<?php
require_once "conexaoMysql.php";
require_once "autenticacao.php";
session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

$email = $logradouro = "";

if (isset($_GET["email"]))
  $email = $_GET["email"];

if (isset($_GET["dataConsulta"]))
  $dataConsulta = $_GET["dataConsulta"];

if (isset($_GET["horarioConsulta"]))
  $horarioConsulta = $_GET["horarioConsulta"];

try {

  $sql = <<<SQL
  DELETE FROM clinica_agenda
  WHERE email = ? AND dataConsulta = ? AND horarioConsulta = ?
  LIMIT 1
SQL;

  // Neste caso utilize prepared statements para prevenir ataques 
//   do tipo SQL Injection, pois a declaração SQL contem parâmetros 
//   (email), (dataConsulta) e (horarioConsulta) vindo da URL
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email, $dataConsulta, $horarioConsulta]);

  header("location: restritoListaAgendamentos.php");
  exit();
} 
catch (Exception $e) {  
  exit('Falha inesperada: ' . $e->getMessage());
}
