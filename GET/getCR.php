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
			$minCR = (string)htmlspecialchars($_POST['getCRmin']);
			$maxCR = (string)htmlspecialchars($_POST['getCRmax']);			
			$return = array();
			$return['cr'] = array();
			$return['name'] = array();
			$stmt = $mysqli->prepare("SELECT C.name, C.cr FROM mm_creatures C WHERE C.cr >= ? AND C.cr <= ? ORDER BY C.cr, C.name");
			
			$stmt->bind_param("dd", $minCR, $maxCR);
			 
			$stmt->execute();
			$stmt->bind_result($rowName, $rowCR);
			
			while($stmt->fetch()){
				if (strlen($rowName) > 0){
					$return['name'][]="<a href='MonsterDisplay.html?name=".$rowName."'>".$rowName."</a>";
					$return['cr'][]= $rowCR;
				}
			}
			$stmt->close();
			return json_encode($return);
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->getCR();	
?>