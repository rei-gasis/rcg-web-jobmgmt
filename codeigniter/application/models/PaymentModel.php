<?php

class PaymentModel extends CI_Model {

	var $details;

	function create($data){
		$result = "";
		

		if($this->db->insert('tbl_payments',$data))
			$result = "success";
		else 
			$result = "failed";

		return $result;

	}

	function get_job_payments($data){
		$sql="SELECT payment_id
		,job_id
		,transaction_type
		,payment_type
		,amount
		,details
		,date_start	
		FROM tbl_payments
		WHERE job_id = " . $data . " " .
		"ORDER BY date_start";
		
		$result = $this->db->query($sql)->result_array();

		return $result;

	}

	function correct($job_id, $data){

		$row = new \StdClass();

		$this->db->from('tbl_payments');
		$this->db->where('job_id', $job_id);
		$this->db->where('payment_id', $data->payment_id);

		$row = $this->db->get()->result();

		if(!$row){
			$payment_array = array("payment_id"=>''
								   ,"job_id"=>$job_id //returned by creating job
								   ,"transaction_type"=>"Balance"
								   ,"payment_type"=>$data->payment_type
								   ,"amount"=>$data->amount
								   ,"details"=>""
								   ,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z");

			
			if($this->create($payment_array)){
				return 'success';
			}else{
				return 'failed';
			}
		}else{

			$this->db->where('job_id', $job_id);
			$this->db->where('payment_id', $data->payment_id);

			$data->last_update_date = date('Y-m-d') . "T" . date('H:i:s') . ".000Z";

			if($this->db->update('tbl_payments',$data)){
				return "success";
			}else{
				return "failed";
			}
		}



	}





	


	// function remove($data){
	// 	if($this->db->delete('tbl_categories', array('category_id'=>$data))){
	// 		return 'Category Removed!';
	// 	}
	// }

	// function get_payment_history($data){
	// 	$query = "SELECT customer_id
	// 	,name
	// 	,contact
	// 	FROM tbl_customers";

	// 	$data = $this->db->query($query);

	// 	return $data->result_array();

	// }


// function update($data){
// 	$this->db->where('category_id', $data['category_id']);
// 	if($this->db->update('tbl_categories',$data)){
// 		return 'Category Updated!';
// 	}
// }


// function get_category_list(){
// 	$query = "SELECT category_id
// 	,name
// 	,date_created
// 	FROM tbl_categories";

// 	$data = $this->db->query($query);

// 	return $data->result_array();

// }

// function search_category($data){
// 			// echo $data;
// 	$query = "SELECT category_id
// 	,name
// 	,date_created
// 	FROM tbl_categories
// 	WHERE name LIKE '%"
// 	. $data
// 	. "%'"
// 	;

// 	$data = $this->db->query($query);

// 	return $data->result_array();

// }




}



?>