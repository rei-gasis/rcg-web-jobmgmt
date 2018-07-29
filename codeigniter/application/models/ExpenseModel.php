<?php

class ExpenseModel extends CI_Model {

	var $details;


	function create($data){

		if($this->db->insert('tbl_expenses',$data)){
			$result = "success";
		}else{	
			$result = "failed";

		}

		return $result;

	}

	function update($data){
		$this->db->where('expense_id', $data['expense_id']);
		if($this->db->update('tbl_expenses',$data)){
			return $result = "success";
		}
	}


	function remove($data){
		if($this->db->delete('tbl_expenses', array('expense_id'=>$data))){
			return $result = "success";
		}
	}

	function get_expenses_by_date($param){
		$query = "SELECT expense_id
				,name
				,amount
				,date_start
		FROM tbl_expenses 
		WHERE DATE(date_start) = DATE(" . $param . ") " .
		"ORDER BY date_start DESC
		";

		$data = $this->db->query($query);

		return $data->result_array();

	}

	function search_expenses($data){
			// echo $data;
		$query = "SELECT expense_id
		,name
		,amount
		,date_start
		FROM tbl_expenses
		WHERE name LIKE '%"
		. $data
		. "%'"
		;

		$data = $this->db->query($query);

		return $data->result_array();

	}

	

	function get_total_expenses_by_date($param){
		$result = "";

		$query = "SELECT SUM(amount) total_amount
				  FROM tbl_expenses
				  WHERE DATE(date_start) = DATE(" . $param . ")"
				;

		$result = $this->db->query($query)->row()->total_amount;

		return $result;

	}






}



?>