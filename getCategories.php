<?php
header('Content-type: application/json');
$DBH = null;
try {
	$host = 'localhost';
	$dbname = 'crimewatch';
	$user = 'cw_user';
	$pass = 'Temp1234!';
	global $DBH;
	# MySQL with PDO_MYSQL
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
}
catch(PDOException $e) {
	echo "{'error': 'Could not establish connection with database'}";
}

if ($DBH) {
	$STH = $DBH->query('SELECT CategoryId AS id, Description AS cat from category ORDER BY cat');
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	
	$categories = array();
	while($row = $STH->fetch()) {
		
		$categories[] = array('id' => $row['id'], 'cat' => ucwords(strtolower(trim($row['cat']))));
	}
	echo json_encode($categories);
}
?>