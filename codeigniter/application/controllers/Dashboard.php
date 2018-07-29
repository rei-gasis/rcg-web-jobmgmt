<?php
class Dashboard extends BS_Controller {
	public function __construct(){
		parent::__construct();

	}


	function view_dashboard(){

		$this->load->view("dashboard/main");
	} 


	function view_dummy(){

		$this->load->view("dashboard/dummy");
	} 

	function sales(){
		$this->load->view("dashboard/sales");
	} 

	function mydata(){
		$this->load->view("dashboard/mydata");
	}

	function view_categories(){
		$this->load->view("dashboard/category");
	}


	function get_jobs(){

		try{
			$this->load->model('JobModel');
			$job_array = $this->JobModel->get_job_list();

			echo json_encode($job_array);

		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}

	/*client side*/
	function get_job_details_by_id(){
		$post_data = file_get_contents('php://input');

		$this->load->model("JobModel");
		$job_array = $this->JobModel->get_job_details("job_id" , $post_data);

		echo json_encode($job_array);

		// $this->load->view("dashboard/job_details", $job_array);
		
	}


	function view_job_details($param_job_id){
		$this->load->model("JobModel");
		$job_array = $this->JobModel->get_job_details("job_id" , $param_job_id);

		$this->load->view("job/job_details", $job_array);
	}


	function view_job_form($param_job_id = 0, $param_action = ""){
		$this->load->model("JobModel");

		$job_array = $this->JobModel->get_job_details("job_id", $param_job_id);
		$job_array->action = $param_action;

		$this->load->view("job/job_form", $job_array);
	}

	function view_job_transfer($param_job_id){
		$this->load->model("JobModel");
		$job_array = $this->JobModel->get_job_details("job_id" , $param_job_id);

		$this->load->view("job/job_transfer", $job_array);
	}

	function correct_job_details(){
		// $data['first_name'] = $this->input->post('first_name');
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);

		$result = "";

		try{

			$artist_id = (isset($data->artist_id) ? $data->artist_id : false);
			$customer_name = (isset($data->customer_name) ? $data->customer_name : false);
			$customer_contact = (isset($data->customer_contact) ? $data->customer_contact : false);

			if($customer_name){
				$customer_array = array("customer_id"=>""
					,"name"=>$customer_name
					,"contact"=>$customer_contact
					);
				$this->load->model('CustomerModel');
				$customer_id = $this->CustomerModel->create($customer_array);
			}
			else{
				$customer_id = null;
			}


			$this->load->model('JobModel');
			$job_array = array("job_id"=>$data->job_id
				,"date_start"=>$data->date_start
				,"category_id"=>$data->category_id
				,"sales_id"=>$data->sales_id
				,"quantity"=>$data->qty
				,"description"=>$data->description
				,"total_amount"=>$data->total_amount
				,"customer_id"=>$customer_id
				);
			$result = $this->JobModel->correct($job_array);

			

			if($artist_id){
				$this->load->model('LayoutArtistModel');
				$job_layout_array = array("artist_id"=>$data->artist_id
					,"job_id"=>$data->job_id
					,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z"
					);

				$result = $this->LayoutArtistModel->correct($job_layout_array);
				
			}

			
			echo $result;
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());

		}
	}

	function delete_job(){
		$job_id = file_get_contents('php://input'); 
		$result = "";

		try{
			$this->load->model('JobModel');

			$progress_array = array("progress_id"=>''
				,"job_id"=>$job_id
				,"status"=>"Deleted"
				,"details"=>""
				,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z");

			$result = $this->JobModel->update_status($progress_array);
			echo $result;

		}catch(Exception $ex){
			echo var_dump($ex);
		}
	}

	function transfer_job(){
		// $data['first_name'] = $this->input->post('first_name');
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);
		$action = "transfer";

		$result = "";

		try{

			$this->load->model('JobModel');
			$job_array = array("job_id"=>$data->job_id
				,"date_start"=>$data->date_start
				,"sales_id"=>$data->sales_id
				,"category_id"=>$data->category_id
				,"quantity"=>$data->qty
				,"description"=>$data->description
				,"customer_id"=>$data->customer_id
				,"total_amount"=>$data->total_amount
				,"date_due"=>$data->date_due
				,"remarks"=>$data->remarks
				,"last_update_date"=>$data->date_start
				);

			$result = $this->JobModel->update($job_array, $action);

			echo $result;
		 	// echo var_dump($user_array);
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());

		}
	}

	function get_job_progress(){
		$post_data = file_get_contents("php://input");
		$param_job_id = json_decode($post_data);

		$result = "";

		
		try{
			$this->load->model('JobModel');
			$result = $this->JobModel->get_job_progress($param_job_id);

			echo json_encode($result);
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}


	}

	function rollback_progress(){
		$job_id = file_get_contents('php://input'); 
		$result = "";

		try{
			$this->load->model('JobModel');


			$result = $this->JobModel->rollback_status($job_id);
			echo $result;

		}catch(Exception $ex){
			echo var_dump($ex);
		}
	}



	function get_job_payments(){
		$post_data = file_get_contents("php://input");
		$param_job_id = json_decode($post_data);

		$result = "";

		
		try{
			$this->load->model('PaymentModel');
			$result = $this->PaymentModel->get_job_payments($param_job_id);

			echo json_encode($result);
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}

	function correct_payments(){
		$post_data = file_get_contents("php://input");
		$data = json_decode($post_data);

		$result = "";

		
		try{
			$this->load->model('PaymentModel');

			$job_id = $data->job_id;
			$payment_details_array = json_decode(json_encode($data->payment_details)); //to prevent stClass object error

			// $result = $this->PaymentModel->correct($job_id, $payment_details_array[0]);

			foreach($payment_details_array as $payment_array){
				$result = $this->PaymentModel->correct($job_id, $payment_array);
				
				if($result=="failed"){
					break;
				}
			}

			echo $result;
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}

	function correct_workers(){
		$post_data = file_get_contents("php://input");
		$data = json_decode($post_data);

		$result = "";
		
		try{
			$this->load->model('WorkerModel');

			$job_id = $data->job_id;
			$workers_array = json_decode(json_encode($data->workers)); //to prevent stdClass object error

			// $result = $this->WorkerModel->correct($job_id, $workers_array[0]);

			foreach($workers_array as $worker){
				$result = $this->WorkerModel->correct($job_id, $worker);
				
				if($result=="failed"){
					break;
				}
			}
			echo $result;

			// echo print_r($workers_array);
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}



	}


	function get_layout_artist(){
		$post_data = file_get_contents("php://input");
		$param_job_id = json_decode($post_data);


		$this->load->model("JobModel");
		$artist_array = $this->JobModel->get_layout_artist($param_job_id);

		echo json_encode($artist_array);

		// $this->load->view("job/job_details", $job_array);
	}

	function get_job_workers(){
		$post_data = file_get_contents("php://input");
		$param_job_id = json_decode($post_data);

		$result = "";
		
		try{
			$this->load->model('WorkerModel');
			$result = $this->WorkerModel->get_job_workers($param_job_id);

			echo json_encode($result);

		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}


	}
	


	function get_jobs_by_date(){
		$data = file_get_contents('php://input');


		try{
			$this->load->model('JobModel');
			$job_array = $this->JobModel->get_jobs_by_date($data);

			echo json_encode($job_array);

		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}

	function get_total_sales_by_date(){
		$data = file_get_contents('php://input');

		$total_sales = "0";
		try{
			$this->load->model('JobModel');
			$total_sales = $this->JobModel->get_total_sales_by_date($data);

			echo $total_sales;

		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}
	}

	function register_category(){
		// $data['first_name'] = $this->input->post('first_name');
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);

		$result = "";

		try{

			$this->load->model('CategoryModel');
		 	$category_array = array("category_id"=>'' //auto increment
		 		,"name"=>$data->name
		 		,"date_created"=>$data->date_created
		 		);

		 	$result = $this->CategoryModel->create($category_array);

		 	echo $result;
		 	// echo var_dump($user_array);
		 }catch(Exception $ex){
		 	echo var_dump($ex->getMessage());
		 	
		 }
		}

		function update_category(){
		// $data['first_name'] = $this->input->post('first_name');
			$post_data = file_get_contents('php://input');
			$data = json_decode($post_data);

			$result = "";

			try{

				$this->load->model('CategoryModel');
				$category_array = array("category_id"=>$data->category_id
					,"name"=>$data->name
					);

				$result = $this->CategoryModel->update($category_array);

				echo $result;
		 	// echo var_dump($user_array);
			}catch(Exception $ex){
				echo var_dump($ex->getMessage());

			}
		}

		function get_categories(){

			try{
				$this->load->model('CategoryModel');
				$category_array = $this->CategoryModel->get_category_list();

				echo json_encode($category_array);

			}catch(Exception $ex){
				echo var_dump($ex->getMessage());
			}

		}

		function search_category(){
			$data = file_get_contents('php://input');


			try{
				$this->load->model('CategoryModel');
				$category_array = $this->CategoryModel->search_category($data);

				echo json_encode($category_array);

			}catch(Exception $ex){
				echo var_dump($ex->getMessage());
			}

		}

		function remove_category(){
			$category_id = file_get_contents('php://input'); 
			$result = "";

			try{
				$this->load->model('CategoryModel');
				$result = $this->CategoryModel->remove($category_id);
				echo $result;

			}catch(Exception $ex){
				echo var_dump($ex);
			}
		}


		function correct_layout_artist(){
			$post_data = file_get_contents("php://input");
			$data = json_decode($post_data);

			$result = "";


			try{
				$this->load->model('LayoutArtistModel');

				$job_id = $data->job_id;
			$payment_details_array = json_decode(json_encode($data->payment_details)); //to prevent stClass object error

			// $result = $this->PaymentModel->correct($job_id, $payment_details_array[0]);

			foreach($payment_details_array as $payment_array){
				$result = $this->PaymentModel->correct($job_id, $payment_array);
				
				if($result=="failed"){
					break;
				}
			}

			echo $result;
		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}

	function view_layout_artists(){
		$this->load->view("dashboard/layout_artists");
	}


	function register_artist(){
			// $data['first_name'] = $this->input->post('first_name');
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);

		$result = "";

		try{

			$this->load->model('ArtistModel');
			 	$artist_array = array("artist_id"=>'' //auto increment
			 		,"first_name"=>$data->first_name
			 		,"last_name"=>$data->last_name
			 		,"date_created"=>$data->date_created
			 		);

			 	$result = $this->ArtistModel->create($artist_array);

			 	echo $result;
			 	// echo var_dump($user_array);
			 }catch(Exception $ex){
			 	echo var_dump($ex->getMessage());
			 	
			 }
			}

			function update_artist(){
			// $data['first_name'] = $this->input->post('first_name');
				$post_data = file_get_contents('php://input');
				$data = json_decode($post_data);

				$result = "";

				try{

					$this->load->model('ArtistModel');
					$artist_array = array("artist_id"=>$data->artist_id
						,"first_name"=>$data->first_name
						,"last_name"=>$data->last_name
						);

					$result = $this->ArtistModel->update($artist_array);

					echo $result;

			 	// echo 'Artist Updated!';
			 	// echo var_dump($user_array);
				}catch(Exception $ex){
					echo var_dump($ex->getMessage());

				}
			}

			function get_artists(){

				try{
					$this->load->model('ArtistModel');
					$artist_array = $this->ArtistModel->get_artist_list();

					echo json_encode($artist_array);

				}catch(Exception $ex){
					echo var_dump($ex->getMessage());
				}

			}

			function search_artist(){
				$data = file_get_contents('php://input');


				try{
					$this->load->model('ArtistModel');
					$artist_array = $this->ArtistModel->search_artist($data);

					echo json_encode($artist_array);

				}catch(Exception $ex){
					echo var_dump($ex->getMessage());
				}

			}

			function remove_artist(){
				$artist_id = file_get_contents('php://input'); 

				$result = "";
				try{
					$this->load->model('ArtistModel');
					$result = $this->ArtistModel->remove($artist_id);


					echo $result;
				}catch(Exception $ex){
					echo var_dump($ex);
				}
			}

			function view_workers(){
				$this->load->view("dashboard/workers");
			}


			function register_worker(){
			// $data['first_name'] = $this->input->post('first_name');
				$post_data = file_get_contents('php://input');
				$data = json_decode($post_data);

				$result = "";

				try{

					$this->load->model('WorkerModel');
			 		$worker_array = array("worker_id"=>'' //auto increment
			 			,"first_name"=>$data->first_name
			 			,"last_name"=>$data->last_name
			 			,"date_created"=>$data->date_created
			 			);

			 		$result = $this->WorkerModel->create($worker_array);

			 		echo $result;
			 	// echo var_dump($user_array);
			 	}catch(Exception $ex){
			 		echo var_dump($ex->getMessage());

			 	}
			 }

			 function update_worker(){
			// $data['first_name'] = $this->input->post('first_name');
			 	$post_data = file_get_contents('php://input');
			 	$data = json_decode($post_data);

			 	$result = "";

			 	try{

			 		$this->load->model('WorkerModel');
			 		$worker_array = array("worker_id"=>$data->worker_id
			 			,"first_name"=>$data->first_name
			 			,"last_name"=>$data->last_name
			 			);

			 		$result = $this->WorkerModel->update($worker_array);

			 		echo $result;

			 	// echo 'Artist Updated!';
			 	// echo var_dump($user_array);
			 	}catch(Exception $ex){
			 		echo var_dump($ex->getMessage());

			 	}
			 }

			 function get_workers(){

			 	try{
			 		$this->load->model('WorkerModel');
			 		$worker_array = $this->WorkerModel->get_worker_list();

			 		echo json_encode($worker_array);

			 	}catch(Exception $ex){
			 		echo var_dump($ex->getMessage());
			 	}

			 }

			 function search_worker(){
			 	$data = file_get_contents('php://input');


			 	try{
			 		$this->load->model('WorkerModel');
			 		$worker_array = $this->WorkerModel->search_worker($data);

			 		echo json_encode($worker_array);

			 	}catch(Exception $ex){
			 		echo var_dump($ex->getMessage());
			 	}

			 }

			 function remove_worker(){
			 	$worker_id = file_get_contents('php://input'); 

			 	$result = "";
			 	try{
			 		$this->load->model('WorkerModel');
			 		$result = $this->WorkerModel->remove($worker_id);


			 		echo $result;
			 	}catch(Exception $ex){
			 		echo var_dump($ex);
			 	}
			 }




			 function view_users(){
			 	$this->load->view("dashboard/user");
			 }

			 function register_account(){
		// $data['first_name'] = $this->input->post('first_name');
			 	$post_data = file_get_contents('php://input');
			 	$data = json_decode($post_data);

			 	$result = "";

			 	try{

			 		$this->load->model('UserModel');
		 			$user_array = array("user_id"=>'' //auto increment
		 				,"first_name"=>$data->first_name
		 				,"last_name"=>$data->last_name
		 				,"user_name"=>$data->user_name
		 				,"password"=>md5($data->password)
		 				,"date_created"=>$data->date_created
		 				);

		 			$result = $this->UserModel->create($user_array);

		 			echo $result;
		 	// echo var_dump($user_array);
		 		}catch(Exception $ex){
		 			echo var_dump($ex->getMessage());
		 			
		 		}
		 	}

		 	function update_account(){
		// $data['first_name'] = $this->input->post('first_name');
		 		$post_data = file_get_contents('php://input');
		 		$data = json_decode($post_data);

		 		try{

		 			$this->load->model('UserModel');
		 			$user_array = array("user_id"=> $data->user_id
		 				,"first_name"=>$data->first_name
		 				,"last_name"=>$data->last_name
		 				,"user_name"=>$data->user_name
		 				,"password"=>md5($data->password)
		 				,"last_update_date"=>$data->last_update_date
		 				);

		 			$this->UserModel->update($user_array);

		 			echo 'Account Updated!';
		 	// echo var_dump($user_array);
		 		}catch(Exception $ex){
		 			echo var_dump($ex->getMessage());

		 		}
		 	}

		 	function remove_account(){
		$user_id = file_get_contents('php://input'); //user_id only
		// $data = json_decode($post_data);

		try{
			$this->load->model('UserModel');
			$this->UserModel->remove($user_id);
			echo 'Account removed!';

		}catch(Exception $ex){
			echo var_dump($ex);
		}
	}

	function get_accounts(){

		try{
			$this->load->model('UserModel');
			$user_array = $this->UserModel->get_user_list();

			echo json_encode($user_array);

		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}
		



	}

	function get_customers(){

		try{
			$this->load->model('CustomerModel');
			$customer_array = $this->CustomerModel->get_customer_list();

			echo json_encode($customer_array);

		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}



	function view_expenses(){
		$this->load->view("dashboard/expense");
	}

	function create_expense(){
		// $data['first_name'] = $this->input->post('first_name');
		$post_data = file_get_contents('php://input');
		$data = json_decode($post_data);

		$result = "";

		try{

			$this->load->model('ExpenseModel');
		 	$expense_array = array("expense_id"=>'' //auto increment
		 		,"name"=>$data->name
		 		,"amount"=>$data->amount
		 		,"date_start"=>$data->date_start
		 		);

		 	$result = $this->ExpenseModel->create($expense_array);

		 	echo $result;
		 	// echo var_dump($user_array);
		 }catch(Exception $ex){
		 	echo var_dump($ex->getMessage());
		 	
		 }
		}


		function update_expense(){
		// $data['first_name'] = $this->input->post('first_name');
			$post_data = file_get_contents('php://input');
			$data = json_decode($post_data);

			$result = "";

			try{

				$this->load->model('ExpenseModel');
				$expense_array = array("expense_id"=>$data->expense_id
					,"name"=>$data->name
					,"amount"=>$data->amount
					,"date_start"=>$data->date_start
					);

				$result = $this->ExpenseModel->update($expense_array);

				echo $result;
		 	// echo var_dump($user_array);
			}catch(Exception $ex){
				echo var_dump($ex->getMessage());

			}
		}

		function remove_expense(){
			$expense_id = file_get_contents('php://input'); 
			$result = "";

			try{
				$this->load->model('ExpenseModel');
				$result = $this->ExpenseModel->remove($expense_id);
				echo $result;

			}catch(Exception $ex){
				echo var_dump($ex);
			}
		}


		function search_expenses(){
			$data = file_get_contents('php://input');


			try{
				$this->load->model('ExpenseModel');
				$expense_array = $this->ExpenseModel->search_expenses($data);

				echo json_encode($expense_array);

			}catch(Exception $ex){
				echo var_dump($ex->getMessage());
			}

		}


		function get_expenses_by_date(){
			$data = file_get_contents('php://input');


			try{
				$this->load->model('ExpenseModel');
				$expense_array = $this->ExpenseModel->get_expenses_by_date($data);

				echo json_encode($expense_array);

			}catch(Exception $ex){
				echo var_dump($ex->getMessage());
			}

		}


		function get_total_expenses_by_date(){
			$data = file_get_contents('php://input');

			$total_sales = "0";

			try{
				$this->load->model('ExpenseModel');
				$total_sales = $this->ExpenseModel->get_total_expenses_by_date($data);

				echo $total_sales;

			}catch(Exception $ex){
				echo var_dump($ex->getMessage());
			}

		}











	}


	?>