<?php
	include 'config.php';
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$tablename = "user";
	// sql to create table
	$sql = "CREATE TABLE $tablename (
		userID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50) NOT NULL,
		username VARCHAR(45) NOT NULL,
		password VARCHAR(45) NOT NULL,
		phone INT(11) NOT NULL
	)";
	
	if (mysqli_query($conn, $sql)) {
		echo "Table " . $tablename . " created successfully";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}

	$tablename = "shifts";

	$sql = "CREATE TABLE $tablename (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	start_day INT(11),
	end_day INT(11),
	start_time INT(11),
	end_time INT(11),
	canceled INT(1),
	userID int(11) UNSIGNED NOT NULL,
	FOREIGN KEY (userID) REFERENCES user(userID)
	)";

	if (mysqli_query($conn, $sql)) {
		echo "Table " . $tablename . " created successfully";
	} else {
		echo "Error creating table: " . mysqli_error($conn);
	}

	$tablename = "isadmin";

	$sql = "CREATE TABLE $tablename(
	userID int(11) UNSIGNED NOT NULL,
	FOREIGN KEY (userID) REFERENCES user(userID)
	)";

	if (mysqli_query($conn, $sql)) {
	echo "Table " . $tablename . " created successfully";
	} else {
	echo "Error creating table: " . mysqli_error($conn);
	}
	
	mysqli_close($conn);
?>