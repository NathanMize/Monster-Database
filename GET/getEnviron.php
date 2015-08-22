<?php
	class ajaxRequest {
		function getCR(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$environ = (string)htmlspecialchars($_POST['environ']);
			$return = array();
			$return['environ'] = $environ;
			$return['name'] = array();
			$stmt = $mysqli->prepare("SELECT C.name FROM mm_creatures C
									  INNER JOIN mm_c_environ CE ON C.id = CE.creature_id
									  INNER JOIN mm_environments E ON CE.envir_id = E.id
									  WHERE E.name = ?
									  ORDER BY C.name");
			
			$stmt->bind_param("s", $environ);
			 
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
	echo $ajaxRequest->getCR();
?>