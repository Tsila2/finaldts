<?php

class Authenticate extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	public function login()
	{
		$data['title'] = "Se connecter";
		$this->load->view('auth/login', $data);

		// Check if login cookies exist
		if (isset($_COOKIE['login_email']) && isset($_COOKIE['login_password'])) {
			$email = $_COOKIE['login_email'];
			$password = $_COOKIE['login_password'];

			$data = array(
				'email' => $email,
				'pass' => $password
			);

			$res = $this->Auth->login($data);

			if ($res != 0) {
				$username = $res[0]['user_fname'] . " " . $res[0]['user_lname'];
				$image = $res[0]['user_avtar'];
				$uniqueid = $res[0]['unique_id'];

				$session_array = array(
					'username' => $username,
					'image' => $image,
					'uniqueid' => $uniqueid
				);

				$this->load->model('Messagemodel');
				$this->session->set_userdata($session_array);
				$this->Messagemodel->logoutUser('active', '');

				// Redirect to the desired page after successful automatic login
				redirect('message');
			}
		}
	}

	public function signup()
	{
		$this->load->helper('url');
		$this->load->view('auth/signup');
	}

	public function signupData()
	{
		$data = $this->input->post();
		$file = $_FILES;


		if (isset($file) && isset($data)) {
			//File
			$file_name = $file['file_img']['name'];
			$file_tmpname = $file['file_img']['tmp_name'];
			$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$file_new_name = substr(md5(microtime()), rand(0, 25), 8);
			$file_upload_name = $file_new_name . "." . $extension;

			//Data
			$unique_id = substr(md5(microtime()), rand(0, 25), 6);
			$user_fname = $data['txt_fname'];
			$user_lname = $data['txt_lname'];
			$user_email = $data['txt_email'];
			$user_pass = $data['txt_pass'];
			$created_at = $data['created_at'];
			$user_avtar = $file_upload_name;
			$user_status = 'active';

			$data_arr = array(
				'unique_id' => $unique_id,
				'user_fname' => $user_fname,
				'user_lname' => $user_lname,
				'user_email' => $user_email,
				'user_pass' => $user_pass,
				'user_avtar' => $user_avtar,
				'created_at' => $created_at,
				'user_status' => $user_status

			);
			$email = $this->Auth->checkEmail($user_email);

			if (isset($email[0]['user_email'])) {
				echo "Email is already exist";
			} else {
				$this->Auth->signup($data_arr);
				$this->Auth->idUpdate();
				move_uploaded_file($file_tmpname, "./upload/" . $file_upload_name);


				$username = $user_fname . " " . $user_lname;
				$image = $user_avtar;
				$session_arr = array(
					'username' => $username,
					'image' => $image,
					'uniqueid' => $unique_id
				);
				$this->session->set_userdata($session_arr);
			}
		}
	}
	public function loginData()
	{
		if (isset($_POST['txt_email']) && isset($_POST['txt_pass'])) {
			$email = $_POST['txt_email'];
			$password = $_POST['txt_pass'];

			$data = array(
				'email' => $email,
				'pass' => $password
			);

			$res = $this->Auth->login($data);

			if ($res != 0) {
				$username = $res[0]['user_fname'] . " " . $res[0]['user_lname'];
				$image = $res[0]['user_avtar'];
				$uniqueid = $res[0]['unique_id'];

				$session_array = array(
					'username' => $username,
					'image' => $image,
					'uniqueid' => $uniqueid
				);

				$this->load->model('Messagemodel');
				$this->session->set_userdata($session_array);
				$this->Messagemodel->logoutUser('active', '');

				// Store the login data in storage (e.g., cookies, local storage, etc.)
				$this->storeLoginData($email, $password);

				print_r($res);
			} else {
				echo 0;
			}
		}
	}

	// Function to store login data in storage
	private function storeLoginData($email, $password)
	{
		// Store the email and password in the desired storage (e.g., cookies, local storage, etc.)
		// Example using cookies:
		setcookie('login_email', $email, time() + (86400 * 30), '/'); // Cookie expires in 30 days
		setcookie('login_password', $password, time() + (86400 * 30), '/'); // Cookie expires in 30 days
	}

}

?>