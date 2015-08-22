<?php
	// remove all minion relationships for a given creature
	class ajaxRequest {

		function remCMinions(){
			require('Mize_connect.php');

			$mysqli = Mize_server();

			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			
			$creature_name = (string)htmlspecialchars($_POST['rem_minions']);
			
			$return = array();
			$return['name'] = $creature_name ;
			
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("DELETE FROM mm_minions WHERE boss_id = 
										(SELECT id FROM mm_creatures WHERE name= ?)");
				
				$stmt->bind_param("s", $creature_name );
				
				$stmt->execute();
				
				$stmt->close();
			}
			
			return json_encode($return);
		
		
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->remCMinions();
	
?>