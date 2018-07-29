<?php
class Job extends BS_Controller {
	public function __construct(){
		parent::__construct();
	}

	function get_job_list_by_sales(){
		$post_data = file_get_contents('php://input');

		$data = json_decode($post_data);
		// $post_data = 7;

		$this->load->model("JobModel");
		$job_array = $this->JobModel->get_job_list_by_sales($data->sales_id);

		echo json_encode($job_array);

		// $data = new StdClass();
		// $data['job'] = $job_array;

		// $this->load->view("dashboard/dummy", $data);
		
	}

	function create_job(){
		
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);

		// $data = new \StdClass();
		

		$description = (isset($data->description) ? $data->description : false);
		$customer_name = (isset($data->customer_name) ? $data->customer_name : false);
		$contact = (isset($data->contact) ? $data->contact : false);

		$result = "";

		try{

			$customer_id = null;

			if($customer_name){
				$customer_array = array("customer_id"=>""
					,"name"=>$customer_name
					,"contact"=>$contact
				);
				$this->load->model('CustomerModel');
				$customer_id = $this->CustomerModel->create($customer_array);
				// echo "customerid: " . $customer_id;
			}


			$this->load->model('JobModel');
			$job_array = array("job_id"=>''
				,"category_id"=>$data->category_id
				,"quantity"=>$data->quantity
				,"description"=>$description
				,"customer_id"=>$customer_id
				,"sales_id"=>$data->sales_id
				,"total_amount"=>$data->total_amount
				,"status"=>"Pending"
				,"date_start"=>$data->date_start
				,"date_due"=>$data->date_due
			// ,"remarks"=>$data->remarks
			// ,"last_update_date"=>$data->last_update_date
			);

			$result = $this->JobModel->create($job_array);

			if($result){
				$payment_array = array("payment_id"=>''
								   ,"job_id"=>$result //returned by creating job
								   ,"transaction_type"=>"Down payment"
								   ,"payment_type"=>$data->payment_type
								   ,"amount"=>$data->down_payment
								   ,"details"=>""
								   ,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z");
				
				$this->load->model("PaymentModel");
				$result = $this->PaymentModel->create($payment_array);

				if($result)
					$result = "success";
				else
					$result = "failed";
					$this->JobModel->remove($result);


			}else{
				$result = "failed";
				$this->JobModel->remove($result);
			}


			echo $result;
			
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
			
		}
	}

	function update_status(){
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);

		$result="";

		try{

			$this->load->model('JobModel');
			$progress_array = array("progress_id"=>''
				,"job_id"=>$data->job_id
				,"status"=>$data->status
				,"details"=>""
				,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z");

			$result = $this->JobModel->update_status($progress_array);


			// echo "worker_id" .  $data->worker_id[0]  . "job_id: " . $data->job_id;


			if($data->worker_id){

				if($data->status === "Layout"){
					
					$job_layout_array = array("job_layout_id"=>''
						,"artist_id"=>$data->worker_id[0]
						,"job_id"=>$data->job_id
						,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z"
					);

					$result = $this->JobModel->assign_artist($job_layout_array);
				}else if($data->status === "Production"){
					foreach ($data->worker_id as $worker_id) {
						$production_array = array("production_id"=>''
							,"worker_id"=>$worker_id
							,"job_id"=>$data->job_id
							,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z"
						);

						$this->JobModel->assign_worker($production_array);
						
					}

				}

			}

			echo $result;

			
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}

	function update_payment(){
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);

		$result="";

		try{

			$this->load->model('PaymentModel');
			$payment_array = array("payment_id"=>''
								   ,"job_id"=>$data->job_id
								   ,"transaction_type"=>"Balance"
								   ,"payment_type"=>$data->payment_type
								   ,"amount"=>$data->amount
								   ,"details"=>""
								   ,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z");
			$result = $this->PaymentModel->create($payment_array);

			echo $result;

			
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}
	}
	
}

?>