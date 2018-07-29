<?php
class BS_Controller extends CI_Controller{

	public function __construct(){
		parent::__construct();

		

		// echo $this->session->userdata('admin_logged');

		if($this->session->userdata('admin_logged') === 'Y'){
			// redirect(base_url() . 'dashboard/view_dashboard', false);
			// header('Location:' . base_url() . 'dashboard/view_dashboard');

		}else{

			$detect = new Mobile_Detect();
			if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
    		
    		}else{
    			header('Location: ' . base_url());
    		}

			// $this->load->view("dashboard/login");
			

			// die();
			//used header instead of redirect
			//because app cannot use controllers


			// die();
			// $this->load->view('dashboard/login');
			// redirect(base_url());
			// echo $this->session->userdata('admin_logged');
		}
	}

}

?>