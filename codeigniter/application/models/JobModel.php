<?php

class JobModel extends CI_Model {

	var $details;



	function create($data){
		$result = "";
		$job_id = null;

		if($this->db->insert('tbl_jobs',$data))
			$job_id = $this->db->insert_id();	

		

		if($job_id){

			$progress_array = array("progress_id"=>''
				,"job_id"=>$job_id
				,"status"=>"Pending"
				,"details"=>""
				,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z");

			if($this->db->insert('tbl_progress',$progress_array)){
				$result = "Job created!";

			}else{
				$this->db->delete('tbl_jobs',array('job_id'=>$job_id));
				$result = "Job not created!";
			}
			
		}else{
			$result = "Job not created!";
		}

		return $job_id;

	}


	function update_status($data){
		$result = "";


		if($this->db->insert('tbl_progress',$data)){
			$result = "success";
		}else{
			$result = "failed";

		}

		return $result;


	}

	function rollback_status($param){
		$progress_id = "";

		$result = "";

		$query = "SELECT progress_id, status
		FROM tbl_progress prog
		WHERE job_id = " . $param . " " .
		"AND date_start = (SELECT MAX(date_start)
		FROM tbl_progress prog_1
		WHERE prog.job_id = prog_1.job_id
		)"
		;


		$progress_id = $this->db->query($query)->row()->progress_id;
		$status = $this->db->query($query)->row()->status;

		if($this->db->delete('tbl_progress', array('progress_id'=>$progress_id)))
			$result = "success";
		else
			$result = "failed";


		if($result==="success"){

		
			if($status==="Layout"){
				if($this->db->delete('tbl_job_layout',array("job_id"=>$param)))
					return "success";
				else
					return "failed";

			}

			else if($status==="Production"){
				if($this->db->delete('tbl_production',array("job_id"=>$param)))
					return "success";
				else
					return "failed";
			}
		}



	}

	function assign_artist($data){
		$result = "";


		if($this->db->insert('tbl_job_layout',$data)){
			$result = "success";
		}else{
			$result = "failed";

		}

		return $result;


	}

	function assign_worker($data){
		$result = "";


		if($this->db->insert('tbl_production',$data)){
			$result = "success";
		}else{
			$result = "failed";

		}

		return $result;


	}

	// function rollback_status($data){


	// }



	function update($data, $action){

		if($action === 'transfer'){

			$row = new \StdClass();


			/*validate recent transfer record*/
			$query = "SELECT sales_id
			FROM tbl_jobs job
			WHERE job_id = " . $data['job_id'] . " " .
			"AND date_start = (SELECT MAX(date_start)
			FROM tbl_jobs job_1
			WHERE job_1.job_id = job.job_id)" ;

			$row = $this->db->query($query)->row();

			if($row && $row->sales_id === $data['sales_id']){
				$result = "You just recently transferred this job to the same person.";
			}else if($this->db->insert('tbl_jobs',$data)){
				$result = "Job Transferred!";
			}


			return $result;
		}
	}

	function correct($data){

		$this->db->where('job_id', $data['job_id']);
		$this->db->where('date_start', $data['date_start']);
		if($this->db->update('tbl_jobs',$data)){
			return 'success';
		}

		// $this->db->where('user_id', $data['user_id']);
		// $this->db->where('date_start', $data['date_start']);
		// $this->db->update('tbl_jobs',$data);
	}



	/*Get latest detail*/
	function get_job_details($criteria, $param){

		$query = "SELECT job_id
		,(SELECT name
		FROM tbl_categories cat
		WHERE job.category_id = cat.category_id) category
		,(SELECT category_id
		FROM tbl_categories cat
		WHERE job.category_id = cat.category_id) category_id
		,quantity qty
		,total_amount
		,date_start
		,(SELECT MIN(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id) date_received
		,date_due
		,(SELECT CONCAT(first_name, ' ', last_name)
		FROM tbl_users user
		WHERE user.user_id = job.sales_id)sales
		,sales_id
		,(SELECT status
		FROM tbl_progress prog
		WHERE job_id = job.job_id
		AND date_start = (SELECT MAX(date_start)
		FROM tbl_progress prog_1
		WHERE prog.job_id = prog_1.job_id
		)
		)status
		,description
		,(SELECT artist_id
		FROM tbl_job_layout jl
		WHERE jl.job_id = job.job_id) artist_id
		,(SELECT name
		FROM tbl_customers cust
		WHERE cust.customer_id = job.customer_id) customer_name
		,(SELECT contact
		FROM tbl_customers cust
		WHERE cust.customer_id = job.customer_id) customer_contact
		,(SELECT amount
		FROM tbl_payments p
		WHERE job.job_id = p.job_id
		AND transaction_type = 'Down payment') down_payment
		,(SELECT amount
		FROM tbl_payments p
		WHERE job.job_id = p.job_id
		AND transaction_type = 'Down payment') down_payment
		,(SELECT total_amount - SUM(amount)
		FROM tbl_payments p
		WHERE job.job_id = p.job_id) balance
		,remarks
		FROM tbl_jobs job
		WHERE " . $criteria . " = " . $param . " " . 
		"AND date_start = (SELECT MAX(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id)
		ORDER BY date_due ,
		CASE WHEN status = 'Pending'
		THEN 1
		WHEN status = 'Layout'
		THEN 2	
		WHEN status = 'Production'
		THEN 3
		WHEN status = 'Completed'
		THEN 4
		WHEN status = 'Claimed'
		THEN 5
		END DESC";


		return $this->db->query($query)->row();



	}


	function get_job_list(){
		$query = "SELECT job_id
		,(SELECT name
		FROM tbl_categories cat
		WHERE job.category_id = cat.category_id) category
		,quantity qty
		,total_amount
		,(SELECT MIN(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id) date_received
		,date_due
		,(SELECT CONCAT(first_name, ' ', last_name)
		FROM tbl_users user
		WHERE user.user_id = job.sales_id)sales
		,(SELECT status
		FROM tbl_progress prog
		WHERE job_id = job.job_id
		AND date_start = (SELECT MAX(date_start)
		FROM tbl_progress prog_1
		WHERE prog.job_id = prog_1.job_id
		)
		)status
		,c.name customer_name
		,c.contact
		,(SELECT amount
		FROM tbl_payments p
		WHERE job.job_id = p.job_id
		AND transaction_type = 'Down payment') down_payment
		FROM tbl_jobs job
		LEFT JOIN tbl_customers c
		ON c.customer_id = job.customer_id
		WHERE date_start = (SELECT MAX(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id)
		ORDER BY date_due ,
		CASE WHEN status = 'Pending'
		THEN 1
		WHEN status = 'Layout'
		THEN 2	
		WHEN status = 'Production'
		THEN 3
		WHEN status = 'Completed'
		THEN 4
		WHEN status = 'Claimed'
		THEN 5
		END DESC";

		$data = $this->db->query($query);

		return $data->result_array();

	}

	function get_total_sales_by_date($param){
		$result = "";

		$query = "SELECT SUM(amount) total_amount
		FROM tbl_payments
		WHERE DATE(date_start) = DATE(" . $param . ")"
		;
		$result = $this->db->query($query)->row()->total_amount;

		return $result;

	}

	function get_jobs_by_date($param){
		$query = "SELECT job.job_id
		,(SELECT name
		FROM tbl_categories cat
		WHERE job.category_id = cat.category_id) category
		,quantity qty
		,total_amount
		,(SELECT MIN(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id) date_received
		,date_due
		,(SELECT CONCAT(first_name, ' ', last_name)
		FROM tbl_users user
		WHERE user.user_id = job.sales_id)sales
		,c.name customer_name
		,c.contact
		,(SELECT amount
		FROM tbl_payments p
		WHERE job.job_id = p.job_id
		AND transaction_type = 'Down payment') down_payment
		,p.status
		FROM tbl_jobs job
		LEFT JOIN tbl_customers c
		ON c.customer_id = job.customer_id
		LEFT JOIN tbl_progress p
		ON job.job_id = p.job_id
		WHERE job.date_start = (SELECT MAX(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id)
		AND p.date_start = (SELECT MAX(date_start)
		FROM tbl_progress prog_1
		WHERE p.job_id = prog_1.job_id
		)
		AND p.status <> 'Deleted'
		AND DATE(job.date_start) = DATE(" . $param . ")" . 
		"ORDER BY date_due ,
		CASE WHEN p.status = 'Pending'
		THEN 1
		WHEN p.status = 'Layout'
		THEN 2	
		WHEN p.status = 'Production'
		THEN 3
		WHEN p.status = 'Completed'
		THEN 4
		WHEN p.status = 'Claimed'
		THEN 5
		END DESC";



		$data = $this->db->query($query);



		return $data->result_array();

	}

	function get_job_list_by_sales($param){
		$query = "SELECT job.job_id
		,(SELECT name
		FROM tbl_categories cat
		WHERE job.category_id = cat.category_id) category
		,quantity qty
		,total_amount
		,(SELECT MIN(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id) date_received
		,date_due
		,(SELECT CONCAT(first_name, ' ', last_name)
		FROM tbl_users user
		WHERE user.user_id = job.sales_id)sales
		,COALESCE(c.name ,'') customer_name
		,COALESCE(c.contact ,'') contact
		,(SELECT amount
		FROM tbl_payments p
		WHERE job.job_id = p.job_id
		AND transaction_type = 'Down payment') down_payment
		,(SELECT total_amount - SUM(amount)
		FROM tbl_payments p
		WHERE job.job_id = p.job_id) balance
		,p.status
		,COALESCE(job.description ,'') description
		FROM tbl_jobs job
		LEFT JOIN tbl_customers c
		ON c.customer_id = job.customer_id
		LEFT JOIN tbl_progress p
		ON job.job_id = p.job_id
		WHERE job.date_start = (SELECT MAX(date_start)
		FROM tbl_jobs job_1
		WHERE job_1.job_id = job.job_id)
		AND p.date_start = (SELECT MAX(date_start)
		FROM tbl_progress prog_1
		WHERE p.job_id = prog_1.job_id
		)
		AND p.status <> 'Deleted'
		AND sales_id = " . $param . " " .
		"ORDER BY date_due ,
		CASE WHEN p.status = 'Pending'
		THEN 1
		WHEN p.status = 'Layout'
		THEN 2	
		WHEN p.status = 'Production'
		THEN 3
		WHEN p.status = 'Completed'
		THEN 4
		WHEN p.status = 'Claimed'
		THEN 5
		END DESC";

        // echo $query;

		$data = $this->db->query($query);

		return $data->result_array();

	}

	function get_job_progress($data){
		$sql="SELECT status	
		,date_start
		FROM tbl_progress
		WHERE job_id = " . $data . " " .
		"ORDER BY date_start";

		$result = $this->db->query($sql)->result_array();

		return $result;

	}

	function get_layout_artist($data){
		$query = "SELECT la.artist_id
		,COALESCE(first_name,'') first_name
		,COALESCE(last_name,'') last_name
		FROM tbl_layout_artists la
		,tbl_job_layout jl
		WHERE jl.job_id = " . $data . " " .
		"AND la.artist_id = jl.artist_id";

		return $this->db->query($query)->row();

	}




	function remove($data){
		$this->db->delete('tbl_jobs', array('job_id'=>$data));
	}




	function set_session(){



		$user_data = array('id'

			,'name'=> $this->details->first_name . ' ' . $this->details->last_name

			,'isLoggedIn'=> true

			);

		$this->session->set_userdata($user_data);



	}





}



?>