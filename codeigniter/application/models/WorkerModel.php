<?php

class WorkerModel extends CI_Model {

	var $details;



	function create($data){

		$row = new \StdClass();


		$query = "SELECT 1
		
		FROM tbl_workers
		WHERE first_name = '" . $data['first_name'] . "'" . 
		"AND last_name = '" . $data['last_name'] . "'"
		;

		$row = $this->db->query($query)->row();

		if($row){
			$result = "Worker already registered.";
		}else{

			$this->db->insert('tbl_workers',$data);	
			$result = "Worker registered!";

		}

		return $result;

	}



	function update($data){
		$this->db->where('worker_id', $data['worker_id']);
		if($this->db->update('tbl_workers',$data)){
			return "Worker Updated!";
		}


	}

	function get_job_workers($data){
		$query = "SELECT w.worker_id
		,first_name
		,last_name
		,production_id
		,job_id
		FROM tbl_workers w
		,tbl_production p
		WHERE p.job_id = " . $data . " " .
		"AND w.worker_id = p.worker_id";

		return $this->db->query($query)->result_array();

	}


	function get_worker_list(){
		$query = "SELECT worker_id
		,first_name
		,last_name
		,concat(first_name,' ',last_name) full_name
		,date_created
		FROM tbl_workers";

		$data = $this->db->query($query);

		return $data->result_array();

	}

	function search_worker($data){
		$query = "SELECT worker_id
		,first_name
		,last_name
		,date_created
		FROM tbl_workers
		WHERE concat(first_name, last_name)  LIKE '%"
		. $data
		. "%'"
		;

		$data = $this->db->query($query);

		return $data->result_array();

	}

	function remove($data){
		if($this->db->delete('tbl_workers', array('worker_id'=>$data))){
			return 'Worker Removed!';

		}
	}

	function create_job_worker($data){
		$result = "";
		

		if($this->db->insert('tbl_production',$data))
			$result = "success";
		else 
			$result = "failed";

		return $result;

	}

	function correct($job_id, $data){

		$row = new \StdClass();

		$this->db->from('tbl_production');
		$this->db->where('production_id',$data->production_id);
		

		$row = $this->db->get()->result();

		if(!$row){

			$production_array = array("production_id"=>''
							,"worker_id"=>$data->worker_id
							,"job_id"=>$job_id
							,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z"
						);
			if($this->create_job_worker($production_array)){
				return 'success';
			}else{
				return 'failed';
			}
		}else{
			$this->db->where('job_id', $job_id);
			$this->db->where('production_id', $data->production_id);

			$production_array = array("production_id"=>$data->production_id
							,"worker_id"=>$data->worker_id
							,"job_id"=>$data->job_id
							,"date_start"=>date('Y-m-d') . "T" . date('H:i:s') . ".000Z"
						);

			$data->date_start = date('Y-m-d') . "T" . date('H:i:s') . ".000Z";

			if($this->db->update('tbl_production',$production_array)){
				return "success";
			}else{
				return "failed";
			}
		}



	}
}



?>