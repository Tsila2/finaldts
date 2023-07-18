<?php
class Message extends CI_controller
{
	public function index()
	{
		if (isset($_SESSION['uniqueid'])) {
			$this->loadMessageView();
		} else if (isset($_COOKIE['login_email']) && isset($_COOKIE['login_password'])) {
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

				// Clear cookies to avoid multiple refreshes
				unset($_COOKIE['login_email']);
				unset($_COOKIE['login_password']);
				setcookie('login_email', '', time() - 3600);
				setcookie('login_password', '', time() - 3600);

				$this->loadMessageView();
			}
		} else {
			redirect('/');
		}
	}

	private function loadMessageView()
	{
		$data['data'] = $this->Messagemodel->ownerDetails();
		$data['title'] = "E-Resaka - Message";
		$this->load->view('message/message', $data);
	}

	public function ownerDetails()
	{
		$res = $this->Messagemodel->ownerDetails();
		print_r(json_encode($res));
	}
	public function allUser()
	{
		sleep(0.01);
		$data['data'] = $this->Messagemodel->allUser();
		$data['last_msg'] = array();
		$this->load->helper('url');
		if (!is_array($data['data'])) {
			echo "<p class='text-center'>Aucun utilisateur disponible. Veuillez rafraîchir la page !</p>";
		} else {
			$count = count($data['data']);
			for ($i = 0; $i < $count; $i++) {
				$unique_id = $data['data'][$i]['unique_id'];
				$msg = $this->Messagemodel->getLastMessage($unique_id);
				for ($j = 0; $j < count($msg); $j++) {
					$time = explode(" ", $msg[0]['time']); // 00:00:00.0000
					$time = explode(".", $time[1]); // 00:00:00
					$time = explode(":", $time[0]); // 00 00 00
					if ((int) $time[0] == 12) {
						$time = $time[0] . ":" . $time[1] . " PM";
					} elseif ((int) $time[0] > 12) {
						$time = ($time[0] - 12) . ":" . $time[1] . " PM";
					} else {
						$time = $time[0] . ":" . $time[1] . " AM";
					}

					$data['last_msg'][] = array(
						'message' => $msg[0]['message'],
						'image_path' => $msg[0]['image_path'],
						'file_path' => $msg[0]['file_path'],
						'sender_id' => $msg[0]['sender_message_id'],
						'receiver_id' => $msg[0]['receiver_message_id'],
						'time' => $time, // 00:00
					);
				}
			}
			$this->load->view('message/sampleDataShow', $data);
		}

	}
	public function getIndividual()
	{
		$returnVal = $this->Messagemodel->getIndividual($_POST['data']);
		print_r(json_encode($returnVal, true));
	}
	public function logout()
	{
		$date = $_POST['date'];
		$this->load->helper('url');
		$this->Messagemodel->logoutUser('deactive', $date);

		// Clear login cookies
		setcookie('login_email', '', time() - 3600, '/');
		setcookie('login_password', '', time() - 3600, '/');

		unset(
			$_SESSION['uniqueid'],
			$_SESSION['username'],
			$_SESSION['image']
			);

		echo base_url();
	}

	public function close()
	{
		$date = $_POST['date'];
		$this->load->helper('url');
		$this->Messagemodel->logoutUser('deactive', $date);

		unset(
			$_SESSION['uniqueid'],
			$_SESSION['username'],
			$_SESSION['image']
			);

		echo base_url();
	}

	public function setNoMessage()
	{
		$data['image'] = $_POST['image'];
		$data['name'] = $_POST['name'];
		$this->load->view('message/notmessageyet', $data);
	}
	public function sendMessage()
	{
		if (isset($_POST['data']) && isset($_SESSION['uniqueid'])) {
			$jsonDecode = json_decode($_POST['data'], true);
			$uniq = $_SESSION['uniqueid'];
			$arr = array(
				'time' => $jsonDecode['datetime'],
				'sender_message_id' => $uniq,
				'receiver_message_id' => $jsonDecode['uniq'],
				'message' => $jsonDecode['message'],
			);
			$this->Messagemodel->sentMessage($arr);
		}
	}

	public function sendImage()
	{
		if (isset($_POST['datetime']) && isset($_SESSION['uniqueid'])) {
			$uniq = $_SESSION['uniqueid'];

			// Get the image file
			$file = $_FILES['file'];
			$file_name = $file['name'];
			$file_tmpname = $file['tmp_name'];
			$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

			// Check if the file is an image
			$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
			if (in_array($extension, $allowed_extensions)) {
				// Generate a unique filename
				$unique_file_name = $this->generateUniqueFileName($file_name, $extension);

				// Move the uploaded file to the destination directory
				move_uploaded_file($file_tmpname, "upload/messages/images/" . $unique_file_name);

				$arr = array(
					'time' => $_POST['datetime'],
					'sender_message_id' => $uniq,
					'receiver_message_id' => $_POST['uniq'],
					'image_path' => $unique_file_name,
				);

				$this->Messagemodel->sentMessage($arr);

				echo "success";
			} else {
				echo "Not allowed.";
			}
		} else {
			echo "error";
		}
	}

	private function generateUniqueFileName($file_name, $extension)
	{
		$directory = "upload/messages/images/";
		$new_file_name = $file_name;
		$i = 1;

		while (file_exists($directory . $new_file_name)) {
			$new_file_name = pathinfo($file_name, PATHINFO_FILENAME) . "_" . $i . "." . $extension;
			$i++;
		}

		return $new_file_name;
	}

	private function generateUniqueAudioName($file_name, $extension)
	{
		$directory = "upload/messages/audios/";
		$new_file_name = $file_name;
		$i = 1;

		while (file_exists($directory . $new_file_name)) {
			$new_file_name = pathinfo($file_name, PATHINFO_FILENAME) . "_" . $i . "." . $extension;
			$i++;
		}

		return $new_file_name;
	}


	public function sendFile()
	{
		if (isset($_POST['datetime']) && isset($_SESSION['uniqueid'])) {
			$uniq = $_SESSION['uniqueid'];

			// Get the file
			$file = $_FILES['file'];
			$file_name = $file['name'];
			$file_tmpname = $file['tmp_name'];
			// $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			// $file_new_name = substr(md5(microtime()), rand(0, 25), 8);
			// $file_upload_name = $file_new_name . "." . $extension;

			// Move the uploaded file to the destination directory
			move_uploaded_file($file_tmpname, "upload/messages/files/" . $file_name);

			$file_download_link = site_url('upload/messages/files/' . $file_name);

			$arr = array(
				'time' => $_POST['datetime'],
				'sender_message_id' => $uniq,
				'receiver_message_id' => $_POST['uniq'],
				'file_path' => $file_name,
				'message' => '<a href="' . $file_download_link . '">' . $file_name . '</a>'
			);

			$this->Messagemodel->sentMessage($arr);

			echo "success";
		} else {
			echo "Error";
		}
	}

	public function sendAudio()
	{
		if (isset($_POST['datetime']) && isset($_SESSION['uniqueid'])) {
			$uniq = $_SESSION['uniqueid'];

			// Get the audio file
			$file = $_FILES['file'];
			$file_name = $file['name'];
			$file_tmpname = $file['tmp_name'];
			$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

			// Generate a unique filename
			$unique_file_name = $this->generateUniqueAudioName($file_name, $extension);

			// Move the uploaded file to the destination directory
			move_uploaded_file($file_tmpname, "upload/messages/audios/" . $unique_file_name);

			$arr = array(
				'time' => $_POST['datetime'],
				'sender_message_id' => $uniq,
				'receiver_message_id' => $_POST['uniq'],
				'audio_path' => $unique_file_name,
			);

			$this->Messagemodel->sentMessage($arr);

			echo "success";
		} else {
			echo "error";
		}
	}

	public function getAllLastMessage()
	{
		// $lastData = $this->db->order_by('time', 'desc')->limit(1)->get('time')->row();
		$lastData = $this->Messagemodel->getLastMessage($_POST['data']);

		// Check if there is any data in the table
		if ($lastData) {
			$lastId = $lastData[0]['id'];

			// Check if this is the first time checking
			if (!$this->session->has_userdata('last_id')) {
				// Store the last ID in session for future comparison
				$this->session->set_userdata('last_id', $lastId);
			} else {
				$previousLastId = $this->session->userdata('last_id');

				// Compare the last ID with the previous one
				if ($lastId > $previousLastId) {
					// Update the stored last ID in session with the current one
					$this->session->set_userdata('last_id', $lastId);

					$data['data'] = $this->Messagemodel->getAllLastMessage($_POST['data'], $previousLastId);
					$data['image'] = $_POST['image'];

					if (!empty($data['data']) && isset($data['data'][0]['id'])) {
						$this->load->view('message/sampleMessageShow', $data);
					} else {
						echo "none";
					}

				} else {
					echo "none";
				}
			}
		} else {
			echo "none";
		}
	}

	public function getMessage()
	{
		if (isset($_POST['data']) && isset($_SESSION['uniqueid'])) {
			$data['data'] = $this->Messagemodel->getmessage($_POST['data']);
			$data['image'] = $_POST['image'];
			$this->load->view('message/sampleMessageShow', $data);
		}
	}

	public function updateBio()
	{
		if ($_POST) {
			$this->Messagemodel->updateBio($_POST);
		}
	}
	public function blockUser()
	{
		if (isset($_POST['time']) && isset($_POST['uniq'])) {
			$arr = array(
				'blocked_from' => $_SESSION['uniqueid'],
				'blocked_to' => $_POST['uniq'],
				'time' => $_POST['time']
			);
			$this->Messagemodel->blockUser($arr);
			return 1;
		}
	}
	public function getBlockUserData()
	{
		if (isset($_POST['uniq'])) {
			$res = $this->Messagemodel->getBlockUserData($_POST['uniq'], $_SESSION['uniqueid']);
			print_r(json_encode($res));
		}
	}
	public function unBlockUser()
	{
		if (isset($_POST['uniq'])) {
			$from = $_SESSION['uniqueid'];
			$to = $_POST['uniq'];
			$this->Messagemodel->unBlockUser($from, $to);
		}
	}
}


?>