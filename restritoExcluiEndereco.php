<?php
require_once "conexaoMysql.php";
require_once "autenticacao.php";
session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

$cep = $logradouro = "";

if (isset($_GET["cep"]))
  $cep = $_GET["cep"];

if (isset($_GET["logradouro"]))
  $logradouro = $_GET["logradouro"];

try {

  $sql = <<<SQL
  DELETE FROM clinica_base_enderecos
  WHERE cep = ? AND logradouro = ?
  LIMIT 1
SQL;

  // Neste caso utilize prepared statements para prevenir
  // ataques do tipo SQL Injection, pois a declaração
  // SQL contem parâmetros (CEP) e (logradouro) vindo da URL
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$cep, $logradouro]);

  header("location: restritoListaBaseEnderecos.php");
  exit();
} 
catch (Exception $e) {  
  exit('Falha inesperada: ' . $e->getMessage());
}
