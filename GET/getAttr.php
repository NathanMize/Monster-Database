<?php
	class ajaxRequest {
		function getAttr(){
			require('Mize_connect.php');
			$mysqli = Mize_server();
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			$attr = (string)htmlspecialchars($_POST['getAttr']);
			$stat = (string)htmlspecialchars($_POST['getStat']);
			$return = array();
			$return['attr'] = $attr;
			$return['value'] = $stat;
			$return['stat'] = array();
			$return['name'] = array();
			$stmt = $mysqli->prepare("SELECT C.name, CA.stat FROM mm_creatures C
									  INNER JOIN mm_c_attr CA ON C.id = CA.creature_id
									  INNER JOIN mm_attributes A ON CA.attribute = A.id
									  WHERE A.name = ? AND CA.stat >= ?
									  ORDER BY CA.stat");
			$stmt->bind_param("sd", $attr, $stat);
			$stmt->execute();
			$stmt->bind_result($rowName, $rowStat);
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['name'][]="<a href='MonsterDisplay.html?name=".$rowName."'>".$rowName."</a>";
					$return['stat'][]= $rowStat;
				}
			}
			$stmt->close();
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->getAttr();
	
?>