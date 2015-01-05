<?php
//mysql_koneksi.php
//mysqli("localhost", "user", "password", "database");

$mysqli = new mysqli("localhost", "smsgammu", "smsgammu", "smsgammu");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

?>