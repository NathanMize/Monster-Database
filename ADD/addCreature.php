<?php
	//Add a new creature to the database
	class ajaxRequest {
		function addCreature(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$name = (string)htmlspecialchars($_POST['creatureName']);
			$hp = (string)htmlspecialchars($_POST['creatureHP']);
			$ac = (string)htmlspecialchars($_POST['creatureAC']);
			$cr = (string)htmlspecialchars($_POST['creatureCR']);
			$type = (string)htmlspecialchars($_POST['creatureType']);
			$return = array();
			$return['name'] = $name;
			$return['hp'] = $hp;
			$return['ac'] = $ac;
			$return['cr'] = $cr;
			$return['type'] = $type;
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_creatures(name, hp, ac, cr, type_id) 
												  VALUES (?, ?, ?, ?, 
														 (SELECT id FROM mm_types WHERE category = ?))
												  ON DUPLICATE KEY UPDATE hp = ?, ac = ?, cr = ?, 
															type_id = (SELECT id FROM mm_types WHERE category = ?)");			
				$stmt->bind_param("sdddsddds", $name, $hp, $ac, $cr, $type, $hp, $ac, $cr, $type);
				$stmt->execute();				
				$stmt->close();
			}			
			return json_encode($return);				
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addCreature();	
?>