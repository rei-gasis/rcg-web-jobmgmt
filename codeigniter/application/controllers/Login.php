<?php
class Login extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}


	function index(){
		$this->load->view("dashboard/login");
	}	


	function login_user_dup(){
		$user_name = $this->input->get('user_name');
		$password = $this->input->get('password');
		$result = "";

		$this->load->model('UserModel');

		if(($this->UserModel->login_user($user_name, md5($password)))){
			$result = "success";
		}else{
			$result = "incorrect";
		}

		echo $user_name;
		echo "<br/>" . $password;
		echo "<br/>" . $result;

	}


	function login_user(){
		try{
			$post_data = file_get_contents('php://input'); 	


			

			$data = json_decode($post_data);

			$this->load->model('UserModel');

			if($this->UserModel->login_user($data->user_name, md5($data->password))){
				/*Login through server*/
				if(strpos($data->user_name, 'admin') > 1){
					$array = array('admin_logged' => 'Y'
						,'user_name' => $data->user_name);

					$this->session->set_userdata($array);	

					// echo $this->session->userdata('admin_logged');

					echo json_encode("Success");

				}
				/*Login through app*/
				else{

					$array = array('user_logged' => 'Y'
						,'user_name' => $data->user_name);
					$this->session->set_userdata($array);

					// echo $this->session->userdata('user_name');

					$user_details = $this->UserModel->login_user($data->user_name, md5($data->password));

					if($user_details){
						echo json_encode(array("result"=>"success","user_id"=>$user_details->user_id,"first_name"=>$user_details->first_name));
						// echo "your cookie: " . get_cookie("cookieci_session");
					}else{
						echo "failed";
					}

					

				}

			}else{

				echo json_encode(array("result"=>"failed"));
			}


			
			

			

		}catch(Exception $ex){
			echo var_dump($ex->getMessage());
		}

	}

	function logout_user(){
		$this->index();

		$this->session->unset_userdata('admin_logged');
	}


}