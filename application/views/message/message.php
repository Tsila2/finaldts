<?php

if (isset($_SESSION)) {
	$image = $_SESSION['image'];
	$name = $data[0]['user_fname'] . " " . $data[0]['user_lname'];
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo site_url('assets/fontawesome-5.15.3/css/all.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-4.5.2.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/css/message/messagestyle.css'); ?>">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- <script src="<?php echo site_url('assets/js/pace.min.js'); ?>"></script> -->
	<!-- <link rel="stylesheet" href="<?php echo site_url('assets/css/message/loading-bar.css'); ?>"> -->
	<script src="<?php echo site_url('assets/js/popper-1.16.0.js'); ?>"></script>

	<title>
		<?php echo $title; ?>
	</title>
</head>

<body>
	<section id="main" class="bg-dark">
		<div id="chat_user_list">
			<div id="owner_profile_details">
				<div id="owner_avtar"
					style="background-image: url('<?php echo site_url('upload/') . $image; ?>'); background-size: 100% 100%">
					<div>
						<div id="online"></div>
					</div>
				</div>
				<div id="owner_profile_text" class="">
					<h6 id="owner_profile_name" class="m-0 p-0">
						<?php echo $name; ?>
					</h6>
					<div id="bio">
						<p id="owner_profile_bio" class="m-0 p-0"></p>
						<i class="fas fa-edit" id="edit_icon"></i>
					</div>
					<a class="text-decoration-none" href="" id="logout" style="color:#e86663;"><i
							class="fa fa-power-off"></i> Déconnexion</a>
				</div>
			</div>
			<div id="search_box_container" class="py-3">
				<input type="text" name="txt_search" class="form-control" autocomplete="off"
					placeholder="Rechercher un utilisateur" id="search">
			</div>
			<hr />
			<div id="user_list" class="py-3">
			</div>
		</div>
		<div id="chatbox">
			<div id="data_container" class="">
				<div id="bg_image"></div>
				<h2 class="mt-0">Salut ! Bienvenue sur</h2>
				<h2>E-Resaka</h2>
				<p class="text-center my-2">Connectez-vous à votre appareil via Internet. N'oubliez pas que vous <br>
					devez disposer d'une connexion Internet stable pour une<br> meilleure expérience.</p>
			</div>
			<div class="chatting_section" id="chat_area" style="display: none">
				<div id="header" class="py-2">
					<div id="name_details" class="pt-2">
						<div id="chat_profile_image" class="mx-2" style="background-size: 100% 100%">
							<div id="online"></div>
						</div>
						<div id="name_last_seen">
							<h6 class="m-0 pt-2"></h6>
							<p class="m-0 py-1"></p>
						</div>
					</div>
					<div id="icons" class="px-4 pt-2">
						<div id="send_mail">
							<a href="" id="mail_link"><i class="fas fa-envelope text-dark"></i></a>
						</div>
						<div id="details_btn" class="ml-3">
							<i class="fas fa-info-circle text-dark"></i>
						</div>
					</div>
				</div>
				<div id="recordingIndicator" style="display: none; color:red" class="mt-2">Recording...</div>

				<div id="chat_message_area">

				</div>

				<div id="messageBar" class="py-4 px-4">
					<div id="attachmentButtonContainer" style="display: flex; justify-content: space-between">
						<label for="imageUpload" class="attachmentButton"
							style="display: inline-block; cursor: pointer;">
							<span class="material-icons" style="vertical-align: middle;">image</span>
							<input type="file" id="imageUpload" name="file1" accept="image/*" style="display: none;">
						</label>
						<label for="fileUpload" class="attachmentButton"
							style="display: inline-block; cursor: pointer;">
							<span class="material-icons" style="vertical-align: middle;">attach_file</span>
							<input type="file" id="fileUpload" name="file2" style="display: none;">
						</label>
						<label for="microphoneRecord" class="attachmentButton"
							style="display: inline-block; cursor: pointer;">
							<span id="microphoneIcon" class="material-icons" style="vertical-align: middle;">mic</span>
							<input type="button" id="microphoneRecord" value="Record" style="display: none;">
						</label>
					</div>

					<div id="textBox_attachment_emoji_container" style="display: flex">
						<div id="text_box_message">
							<textarea maxlength="200" name="txt_message" id="messageText" class="form-control no-resize"
								placeholder="Tapez votre message"></textarea>
						</div>
						<div id="text_counter">
							<p id="count_text" class="m-0 p-0"></p>
						</div>
					</div>

					<div id="sendButtonContainer" style="display: inline-block;">
						<button class="btn" id="send_message">
							<span class="material-icons">send</span>
						</button>
					</div>
				</div>


			</div>
		</div>
		<div id="details_of_user">
			<input type="hidden" id="input_user">
			<div id="user_details_container_avtar" style="background-size: 100% 100%"></div>
			<h5 class="text-justify" id="details_of_name"></h5>
			<p class="text-justify" id="details_of_bio"></p>
			<div id="user_details_container_details">
				<p class="text-justify" id="details_of_created"></p>
				<p class="text-justify" id="details_of_birthday"></p>
				<p class="text-justify" id="details_of_mobile"><span></p>
				<p class="text-justify" id="details_of_email"><span></p>
				<p class="text-justify" id="details_of_location"><span></p>
			</div>
			<button class="btn btn-danger" id="btn_block">Bloquer l'utilisateur</button>
		</div>
	</section>
	<div id="update_container">
		<div style="background-color:#F5F6FA;" class="p-3 d-flex justify-content-between align-items-center">
			<h5 id="update_container_title" class="m-0 p-0">Update Profile</h5>
			<i class="fas fa-times"></i>
		</div>
		<form class="" id="form_details" autocomplete="off">
			<div class="form-group">
				<label>Date Of Birth</label>
				<input type="text" name="txt_dob" id="dob" class="form-control" placeholder="dd-mm-yyyy">
			</div>
			<div class="form-group">
				<label>Contact Number</label>
				<input type="text" maxlength="10" name="txt_phone"
					placeholder="Écrivez votre numéro de téléphone mobile" id="phone_num" class="form-control">
			</div>
			<div class="form-group">
				<label>Address</label>
				<input type="text" name="txt_addr" id="address" placeholder="Écrivez votre adresse"
					class="form-control">
			</div>
			<div class="form-group">
				<label>Bio</label>
				<textarea name="bio" class="" id="update_bio" placeholder="Écrivez votre biographie ici..."></textarea>
			</div>
			<button class="btn btn-block" id="update_btn" style="border-radius:0px;">
				<span>Enregistrer les modifications</span>
			</button>
		</form>
	</div>
	<script src="<?php echo site_url('assets/js/jquery-3.5.1.min.js') ?>"></script>
	<script src="<?php echo site_url('assets/js/bootstrap-4.5.2.min.js') ?>"></script>
	<script src="<?php echo site_url('assets/js/gsap-3.6.1.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('assets/js/message/main.js') ?>"></script>

	<script>
		// Function to handle tab closing event
		window.addEventListener('beforeunload', function (event) {
			var date = new Date().toLocaleString();
			var data = new FormData();
			data.append('date', date);

			navigator.sendBeacon('close', data);
		});
	</script>

</body>

</html>