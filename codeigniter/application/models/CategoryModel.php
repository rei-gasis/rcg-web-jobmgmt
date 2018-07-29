<?php

	class CategoryModel extends CI_Model {

		var $details;



		

		function create($data){

			$row = new \StdClass();


			/*validate existing record*/
			$query = "SELECT name
							-- ,name
							-- ,date_created
					 FROM tbl_categories
					 WHERE name = '" . $data['name'] . "'"
					;

			$row = $this->db->query($query)->row();

			if($row){
				$result = "Category already registered.";
			}else{
				
				$this->db->insert('tbl_categories',$data);	
				$result = "Category registered!";
			
			}

			return $result;
			
		}

		function update($data){
			$this->db->where('category_id', $data['category_id']);
			if($this->db->update('tbl_categories',$data)){
				return 'Category Updated!';
			}
		}


		function get_category_list(){
			$query = "SELECT category_id
							,name
							,date_created
					 FROM tbl_categories";

			$data = $this->db->query($query);

			return $data->result_array();

		}

		function search_category($data){
			// echo $data;
			$query = "SELECT category_id
							,name
							,date_created
					 FROM tbl_categories
					 WHERE name LIKE '%"
									 . $data
									 . "%'"
					;

			$data = $this->db->query($query);

			return $data->result_array();

		}

		function remove($data){
			if($this->db->delete('tbl_categories', array('category_id'=>$data))){
				return 'Category Removed!';
			}
		}






	}



?>