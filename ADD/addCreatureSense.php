<?php
	// add a relationship between a sense and a creature, with a range
	// update range if relationship already exists. 
	class ajaxRequest {
		function addCreatureSense(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$creature = (string)htmlspecialchars($_POST['csen_creature']);
			$sense = (string)htmlspecialchars($_POST['csen_name']);
			$range = (string)htmlspecialchars($_POST['csen_range']);
			$return = array();
			$return['creature'] = $creature;
			$return['sense'] = $sense;
			$return['range'] = $range;
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_c_senses (creature_id, sense_id, distance)
										  VALUES
											((SELECT id FROM mm_creatures WHERE name= ?),
											 (SELECT id FROM mm_senses WHERE name= ?),
											  ?)
											  ON DUPLICATE KEY UPDATE distance = ?");
				$stmt->bind_param("ssdd", $creature, $sense, $range, $range);
				$stmt->execute();
				$stmt->close();
			}
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addCreatureSense();
?>