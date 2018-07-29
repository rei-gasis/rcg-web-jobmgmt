<?php

	class ArtistModel extends CI_Model {

		var $details;



		function create($data){

			$row = new \StdClass();


			/*validate existing record*/
			$query = "SELECT 1
							-- ,name
							-- ,date_created
					 FROM tbl_layout_artists
					 WHERE first_name = '" . $data['first_name'] . "'" . 
					 "AND last_name = '" . $data['last_name'] . "'"
					;

			$row = $this->db->query($query)->row();

			if($row){
				$result = "Artist already registered.";
			}else{
				
				$this->db->insert('tbl_layout_artists',$data);	
				$result = "Layout Artist registered!";
			
			}

			return $result;
			
		}

		function update($data){
			$this->db->where('artist_id', $data['artist_id']);
			if($this->db->update('tbl_layout_artists',$data)){
				return "Artist Updated!";
			}


		}


		function get_artist_list(){
			$query = "SELECT artist_id
							,first_name
							,last_name
							,CONCAT(first_name,' ',last_name) full_name
							,date_created
					 FROM tbl_layout_artists";

			$data = $this->db->query($query);

			return $data->result_array();

		}

		function search_artist($data){
			// echo $data;
			$query = "SELECT artist_id
							,first_name
							,last_name
							,date_created
					 FROM tbl_layout_artists
					 WHERE concat(first_name, last_name)  LIKE '%"
														 . $data
														 . "%'"
					;

			$data = $this->db->query($query);

			return $data->result_array();

		}

		function remove($data){
			if($this->db->delete('tbl_layout_artists', array('artist_id'=>$data))){
				return 'Layout Artist Removed!';

			}
		}
	}



?>