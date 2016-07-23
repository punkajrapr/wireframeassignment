<?php
	ob_start();
	session_start();
	$allowImageType = array('image/jpeg','image/gif','image/png');
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$timeToCheck = $_POST['captcha'];
		if($timeToCheck == $_SESSION['checktime']) {
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["my-file-selector"]["name"]);
			
			 if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
			
		
		
		
		}	
	}
	