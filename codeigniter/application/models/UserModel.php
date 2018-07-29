<?php

	class UserModel extends CI_Model {

		var $details;



		function login_user($username, $password){

			$this->db->from('tbl_users');

			$this->db->where('user_name', $username);

			$this->db->where('password', $password);

			$login = $this->db->get()->result();



			if(is_array($login) && count($login) == 1) {

				$this->details = $login[0];

				// 

				return $this->details;

			}



		}

		function create($data){

			$row = new \StdClass();


			/*validate existing record*/
			$query = "SELECT user_name
					 FROM tbl_users
					 WHERE user_name = '" . $data['user_name'] . "'"
					;

			$row = $this->db->query($query)->row();

			if($row){
				$result = "User name already registered.";
			}else{
				
				$this->db->insert('tbl_users',$data);	
				$result = "User registered!";
			
			}

			return $result;
			
		}

		function update($data){
			$this->db->where('user_id', $data['user_id']);
			$this->db->update('tbl_users',$data);
		}


		function get_user_list(){
			$query = "SELECT user_id
							,user_name
							,first_name
							,last_name
							,concat(first_name, ' ', last_name) full_name
							,date_created
							,last_update_date
					 FROM tbl_users";

			$data = $this->db->query($query);

			return $data->result_array();

		}

		function remove($data){
			$this->db->delete('tbl_users', array('user_id'=>$data));
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