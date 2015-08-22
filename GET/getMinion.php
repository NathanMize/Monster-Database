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
			$boss = (string)htmlspecialchars($_POST['boss']);
			$return = array();
			$return['boss'] = $boss;
			$return['name'] = array();
			$stmt = $mysqli->prepare("SELECT CM.name FROM mm_creatures CB
									  INNER JOIN mm_minions M ON CB.id = M.boss_id
									  INNER JOIN mm_creatures CM ON M.minion_id = CM.id
									  WHERE CB.name = ?");
			
			$stmt->bind_param("s", $boss);
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