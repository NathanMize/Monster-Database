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
			$skill = (string)htmlspecialchars($_POST['skill']);
			$return = array();
			$return['skill'] = $skill;
			$return['bonus'] = array();
			$return['name'] = array();
			$stmt = $mysqli->prepare("SELECT C.name, CS.bonus FROM mm_creatures C
									  INNER JOIN mm_c_skills CS ON C.id = CS.creature_id
									  INNER JOIN mm_skills S ON CS.skill_id = S.id
									  WHERE S.name = ?");
															
			$stmt->bind_param("s", $skill);
			$stmt->execute();
			$stmt->bind_result($rowName, $rowBonus);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['name'][]="<a href='MonsterDisplay.html?name=".$rowName."'>".$rowName."</a>";
					$return['bonus'][]= $rowBonus;
				}
			}
			$stmt->close();
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->getCR();
?>