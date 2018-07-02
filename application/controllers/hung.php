<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hung extends CI_Controller {

	public function index($ok = '')
	{
		if($this->session->has_userdata('admin')){

			switch ($ok) {
				case 'add_clone':
					$this->load->view('admin/add_clone');
					break;
				
				default:
					$this->load->view('admin/index');
					break;
			}
			

		}else{
			if($this->input->post('password') !=''){
				if($this->input->post('password') == '2110'){
					$array = array(
						'admin' => '1'
					);
					
					$this->session->set_userdata( $array );
					echo '<script>alert("Dang nhap thanh cong ! Nhan ok de tai lai!");';
					redirect('/Hung','refresh');
				}
			}
			$this->load->view('admin/login');
		}
	}
	public function logout(){
		session_destroy();
		echo '<script>alert("Dang xuat thanh cong !"); </script>';
		redirect('Hung/index','refresh');
	}
	public function Ajax($type){
		switch ($type) {
			case 'add_clone':
				echo json_encode($this->input->post());
				break;
			
			default:
				# code...
				break;
		}
	}

}

/* End of file Hung.php */
/* Location: ./application/controllers/Hung.php */
