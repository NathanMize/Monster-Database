<?php
	// add a relationship between a creature and an environment
	class ajaxRequest {
		function addCreatureEnviron(){
			require('Mize_connect.php');

			$mysqli = Mize_server();

			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			
			$creature = (string)htmlspecialchars($_POST['ce_creature']);
			$environ = (string)htmlspecialchars($_POST['ce_name']);


			
			$return = array();
			$return['creature'] = $creature;
			$return['environ'] = $environ;
			
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_c_environ (creature_id, envir_id)
										  VALUES
											((SELECT id FROM mm_creatures WHERE name= ?),
											 (SELECT id FROM mm_environments WHERE name= ?))");
				
				$stmt->bind_param("ss", $creature, $environ);
				
				$stmt->execute();
				
				$stmt->close();
			}
			
			return json_encode($return);
		
		
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addCreatureEnviron();
	
?>