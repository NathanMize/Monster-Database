<?php
	// remove a type from the table
	class ajaxRequest {

		function delType(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$name = (string)htmlspecialchars($_POST['del_type']);
			$return = array();
			$return['name'] = $name;
			$stmt = $mysqli->prepare("DELETE FROM mm_types WHERE category = ?");
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->close();
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->delType();
?>