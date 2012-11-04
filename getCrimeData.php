<?php
$DBH;
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
	echo "<h1>Database Error</h1>";
}

$STH = $DBH->query('SELECT Latitude, Longitude, CategoryId As Cat from crimes LIMIT 1000');
$STH->setFetchMode(PDO::FETCH_ASSOC);

$crimes = array();
while($row = $STH->fetch()) {
	
	$crimes[] = $row;
}
echo json_encode($crimes)
?>