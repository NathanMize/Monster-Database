<?php
	// add a relationship between a creature and a skill with a bonus value,
	// update bonus value if relationship already exists
	class ajaxRequest {
		function addCreatureSkill(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}			
			$creature = (string)htmlspecialchars($_POST['cs_creature']);
			$skill = (string)htmlspecialchars($_POST['cs_skill']);
			$bonus = (string)htmlspecialchars($_POST['cs_bonus']);			
			$return = array();
			$return['creature'] = $creature;
			$return['skill'] = $skill;
			$return['bonus'] = $bonus;
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_c_skills (creature_id, skill_id, bonus)
										  VALUES
											((SELECT id FROM mm_creatures WHERE name= ?),
											 (SELECT id FROM mm_skills WHERE name= ?),
											  ?)
											  ON DUPLICATE KEY UPDATE bonus = ?");
				
				$stmt->bind_param("ssdd", $creature, $skill, $bonus, $bonus);				
				$stmt->execute();
				$stmt->close();
			}
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addCreatureSkill();
?>