<?php
	// get all the relationships for a given creature to display to user on MonsterDisplay.php
	class ajaxRequest {	
		function requestLists(){
			$return = array();
			$name = (string)htmlspecialchars($_POST['name']);
			$return['name'] = $name;
			$return['attr'] = array();
			$return['attrValue'] = array();
			$return['skills'] = array();
			$return['skillsValue'] = array();
			$return['sense'] = array();
			$return['senseValue'] = array();
			$return['environ'] = array();
			$return['minions'] = array();
			$return['bosses'] = array();
			require('Mize_connect.php');
			$mysqli = Mize_server();
			
			$stmt = $mysqli->prepare("SELECT C.hp, C.ac, C.cr, T.category FROM mm_creatures C
										  INNER JOIN mm_types T ON C.type_id = T.id
										  WHERE C.name = ?");	
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($hp,$ac,$cr,$type );
			while($stmt->fetch()){
				$return['hp'] = $hp;
				$return['ac'] = $ac;
				$return['cr'] = $cr;
				$return['type'] = $type;

			}
			$stmt->close();
			
			$stmt = $mysqli->prepare("SELECT A.name, CA.stat FROM mm_creatures C
										  INNER JOIN mm_c_attr CA ON C.id = CA.creature_id
										  INNER JOIN mm_attributes A ON CA.attribute = A.id
										  WHERE C.name = ?
										  ORDER BY A.id");
				
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($attrName, $stat);
			while($stmt->fetch()){
				$return['attr'][] = $attrName;
				$return['attrValue'][] = $stat;

			}
			$stmt->close();
			
			$stmt = $mysqli->prepare("SELECT S.name, CS.bonus FROM mm_creatures C
										  INNER JOIN mm_c_skills CS ON C.id = CS.creature_id
										  INNER JOIN mm_skills S ON CS.skill_id = S.id
										  WHERE C.name = ?");
																
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($rowName, $rowBonus);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['skills'][]=$rowName;
					$return['skillsValue'][]= $rowBonus;
				}
			}
			$stmt->close();
			
			$stmt = $mysqli->prepare("SELECT S.name, CS.distance FROM mm_creatures C
										  INNER JOIN mm_c_senses CS ON C.id = CS.creature_id
										  INNER JOIN mm_senses S ON CS.sense_id = S.id
										  WHERE C.name = ?");
				
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($rowName, $rowRange);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['sense'][]=$rowName;
					$return['senseValue'][]= $rowRange;
				}
			}
			$stmt->close();
			
			$stmt = $mysqli->prepare("SELECT E.name FROM mm_creatures C
										  INNER JOIN mm_c_environ CE ON C.id = CE.creature_id
										  INNER JOIN mm_environments E ON CE.envir_id = E.id
										  WHERE C.name = ?");
				
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($rowName);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['environ'][]=$rowName;
				}
			}
			$stmt->close();
			
			$stmt = $mysqli->prepare("SELECT CM.name FROM mm_creatures CB
										  INNER JOIN mm_minions M ON CB.id = M.boss_id
										  INNER JOIN mm_creatures CM ON M.minion_id = CM.id
										  WHERE CB.name = ?");
				
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($rowName);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['minion'][]="<a href='MonsterDisplay.html?name=".$rowName."'>".$rowName."</a>";
				}
			}
			$stmt->close();
			
			$stmt = $mysqli->prepare("	SELECT CB.name
										FROM mm_creatures CB
										INNER JOIN mm_minions M ON CB.id = M.boss_id
										WHERE M.minion_id = (SELECT id FROM mm_creatures WHERE name = ?)");
				
			$stmt->bind_param("s", $name);
			$stmt->execute();
			$stmt->bind_result($rowName);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['bosses'][]="<a href='MonsterDisplay.html?name=".$rowName."'>".$rowName."</a>";
				}
			}
			$stmt->close();
			
			
			
			
			
			
			
			return json_encode($return);
		
		}
	}
	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->requestLists();
	
	

?>