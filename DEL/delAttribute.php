<?php
	// remove an attribute from the table
	class ajaxRequest {

		function delAttr(){
			require('Mize_connect.php');

			$mysqli = Mize_server();

			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") "
				. $mysqli->connect_error;
				exit;
			}
			
			$attr_name = (string)htmlspecialchars($_POST['del_attr']);
			
			$return = array();
			$return['name'] = $attr_name ;
			
			if(count($_POST) > 0) {
				$stmt = $mysqli->prepare("DELETE FROM mm_attributes WHERE name = ? LIMIT 1");
				
				$stmt->bind_param("s", $attr_name );
				
				$stmt->execute();
				
				$stmt->close();
			}
			
			return json_encode($return);
		
		
		}	
	}	
	$ajaxRequest = new ajaxRequest;
	echo $ajaxRequest->delAttr();
	
?>