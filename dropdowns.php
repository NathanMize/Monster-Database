<?php
// Select the contents of each non relational table to fill drop
// downs on MonsterDisplay.html and MonsterEdit.html
	class ajaxRequest {
		
		
		function requestLists(){

		
			$return = array();
			$return['attr'] = array();
			$return['creature'] = array();
			$return['type'] = array();
			$return['skill'] = array();
			$return['sense'] = array();
			$return['environ'] = array();
			
			require('Mize_connect.php');
			$mysqli = Mize_server();
			
			// get attribute list
			$stmt = $mysqli->prepare("SELECT name FROM mm_attributes ORDER BY id");
			$stmt->execute();
			$stmt->bind_result($name);
			while($stmt->fetch()){
				$return['attr'][] = $name;
			}
			$stmt->close();
			
			// get creature list
			$stmt = $mysqli->prepare("SELECT name FROM mm_creatures");
			$stmt->execute();
			$stmt->bind_result($name);
			while($stmt->fetch()){
				$return['creature'][] = $name;
			}
			$stmt->close();
			
			// get type list
			$stmt = $mysqli->prepare("SELECT category FROM mm_types");
			$stmt->execute();
			$stmt->bind_result($name);
			while($stmt->fetch()){
				$return['type'][] = $name;
			}
			$stmt->close();
			
			// get skill list
			$stmt = $mysqli->prepare("SELECT name FROM mm_skills");
			$stmt->execute();
			$stmt->bind_result($name);
			while($stmt->fetch()){
				$return['skill'][] = $name;
			}
			$stmt->close();
			
			// get sense list
			$stmt = $mysqli->prepare("SELECT name FROM mm_senses");
			$stmt->execute();
			$stmt->bind_result($name);
			while($stmt->fetch()){
				$return['sense'][] = $name;
			}
			$stmt->close();
			
			// get environ list
			$stmt = $mysqli->prepare("SELECT name FROM mm_environments");
			$stmt->execute();
			$stmt->bind_result($name);
			while($stmt->fetch()){
				$return['environ'][] = $name;
			}
			$stmt->close();
			
			return json_encode($return);
		}
	}
	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->requestLists();

?>