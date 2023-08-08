<?php
    
require "conexaoMysql.php";
$pdo = mysqlConnect();
    
$nome = $email = $sexo = $codigo_medico = "";
$dataConsulta = $horarioConsulta = "";

if (isset($_POST["nome"]))
    $nome = $_POST["nome"];

if (isset($_POST["email"]))
    $email = $_POST["email"];

if (isset($_POST["sexo"]))
    $sexo = $_POST["sexo"];

if (isset($_POST["medicoEspecialista"]))
    $codigo_medico = $_POST["medicoEspecialista"];

if (isset($_POST["dataConsulta"]))
    $dataConsulta = $_POST["dataConsulta"];

if (isset($_POST["horarioConsulta"]))
    $horarioConsulta = $_POST["horarioConsulta"];


try {

  $sql = <<<SQL
  INSERT INTO clinica_agenda (nome, email, sexo, codigo_medico, dataConsulta, horarioConsulta)
  VALUES (?, ?, ?, ?, ?, ?)
SQL;

  // Neste caso utilize prepared statements para prevenir
  // ataques do tipo SQL Injection, pois precisamos
  // cadastrar dados fornecidos pelo usuário 
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$nome, $email, $sexo, $codigo_medico, $dataConsulta, $horarioConsulta]);

  header("location: formAgendamento.php");
  exit();
} 
catch (Exception $e) {  
  //error_log($e->getMessage(), 3, 'log.php');
  if ($e->errorInfo[1] === 1062)
    exit('Dados duplicados: ' . $e->getMessage());
  else
    exit('Falha ao cadastrar os dados: ' . $e->getMessage());
}