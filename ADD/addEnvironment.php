<?php
	// add an environment to the database
	class ajaxRequest {
		function addEnviron(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}			
			$nameInput = (string)htmlspecialchars($_POST['environName']);		
			$return = array();
			$return['name'] = $nameInput;		
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_environments(name) 
												  VALUES (?)");	
				$stmt->bind_param("s", $nameInput);			
				$stmt->execute();				
				$stmt->close();
			}			
			return json_encode($return);		
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addEnviron();
?>