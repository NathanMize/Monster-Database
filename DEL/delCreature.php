<?php
	// remove a creature from the database
	class ajaxRequest {

		function delCreature(){
			require('Mize_connect.php');

			$mysqli = Mize_server();

			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			
			$creature_name = (string)htmlspecialchars($_POST['del_creature']);
			
			$return = array();
			$return['name'] = $creature_name ;
			
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("DELETE FROM mm_creatures WHERE name = ? LIMIT 1");
				
				$stmt->bind_param("s", $creature_name );
				
				$stmt->execute();
				
				$stmt->close();
			}
			
			return json_encode($return);
		
		
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->delCreature();
	
?>