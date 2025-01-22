<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "mixology_store";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
	die("Błąd połączenia: ".$conn->connect_error);
}

mysqli_set_charset($conn,"utf8mb4");