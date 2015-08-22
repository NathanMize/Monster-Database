<?php
	// add a minion relationship between two creatures
	class ajaxRequest {
		function addMinion(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}		
			$boss = (string)htmlspecialchars($_POST['boss']);
			$minion = (string)htmlspecialchars($_POST['minion']);		
			$return = array();
			$return['boss'] = $boss;
			$return['minion'] = $minion;	
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_minions(boss_id, minion_id) 
											VALUES ((SELECT id FROM mm_creatures WHERE name = ?), 
													(SELECT id FROM mm_creatures WHERE name = ?))");
				
				$stmt->bind_param("ss", $boss, $minion);	
				$stmt->execute();	
				$stmt->close();
			}
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addMinion();
	
?>