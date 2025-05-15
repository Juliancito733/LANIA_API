<?php
$host = "localhost";
$user = "lania";
$password = "l4n1@Cc";
$database = "lania_cc";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
