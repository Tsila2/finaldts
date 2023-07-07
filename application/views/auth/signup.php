<!DOCTYPE html>
<html>

<head>
	<title>
		<?php echo $title; ?>
	</title>
	<link rel="stylesheet" href="<?php echo site_url('assets/fontawesome-5.15.3/css/all.css'); ?>">
	<link rel="stylesheet" type="text/css" href="../assets/css/signup/style.css">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/bootstrap-4.5.2.min.css'); ?>">
	<script src="<?php echo site_url('assets/js/popper-1.16.0.js'); ?>"></script>
</head>

<body>
	<section id="main">
		<div id="" class="container form_container">
			<div class="row form_row">
				<div class="col-lg-4 bg-white px-3 py-3" id="form_col">
					<form autocomplete="off" id="form_signup" enctype="multipart/form-data">
						<h3 class="text-center">Créer un compte</h3>

						<div class="alert alert-danger d-none" id="alert"></div>
						<div id="ruler" class="">
							<hr />
						</div>
						<div class="field_container">
							<div class="row">
								<div class="form-group col-lg-6">
									<input type="text" name="txt_fname" id="fname" placeholder="Prénom"
										class="form-control">
								</div>
								<div class="form-group col-lg-6">
									<input type="text" name="txt_lname" id="lname" placeholder="Nom de famille"
										class="form-control">
								</div>
							</div>
							<div class="form-group">
								<input type="email" name="txt_email" id="email_addr" placeholder="Email"
									class="form-control">
							</div>
							<div class="row">
								<div class="form-group col-lg-6">
									<input type="password" name="txt_pass" placeholder="
Mot de passe" class="form-control" id="pass1">
								</div>
								<div class="form-group col-lg-6">
									<input type="Password" name="txt_cpass" placeholder="Confirmez le mot de passe"
										class="form-control" id="pass2">
								</div>
							</div>

							<div class="form-group" id="file_container">
								<div class="custom-file">
									<input type="file" name="file_img" class="custom-file-input" id="file_avtar"
										accept="image/*">
									<label class="custom-file-label" for="file_avtar">Télécharger une photo</label>
								</div>
							</div>

							<div class="form-group">
								<button class="btn btn-block" style="background-color:#ea675d; border-radius:0%;"
									id="btn_signup">
									<span>
										S'inscrire</span>
								</button>
							</div>
							<h6 class="text-center">
								Vous avez déjà un compte ? <a href="<?php echo base_url(); ?>"
									style="text-decoration:none;">
									Connexion</a></h6>
						</div>

					</form>
				</div>
			</div>
		</div>

	</section>

	<script src="<?php echo site_url('assets/js/jquery-3.5.1.min.js') ?>"></script>
	<script src="<?php echo site_url('assets/js/bootstrap-4.5.2.min.js') ?>"></script>
	<script src="<?php echo site_url('assets/js/gsap-3.6.1.min.js') ?>"></script>
	<script src="../assets/js/signup/main.js"></script>
	<script>
		$(".custom-file-input").on("change", function () {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
	</script>
</body>

</html>