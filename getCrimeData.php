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
	$from_date = (string) $_POST['from_year'] . '-' . str_pad((string) $_POST['from_month'], 2, "0", STR_PAD_LEFT);
	$to_date = (string) $_POST['to_year'] . '-' . str_pad((string) $_POST['to_month'], 2, "0", STR_PAD_LEFT);
	//echo $from_date;
	//echo $to_date;
	$STH = $DBH->prepare('SELECT Latitude, Longitude, CategoryId As Cat from crimes WHERE Date >= :from_date AND Date <= :to_date', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$STH->execute(array(':from_date' => $from_date, ':to_date' => $to_date));
	$STH->setFetchMode(PDO::FETCH_ASSOC);
	
	$crimes = array();
	while($row = $STH->fetch()) {
		
		$crimes[] = $row;
	}
	echo json_encode($crimes);
}
?>
