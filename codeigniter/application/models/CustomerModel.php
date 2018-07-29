<?php

	class CustomerModel extends CI_Model {

		var $details;




		function create($data){

			$row = new \StdClass();
			$result = "";


			/*validate existing record*/
			$query = "SELECT name
					 FROM tbl_customers
					 WHERE name = '" . $data['name'] . "'"
					;

			$row = $this->db->query($query)->row();

			if($row){
				$result = "Customer already registered";
			}else{
				$this->db->insert('tbl_customers',$data);	
				$result = $this->db->insert_id();
			}

			return $result;
		}

		// function update($data){
		// 	$this->db->where('category_id', $data['category_id']);
		// 	if($this->db->update('tbl_categories',$data)){
		// 		return 'Category Updated!';
		// 	}
		// }


		function get_customer_list(){
			$query = "SELECT customer_id
							,name
							,contact
					 FROM tbl_customers";

			$data = $this->db->query($query);

			return $data->result_array();

		}

		// function search_category($data){
		// 	// echo $data;
		// 	$query = "SELECT category_id
		// 					,name
		// 					,date_created
		// 			 FROM tbl_categories
		// 			 WHERE name LIKE '%"
		// 							 . $data
		// 							 . "%'"
		// 			;

		// 	$data = $this->db->query($query);

		// 	return $data->result_array();

		// }

		// function remove($data){
		// 	if($this->db->delete('tbl_categories', array('category_id'=>$data))){
		// 		return 'Category Removed!';
		// 	}
		// }






	}



?>