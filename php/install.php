 <?php

/* Kjør denne filen på en server for å opprette database tabeller.
NB: Endre på config.php først med riktig db informasjon. */

include 'config.php';

// lager og sjekker tilkoblingen
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// lager "location" tabellen
$tablename = "location";
$sql       = "CREATE TABLE $tablename (
        location VARCHAR(80) PRIMARY KEY NOT NULL
    )";
if (mysqli_query($conn, $sql)) {
    echo "Table " . $tablename . " created successfully";
    print '<br>';
} else {
    echo "Error creating table: " . mysqli_error($conn);
    print '<br>';
}

// lager "user" tabellen
$tablename = "user";
$sql       = "CREATE TABLE $tablename (
        userID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        username VARCHAR(45) NOT NULL,
        password VARCHAR(255) NOT NULL,
        phone INT(11) UNSIGNED,
        location VARCHAR (80),
        FOREIGN KEY (location) REFERENCES location(location)
    )";

if (mysqli_query($conn, $sql)) {
    echo "Table " . $tablename . " created successfully";
    $hashAdmpass = password_hash($admpassword, PASSWORD_DEFAULT);
    $sql = "INSERT INTO $tablename (userID, name, username, password, phone, location) VALUES(1, 'Administrator', '$admusername', '$hashAdmpass', null, null)";
    if (mysqli_query($conn, $sql)) {
        echo "Administrator user added to " . $tablename . " with the userID 1, username $admusername and password $admpassword";
        print '<br>';
    } else {
        echo "Error: " . mysqli_error($conn);
        echo "couldn't add Administrator user to table";
        print '<br>';
    }
} else {
    echo "Error creating table: " . mysqli_error($conn);
    print '<br>';
}

// lager "shifts" tabellen
$tablename = "shifts";
$sql       = "CREATE TABLE $tablename (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    start_day INT(11),
    end_day INT(11),
    start_time INT(11),
    end_time INT(11),
    canceled INT(1) ,
    userID int(11) UNSIGNED NOT NULL,
    FOREIGN KEY (userID) REFERENCES user(userID)
    )";

if (mysqli_query($conn, $sql)) {
    echo "Table " . $tablename . " created successfully";
    print '<br>';
} else {
    echo "Error creating table: " . mysqli_error($conn);
    print '<br>';
}

// lager isadmin tabellen
$tablename = "isadmin";
$sql       = "CREATE TABLE $tablename(
    userID int(11) UNSIGNED NOT NULL,
    FOREIGN KEY (userID) REFERENCES user(userID)
    )";

if (mysqli_query($conn, $sql)) {
    echo "Table " . $tablename . " created successfully";
    $sql = "INSERT INTO isadmin(userID) VALUES(1)";
    if (mysqli_query($conn, $sql)) {
        echo "Admin user $admusername " . " successfully given privileges";
        print '<br>';
    }
    
    else {
        echo "Error creating table: " . mysqli_error($conn);
        print '<br>';
    }
} else {
    echo "Error creating table: " . mysqli_error($conn);
    print '<br>';
}

mysqli_close($conn);
?> 