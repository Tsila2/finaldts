$(document).ready(function () {
	var oldDate, newDate, days, hours, min, sec, unique_id, bg_image, inter, inter3, inter2,
		chatBox = document.getElementById('chat_message_area'),
		main = document.getElementById('main'),
		owenerProfileBio = document.getElementById('owner_profile_bio'),
		dob, phone, addr, bio;

	const MAIN_PLAY = gsap.timeline({ paused: true });
	MAIN_PLAY.from("#main", { duration: 0.5, opacity: 0 });

	//Main funtion which will run at the time of page load

	document.addEventListener('DOMContentLoaded', function () {
		const audioElements = document.getElementsByTagName('audio');
		for (let i = 0; i < audioElements.length; i++) {
			const audioElement = audioElements[i];
			audioElement.addEventListener('play', function () {
				stopAllAudioAndTimers();
			});
		}
	});

	function stopAllAudioAndTimers() {
		// Step 1: Find all active audio elements
		const audioElements = document.getElementsByTagName('audio');

		// Step 2: Pause audio playback for each active audio element
		for (let i = 0; i < audioElements.length; i++) {
			const audioElement = audioElements[i];
			if (!audioElement.paused) {
				audioElement.pause();
			}
		}

		// Step 3: Clear all setInterval timers
		const allIntervals = window.setInterval(() => { }, 9999);
		for (let i = 1; i <= allIntervals; i++) {
			clearInterval(i);
		}
	}

	//UserSidebarIn
	function barIn() {
		$('#details_of_user').css('width', '20%');
		$('#chatbox').css('width', '55%');
		$('#details_of_user').children().show();
	}
	//UserSidebarOut
	function barOut() {
		$('#details_of_user').children().hide();
		$('#details_of_user').css('width', '0');
		$('#chatbox').css('width', '75%');
	}

	//getting all user list except me
	function getUserList() {
		return new Promise(function (resolve, reject) { //Creating new Promise Chain
			$.ajax({
				url: 'Message/allUser',
				type: 'get',
				async: false,
				success: function (data) {
					if (data != "") {
						resolve(data);
					}
				}
			})
		}).then(function (data) { //This function setting the user list
			document.getElementById('user_list').innerHTML = data; //setting data to the user list
			$.get('Message/ownerDetails', function (data) {
				jsonData = JSON.parse(data);
				dob = jsonData[0]['dob'];
				phone = jsonData[0]['phone'];
				addr = jsonData[0]['address'];
				bio = jsonData[0]['bio'];
				if (dob.length != 0 && addr.length != 0 && phone.length != 0 && bio.length != 0) {
					owenerProfileBio.classList.remove('text-warning');
					owenerProfileBio.innerHTML = (bio.length > 28) ? bio.slice(0, 28) + "..." : bio;
				} else {
					owenerProfileBio.innerHTML = "Profil non complété";
					owenerProfileBio.classList.add('text-warning');
				}
			})
			$('.innerBox').click(function () {
				$('#search').val('');
				barIn();
				$('.chatting_section').css('display', '');

				unique_id = $(this).find('#user_avtar').children('#hidden_id').val();
				bg_image = $(this).find('#user_avtar').css('background-image').split('"')[1];

				getBlockUserData();
				setInterval(getBlockUserData, 100);

				getUserDetails(unique_id);
				sendUserUniqIDForMsg(unique_id, bg_image);

				addLastMessage(unique_id, bg_image);

				inter = setInterval(function () {
					addLastMessage(unique_id, bg_image);
				}, 100);
			})
		})
	}
	function getUserDetails(uniq_id) {
		$.post('Message/getIndividual', { data: uniq_id }, function (data) {
			var res_data = JSON.parse(data);
			setUserDetails(res_data);
		})
	}
	function setUserDetails(data) {
		var user_name = `${data[0]['user_fname']} ${data[0]['user_lname']}`;
		var status = data[0]['user_status'];
		var avtar = `upload/${data[0]['user_avtar']}`;
		// var avtar = data[0]['user_avtar'];
		var last_seen = data[0]['last_logout'];
		offlineOnlineIndicator(status, last_seen);
		$('#name_last_seen h6').html(user_name);
		$('#chat_profile_image').css('background-image', `url(${avtar})`);
		$('#new_message_avtar').css('background-image', `url(${avtar})`);
		$('#mail_link').attr('href', `mailto:${data[0]['user_email']}`);

		$('#user_details_container_avtar').css('background-image', `url(${avtar})`);
		$('#input_user').val(`${avtar}`);

		$('#details_of_user h5').html(user_name);
		(data[0]['bio'].length > 1) ? $('#details_of_bio').html(data[0]['bio']) : $('#details_of_bio').html("--Non spécifié--");

		$('#details_of_created').html(`Created at : ${data[0]['created_at']}`);
		$('#details_of_email').html(`<span><i class="fas fa-envelope text-dark pr-2"></i></span>${data[0]['user_email']}`);

		(data[0]['dob'].length > 1) ?
			$('#details_of_birthday').html(`<span><i class="fas fa-birthday-cake text-dark pr-2"></i></span>${data[0]['dob']}`) :
			$('#details_of_birthday').html(`<span><i class="fas fa-birthday-cake text-dark pr-2"></i></span>--Non spécifié--`);

		(data[0]['phone'].length > 1) ?
			$('#details_of_mobile').html(`<span><i class="fas fa-mobile-alt text-dark pr-2"></i></span>${data[0]['phone']}`) :
			$('#details_of_mobile').html(`<span><i class="fas fa-mobile-alt text-dark pr-2"></i></span>--Non spécifié--`);

		(data[0]['address'].length > 1) ?
			$('#details_of_location').html(`<span><i class="fas fa-map-marker-alt text-dark pr-2"></i></span>${data[0]['address']}`) :
			$('#details_of_location').html(`<span><i class="fas fa-map-marker-alt text-dark pr-2"></i></span>--Non spécifié--`);


	}

	function offlineOnlineIndicator(data, last_seen) {
		if (data == 'active') {
			$('#name_last_seen p').html("Actif");
			$("#chat_profile_image #online").show();
		} else {
			$("#chat_profile_image #online").hide();
			getLastSeen(last_seen);
		}
	}

	function getLastSeen(data) {
		var { hours, min, sec } = calculateTime(data);
		var formattedDate = formatDate(data);

		if (days > 0) {
			$('#name_last_seen p').html(`Actif Il y a ${formattedDate}`);
		} else {
			(hours > 0) ? $('#name_last_seen p').html(`Actif Il y a ${hours} heures ${min} minutes`) :
				(min > 0) ? $('#name_last_seen p').html(`Actif Il y a ${min} minutes`) :
					$('#name_last_seen p').html(`Actif Il y a un instant`);
		}
	}

	function calculateTime(data) {
		oldDate = new Date(data.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3")).getTime();
		newDate = new Date().getTime();
		differ = newDate - oldDate;
		days = Math.floor(differ / (1000 * 60 * 60 * 24));
		hours = Math.floor((differ % (1000 * 60 * 60 * 24)) / (60 * 60 * 1000));
		min = Math.floor((differ % (1000 * 60 * 60)) / (60 * 1000));
		sec = Math.floor((differ % (1000 * 60)) / 1000);
		var obj = {
			hours: hours,
			min: min,
			sec: sec
		};
		return obj;
	}

	function formatDate(data) {
		var dateParts = data.split(" ")[0].split("/");
		var timePart = data.split(" ")[1];

		var year = dateParts[2];
		var month = ('0' + dateParts[1]).slice(-2);
		var day = ('0' + dateParts[0]).slice(-2);

		var formattedDate = `${year}-${month}-${day} ${timePart}`;

		return formattedDate;
	}

	//sending unique_id which is clicked for messages
	function sendUserUniqIDForMsg(uniq_id, bg_image) {
		$.post('getmessage', { data: uniq_id, image: bg_image }, function (data) {
			setMessageToChatArea(data, bg_image);//setting messages to the chatting section
		});
	}

	function addLastMessage(uniq_id, bg_image) {
		$.post('getlastmessage', { data: uniq_id, image: bg_image }, function (data) {
			if (data !== 'none') {
				$('#chat_message_area').append(data);
				clearInterval(inter2);
				inter2 = setInterval(getUserList, 1000);
			}
		});
	}

	function setMessageToChatArea(data, bg_image) {
		scrollToBottom();
		var res_data;
		if (data.length > 5) {
			$('#chat_message_area').html(data);
		} else {
			var profileName = $('#name_last_seen h6').html();
			$.ajax({
				url: 'Message/setNoMessage',
				type: 'post',
				async: false,
				data: { image: bg_image, name: profileName },
				success: function (data) {
					res_data = data;
				}
			})
			$('#chat_message_area').html(res_data);
		}
	}
	$('#chat_message_area').mouseenter(function () {
		chatBox.classList.add('active');
	});
	$('#chat_message_area').mouseleave(function () {
		chatBox.classList.remove('active');
	});
	function scrollToBottom() {
		inter4 = setInterval(() => {
			if (!chatBox.classList.contains('active')) {
				chatBox.scrollTop = chatBox.scrollHeight;
			}
		});
	}
	$('#search').keyup(function (e) {
		var user = document.querySelectorAll('.user');
		var name = document.querySelectorAll('#user_list h6');
		var val = this.value.toLowerCase();
		if (val.length > 0) {
			clearInterval(inter2);
			for (let i = 0; i < user.length; i++) {
				nameVal = name[i].innerHTML;
				if (nameVal.toLowerCase().indexOf(val) > -1) {
					user[i].style.display = '';
				} else {
					user[i].style.display = 'none';
				}
			}
			console.log('Called');
			clearInterval(inter2);
		} else {
			clearInterval(inter2);
			inter2 = setInterval(getUserList, 1000);
		}
	});

	function getCharLength() {
		const MAX_LENGTH = 200;
		var len = document.getElementById('messageText').value.length;
		if (len <= MAX_LENGTH) {
			$('#count_text').html(`${len}/200`);
		}
	}
	setInterval(getCharLength, 10);
	$('#logout').click(function (e) {
		e.preventDefault();
		var date = new Date();
		date = new Date(date);
		date = date.toLocaleString();
		console.log(date);
		$.ajax({
			url: 'logout',
			type: 'post',
			data: "date=" + date,
			success: function (res) {
				location.href = res;
			}
		})
	});

	// Send message when clicking the button "Enter"
	$('#messageText').keypress(function (event) {
		if (event.which === 13 && event.target === this) {
			event.preventDefault(); // Prevent the default Enter key behavior
			$('#send_message').click(); // Trigger the click event of the send_message button
		}
	});

	//send message after button click
	$('#send_message').click(function (e) {
		var message = $('#messageText').val();
		if (message && $.trim(message) !== "") {
			var d = new Date(),
				messageHour = d.getHours(),
				messageMinute = d.getMinutes(),
				messageSec = d.getSeconds(),
				messageYear = d.getFullYear(),
				messageDate = d.getDate(),
				messageMonth = d.getMonth() + 1,
				actualDateTime = `${messageYear}-${messageMonth}-${messageDate} ${messageHour}:${messageMinute}:${messageSec}`;
			var data = {
				message: message,
				datetime: actualDateTime,
				uniq: unique_id
			};
			var jsonData = JSON.stringify(data);
			$.post('sent', { data: jsonData }, function (data) {
				$('#messageText').val('');
			});
			clearInterval(inter2);
			inter2 = setInterval(getUserList, 1000);
		}
	})
	// my details edit icon
	$('#edit_icon').click(function () {
		$('#main').addClass('blur');
		$('#update_container').show();
		$('#update_bio').focus();
		$('#dob').val(dob);
		$('#phone_num').val(phone);
		$('#update_bio').val(bio);
		$('#address').val(addr);
	})
	$('#update_container i').click(function () {
		$('#main').removeClass('blur');
		$('#update_container').hide();
	})
	//update form container submit event
	$('#form_details').on('submit', function (e) {
		e.preventDefault();
		var newDate = $('#dob').val();
		var newPhone = $('#phone_num').val();
		var newAddress = $('#address').val();
		var newBio = $('#update_bio').val();
		$.post('Message/updateBio', { dob: newDate, phone: newPhone, address: newAddress, bio: newBio }, function (data) {
			$('#main').removeClass('blur');
			$('#update_container').hide();
		})
	})
	$('#details_btn').click(function () {
		var bar = document.getElementById('details_of_user').style;
		if (bar.width == "20%") {
			barOut();
		} else {
			barIn();
		}
	})
	$('#btn_block').click(function () {
		var d = new Date(),
			messageHour = d.getHours(),
			messageMinute = d.getMinutes(),
			messageSec = d.getSeconds(),
			messageYear = d.getFullYear(),
			messageDate = d.getDate(),
			messageMonth = d.getMonth() + 1,
			actualDateTime = `${messageYear}-${messageMonth}-${messageDate} ${messageHour}:${messageMinute}:${messageSec}`;
		if (this.innerHTML == "Bloquer l\'utilisateur") {
			$.post('Message/blockUser', { time: actualDateTime, uniq: unique_id })
		} else {
			$.post('Message/unBlockUser', { uniq: unique_id })
		}
	})

	// Add click event listener to the image attachment button
	$('#attachmentButtonContainer').on('click', '#imageUploadButton', function () {
		// Trigger the click event on the hidden image upload input field
		$('#imageUpload').click();
	});

	// Add click event listener to the file attachment button
	$('#attachmentButtonContainer').on('click', '#fileUploadButton', function () {
		// Trigger the click event on the hidden file upload input field
		$('#fileUpload').click();
	});

	let mediaRecorder;
	let audioChunks = [];

	navigator.mediaDevices.getUserMedia({ audio: true })
		.then(function (stream) {
			mediaRecorder = new MediaRecorder(stream);

			mediaRecorder.ondataavailable = function (event) {
				if (event.data.size > 0) {
					audioChunks.push(event.data);
				}
			};

			mediaRecorder.onstart = function () {
				document.getElementById('recordingIndicator').style.display = 'block';
				document.getElementById('microphoneIcon').textContent = 'stop';
			};

			mediaRecorder.onstop = function () {
				document.getElementById('recordingIndicator').style.display = 'none';

				const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });

				// // Create an audio element to play the recorded audio
				// const audioPlayer = document.createElement('audio');
				// audioPlayer.controls = true;
				// audioPlayer.src = URL.createObjectURL(audioBlob);

				// // Append the audio element to the container in the HTML
				// const audioContainer = document.getElementById('audioContainer');
				// audioContainer.innerHTML = ''; // Clear previous audio elements (if any)
				// audioContainer.appendChild(audioPlayer);

				// Now you can do whatever you want with the recorded audio data
				// For example, you can upload it to the server or perform other actions.

				document.getElementById('microphoneIcon').textContent = 'mic';
				saveAudio(audioBlob); // This is where you send the audio data to the server if needed.
			};
		})
		.catch(function (error) {
			console.error('Error accessing the microphone:', error);
		});

	// Function to check microphone permission
	function checkMicrophonePermission() {
		return navigator.permissions.query({ name: 'microphone' })
			.then((permissionStatus) => {
				if (permissionStatus.state === 'granted') {
					return true; // Microphone access is already granted
				} else if (permissionStatus.state === 'prompt') {
					// Microphone permission has not been granted yet, request it
					return navigator.mediaDevices.getUserMedia({ audio: true })
						.then(() => {
							return true; // Microphone access granted after user prompt
						})
						.catch(() => {
							return false; // Microphone access denied by the user
						});
				} else {
					return false; // Microphone access denied by the user
				}
			})
			.catch(() => {
				return false; // Error occurred while checking microphone permission
			});
	}

	// Function to start recording
	function startRecording() {
		audioChunks = [];
		mediaRecorder.start();
		console.log('Recording started...');
	}

	// Function to stop recording
	function stopRecording() {
		mediaRecorder.stop();
		console.log('Recording stopped.');
	}

	$('#microphoneRecord').click(async function () {
		if (!mediaRecorder) {
			// MediaRecorder is not available (possibly due to unsupported browser)
			console.error('MediaRecorder is not available in this browser.');
			return;
		}

		const hasPermission = await checkMicrophonePermission();
		if (hasPermission) {
			if (mediaRecorder.state === 'inactive') {
				startRecording();
			} else if (mediaRecorder.state === 'recording') {
				stopRecording();
			}
		} else {
			console.warn('Microphone access is denied. Please grant permission to use the microphone.');
		}
	});


	function saveAudio(blob) {

		// Send the recorded audio to the server using AJAX
		var d = new Date(),
			messageHour = d.getHours(),
			messageMinute = d.getMinutes(),
			messageSec = d.getSeconds(),
			messageYear = d.getFullYear(),
			messageDate = d.getDate(),
			messageMonth = d.getMonth() + 1,
			actualDateTime = `${messageYear}-${messageMonth}-${messageDate} ${messageHour}:${messageMinute}:${messageSec}`;

		var data = new FormData();
		data.append('file', blob, 'audio.webm');
		data.append('datetime', actualDateTime);
		data.append('uniq', unique_id);

		$.ajax({
			url: 'sent/audio',
			type: 'POST',
			data: data,
			contentType: false,
			processData: false,
			success: function (response) {
				// Handle the upload success
				console.log(response);
				// console.log('File uploaded successfully');
				// $('#fileUpload').val('');
			},
			error: function (xhr, status, error) {
				// Handle the upload error
				console.error('Échec du téléchargement du fichier:', error);
			}
		});
	}

	// Image upload handling
	$('#imageUpload').on('change', function (e) {
		var file = e.target.files[0];
		if (file) {
			// Process the image file
			var reader = new FileReader();
			reader.onload = function (e) {
				var imageData = e.target.result;

				var d = new Date(),
					messageHour = d.getHours(),
					messageMinute = d.getMinutes(),
					messageSec = d.getSeconds(),
					messageYear = d.getFullYear(),
					messageDate = d.getDate(),
					messageMonth = d.getMonth() + 1,
					actualDateTime = `${messageYear}-${messageMonth}-${messageDate} ${messageHour}:${messageMinute}:${messageSec}`;

				var data = new FormData();
				data.append('file', file);
				data.append('datetime', actualDateTime);
				data.append('uniq', unique_id);

				$.ajax({
					url: 'sent/image',
					type: 'POST',
					data: data,
					contentType: false,
					processData: false,
					success: function (response) {
						// Handle the upload success
						if (response === "Not allowed.") {
							alert("Format non pris en charge");
						}
						$('#imageUpload').val('');
					},
					error: function (xhr, status, error) {
						// Handle the upload error
						console.error('Échec du téléchargement de l\'image :', error);
					}
				});
			};
			reader.readAsDataURL(file);
		}
	});



	$('#fileUpload').on('change', function (e) {
		var file = e.target.files[0];
		if (file) {
			// Process the file
			// Example: Perform any required operations with the file data

			var d = new Date(),
				messageHour = d.getHours(),
				messageMinute = d.getMinutes(),
				messageSec = d.getSeconds(),
				messageYear = d.getFullYear(),
				messageDate = d.getDate(),
				messageMonth = d.getMonth() + 1,
				actualDateTime = `${messageYear}-${messageMonth}-${messageDate} ${messageHour}:${messageMinute}:${messageSec}`;

			var data = new FormData();
			data.append('file', file);
			data.append('datetime', actualDateTime);
			data.append('uniq', unique_id);

			$.ajax({
				url: 'sent/file',
				type: 'POST',
				data: data,
				contentType: false,
				processData: false,
				success: function (response) {
					// Handle the upload success
					console.log(response);
					// console.log('File uploaded successfully');
					// $('#fileUpload').val('');
				},
				error: function (xhr, status, error) {
					// Handle the upload error
					console.error('Échec du téléchargement du fichier:', error);
				}
			});
		}
	});




	//working on block & unblock program
	function getBlockUserData() {
		$.post('Message/getBlockUserData', { uniq: unique_id }, function (data) {
			var jsonData = JSON.parse(data);
			if (jsonData.length == 1) {
				for (var i = 0; i < jsonData.length; i++) {
					if (jsonData[i]['blocked_from'] == unique_id) {
						$('#messageText').attr('disabled', '');
						$('#messageText').attr('placeholder', 'Cet utilisateur ne reçoit pas de messages en ce moment.');
						$('#messageText').css('cursor', 'no-drop');
						$('#btn_block').html('Bloquer l\'utilisateur');
						$('#send_message').attr('disabled', '');
						$('#send_message').css('cursor', 'no-drop');
					} else {
						$('#messageText').attr('disabled', '');
						$('#messageText').attr('placeholder', 'Vous avez bloqué cet utilisateur');
						$('#btn_block').html('Débloquer l\'utilisateur');
						$('#messageText').css('cursor', 'no-drop');

						$('#send_message').attr('disabled', '');
						$('#send_message').css('cursor', 'no-drop');
					}
				}
			} else if (jsonData.length == 2) {
				$('#messageText').attr('disabled', '');
				$('#messageText').attr('placeholder', 'Vous vous êtes bloqués mutuellement');
				$('#btn_block').html('Débloquer l\'utilisateur');
				$('#messageText').css('cursor', 'no-drop');
				$('#send_message').attr('disabled', '');
				$('#send_message').css('cursor', 'no-drop');
			} else {
				$('#messageText').removeAttr('disabled');
				$('#messageText').attr('placeholder', 'Commencez à taper...');
				$('#btn_block').html('Bloquer l\'utilisateur');
				$('#messageText').css('cursor', '');
				$('#send_message').removeAttr('disabled');
				$('#send_message').css('cursor', '');
			}
		})
	}

	// Pace.on('done', function () {
		MAIN_PLAY.play();
	// })
	getUserList(); //Calling the root function without interval
	inter2 = setInterval(getUserList, 1000);
})

