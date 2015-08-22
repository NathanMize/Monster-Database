<?php
	// add a skill and its associated attribute to the database
	class ajaxRequest {

		function addSkill(){
			require('Mize_connect.php');

			$mysqli = Mize_server();

			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$nameInput = (string)htmlspecialchars($_POST['skillName']);
			$attrInput = (string)htmlspecialchars($_POST['skillAttr']);
			$return = array();
			$return['attr'] = $attrInput;
			$return['name'] = $nameInput;
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_skills(name, attribute) 
												  VALUES (?, (SELECT id FROM mm_attributes WHERE name = ?))
												  ON DUPLICATE KEY UPDATE attribute = (SELECT id FROM mm_attributes WHERE name = ?)");
				$stmt->bind_param("sss", $nameInput, $attrInput, $attrInput);
				$stmt->execute();
				$stmt->close();
			}	
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addSkill();
?>