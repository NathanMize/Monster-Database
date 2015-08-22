<?php
	// remove a skill from the database
	class ajaxRequest {

		function delSkill(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$name = (string)htmlspecialchars($_POST['del_skill']);
			$return = array();
			$return['name'] = $name;
			$stmt = $mysqli->prepare("DELETE FROM mm_skills WHERE name = ?");
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->close();
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->delSkill();
?>