<?php
	class ajaxRequest {
		function getType(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$creatureType = (string)htmlspecialchars($_POST['creatureType']);
			$return = array();
			$return['creatureType'] = $creatureType;
			$return['name'] = array();
			$stmt = $mysqli->prepare("SELECT C.name FROM mm_creatures C
									  INNER JOIN mm_types T ON C.type_id = T.id
									  WHERE T.category = ?");
			
			$stmt->bind_param("s", $creatureType); 
			$stmt->execute();
			$stmt->bind_result($rowName);
			
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['name'][]="<a href='MonsterDisplay.html?name=".$rowName."'>".$rowName."</a>";
				}
			}
			$stmt->close();
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->getType();
?>