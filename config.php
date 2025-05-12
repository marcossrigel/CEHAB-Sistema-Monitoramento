<?php
$dbHost     = getenv('MYSQLHOST');
$dbUsername = getenv('MYSQLUSER');
$dbPassword = getenv('MYSQLPASSWORD');
$dbName     = getenv('MYSQLDATABASE');

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}
?>
