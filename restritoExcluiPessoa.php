<?php
require_once "conexaoMysql.php";
require_once "autenticacao.php";
session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

$email = "";
if (isset($_GET["email"]))
  $email = $_GET["email"];

try {

  $sql = <<<SQL
  DELETE FROM clinica_pessoa
  WHERE email = ?
  LIMIT 1
SQL;

  // Neste caso utilize prepared statements para prevenir
  // ataques do tipo SQL Injection, pois a declaração
  // SQL contem um parâmetro (email) vindo da URL
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);

  header("location: restritoPrincipal.php");
  exit();
} 
catch (Exception $e) {  
  exit('Falha inesperada: ' . $e->getMessage());
}
