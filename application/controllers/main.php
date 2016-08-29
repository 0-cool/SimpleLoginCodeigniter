<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		$this->login();
	}

	public function login(){
		$this->load->view('login');
	}

	public function signup(){
		$this->load->view('signup');
	}

	public function members(){
		if ($this->session->userdata('is_logged_in')) {
			$this->load->view('members');
		}else{
			redirect('main/index');
		}
	}
	public function login_validation(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email','Email','required|trim|callback_validate_credentials');
		$this->form_validation->set_rules('password','Password','required|md5|trim');

		if ($this->form_validation->run()) {
			$data = array(
					'email' => $this->input->post('email'),
					'is_logged_in' => 1
				);
			$this->session->set_userdata($data);
			redirect('main/members');	
		}else{
			$this->load->view('login');
		}
	}

	public function signup_validation(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password','Password','required|trim');
		$this->form_validation->set_rules('cpassword','Confirm Password','required|trim|matches[password]');

		$this->form_validation->set_message('is_unique', "The email address alreay exist.");

		if ($this->form_validation->run()) {

			//generate random key
			$key = md5(uniqid());
			$this->load->library('email', array('mailtype'=>'html'));
			$this->load->model('model_users');
			
			$this->email->from('me@website.com', "Steven");
			$this->email->to($this->input->post('email'));
			$this->email->subject("Confirm your account.");

			$message = "<p>Thank you for signing up!</p>";
			$message .= "<p><a href='".base_url()."main/register_user/$key'>Click Here</a>
			to confirm your account.</p>";

			$this->email->message($message);
			
			if ($this->model_users->add_temp_user($key)) {
				if ($this->email->send()) {
				echo $this->input->post('email');
				echo "The emails has been send it!";
				}else{
					echo "Dont send it!";
				}
			}else{
				echo "Problem adding to database.";
			}
		}else{
			$this->load->view('signup');
		}
	}

	public function validate_credentials(){
		$this->load->model('model_users');

		if ($this->model_users->can_log_in()) {
			return true;
		}else{
			$this->form_validation->set_message('validate_credentials','Incorrect username/password.');

			return false;
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('main/login');
	}

	public function register_user($key){
		$this->load->model('model_users');

		if ($this->model_users->is_valid_key($key)) {
			if ($newemail = $this->model_users->add_user($key)) {
				$data = array(
					'email' => $newemail,
					'is_logged_in' => 1
					);
				$this->session->set_userdata($data);
				redirect('main/members');
			}else echo "failed to add user, please try again";
		}else echo "invalid key";
	}
}
