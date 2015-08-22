<?php
	class ajaxRequest {
		function getSense(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$sense = (string)htmlspecialchars($_POST['sense']);
			$return = array();
			$return['sense'] = $sense;
			$return['range'] = array();
			$return['name'] = array();
			$stmt = $mysqli->prepare("SELECT C.name, CS.distance FROM mm_creatures C
									  INNER JOIN mm_c_senses CS ON C.id = CS.creature_id
									  INNER JOIN mm_senses S ON CS.sense_id = S.id
									  WHERE S.name = ?
									  ORDER BY CS.distance, C.name");
			$stmt->bind_param("s", $sense);			 
			$stmt->execute();
			$stmt->bind_result($rowName, $rowRange);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['name'][]="<a href='MonsterDisplay.html?name=".$rowName."'>".$rowName."</a>";
					$return['range'][]= $rowRange;
				}
			}
			$stmt->close();
			
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->getSense();
?>