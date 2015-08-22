<?php
	//Add a new relationship between creature and attribute with a given value
	class ajaxRequest {
		function addCreatureAttr(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$creature = (string)htmlspecialchars($_POST['ca_creature']);
			$attr = (string)htmlspecialchars($_POST['ca_attr']);
			$value = (string)htmlspecialchars($_POST['ca_value']);
			$return = array();
			$return['creature'] = $creature;
			$return['attr'] = $attr;
			$return['value'] = $value;
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("INSERT INTO mm_c_attr (creature_id, attribute, stat)
										  VALUES
											((SELECT id FROM mm_creatures WHERE name= ?),
											 (SELECT id FROM mm_attributes WHERE name= ?),
											  ?)
											  ON DUPLICATE KEY UPDATE stat = ?");				
				$stmt->bind_param("ssdd", $creature, $attr, $value, $value);				
				$stmt->execute();				
				$stmt->close();
			}		
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->addCreatureAttr();
	
?>