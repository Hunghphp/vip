
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

	public function index()
	{	
		if($this->session->has_userdata('id_member')){
			$id_member = $this->session->userdata('id_member');
			$data =  array(
					'page' => 'page/index_login',
					'title' => 'Mua token giá rẻ',
					'info'  => $this->m_func->get_info_user($id_member),
					'csrf_token' => array(
				        'name' => $this->security->get_csrf_token_name(),
				        'hash' => $this->security->get_csrf_hash()
					)
			);
			$this->load->view('layout', $data, FALSE);

		}else{
			$data =  array(
					'page' => 'page/index_no_login',
					'title' => 'Shop bán token giá rẻ',
					'csrf_token' => array(
				        'name' => $this->security->get_csrf_token_name(),
				        'hash' => $this->security->get_csrf_hash()
					)
			);
			$this->load->view('layout', $data, FALSE);
		}

	}
	public function Ajax($type){
		header("Content-Type: application/json; charset=UTF-8");
		switch ($type) {
			case 'r_l_member':
				if($this->session->has_userdata('id_member')){
					exit;
				}
				if($this->input->post('email') != ''){
					$result = array(
						'type' => 'warning',
						'message' => 'Có lỗi xảy ra trong quá trình đăng ký'
					);
					$this->form_validation->set_rules('email', 'Email', 'required|min_length[5]|max_length[30]|valid_email|xss_clean');
					$this->form_validation->set_rules('password', 'Mật khẩu', 'required|min_length[8]|max_length[30]|xss_clean');


					if ($this->form_validation->run() == TRUE) {


								$this->db->where('email', $this->input->post('email'));
								$get = $this->db->get('member');
								if($get->num_rows() > 0){
									if($get->result_array()[0]['password'] == $this->m_func->encode_pass($this->input->post('password'))){
										
										$ss_insert = array(
											'id_member' => $get->result_array()[0]['id']
										);
										
										$this->session->set_userdata( $ss_insert );


										$result = array(
											'type' => 'success',
											'message' => 'Đăng nhập thành công  ! Đọi 3s chuyển trang...'
										);	
									}else{
										$result = array(
											'type' => 'warning',
											'message' => 'Sai tên đăng nhập hoặc mật khẩu...'
										);	
									}
								}else{
									//check ip
									$this->db->where('ip_reg', $this->input->ip_address()); 
									$get_ip = $this->db->get('member');
									if($get_ip->num_rows() >=2 ){
													$result = array(
														'type' => 'warning',
														'message' => 'Chỉ đăng ký tối đa 2 tài khoản'
													);	
									}else{
									$array_insert = array(
										'email' => $this->input->post('email'),
										'password' => $this->m_func->encode_pass($this->input->post('password')),
										'money' => 0,
										'time_creat' => time(),
										'active' => 1,
										'ip_reg' => $this->input->ip_address()
									);

									if($this->db->insert('member', $array_insert)){
										$ss_insert = array(
											'id_member' => $this->db->insert_id()
										);
										
										$this->session->set_userdata( $ss_insert );
										$result = array(
											'type' => 'success',
											'message' => 'Đăng ký tài khoản thành công ! Đợi 3s để chuyển trang...'
										);
									}else{
										$result = array(
											'type' => 'warning',
											'message' => 'Có lỗi xảy ra trong quá trình đăng ký'
										);
									}
									}

									

								}



						
						
					} else {
						$result = array(
							'type' => 'warning',
							'message' => validation_errors()
						);
						
					}
				}
				echo json_encode($result);
				break;
			
			default:
				# code...
				break;
		}
	}

}

/* End of file Shop.php */
/* Location: ./application/controllers/Shop.php */
