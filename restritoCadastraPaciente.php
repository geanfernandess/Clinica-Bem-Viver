<?php

require_once "conexaoMysql.php";
require_once "autenticacao.php";
session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

// Inicializa e resgata dados do paciente
$nome = $sexo = $email = $telefone = "";
$cep = $logradouro = $cidade = $estado = "";
$peso = $altura = $tipoSanguineo = "";

if (isset($_POST["nome"]))
  $nome = $_POST["nome"];

if (isset($_POST["sexo"]))
  $sexo = $_POST["sexo"];

if (isset($_POST["email"]))
  $email = $_POST["email"];

if (isset($_POST["telefone"]))
  $telefone = $_POST["telefone"];

if (isset($_POST["cep"]))
  $cep = $_POST["cep"];

if (isset($_POST["logradouro"]))
  $logradouro = $_POST["logradouro"];

if (isset($_POST["cidade"]))
  $cidade = $_POST["cidade"];

if (isset($_POST["estado"]))
  $estado = $_POST["estado"];

if (isset($_POST["peso"]))
  $peso = $_POST["peso"];

if (isset($_POST["altura"]))
  $altura = $_POST["altura"];

if (isset($_POST["tipoSanguineo"]))
  $tipoSanguineo = $_POST["tipoSanguineo"];

$sql1 = <<<SQL
  INSERT INTO clinica_pessoa 
    (nome, sexo, email, telefone, cep, logradouro, cidade, estado)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
SQL;

$sql2 = <<<SQL
  INSERT INTO clinica_pessoa_paciente 
    (peso, altura, tipoSanguineo, codigo_paciente)
  VALUES (?, ?, ?, ?)
SQL;

try {

  // Inicia a transação de cadastro na tabela pessoa e paciente
  $pdo->beginTransaction();

  // Inserção na tabela pessoa
  // Neste caso utilize prepared statements para prevenir
  // ataques do tipo SQL Injection, pois estamos
  // inseririndo dados fornecidos pelo usuário
  $stmt1 = $pdo->prepare($sql1);
  if (!$stmt1->execute([
    $nome, $sexo, $email, $telefone,
    $cep, $logradouro, $cidade, $estado
  ])) throw new Exception('Falha na primeira inserção');

  // Inserção na tabela pessoa_paciente
  // Precisamos do id do pessoa cadastrado, que
  // foi gerado automaticamente pelo MySQL
  // na operação acima (campo auto_increment), para
  // prover valor para o campo chave estrangeira
  $idNovoPaciente = $pdo->lastInsertId();
  $stmt2 = $pdo->prepare($sql2);
  if (!$stmt2->execute([
    $peso, $altura, $tipoSanguineo, $idNovoPaciente
  ])) throw new Exception('Falha na segunda inserção');

  // Caso a inserção seja feita nas duas tabelas sem
  // apresentar nenhum tipo de erro as transações são efetivadas
  $pdo->commit();

  header("location: restritoPrincipal.php");
  exit();
} 
catch (Exception $e) {
  // Caso a inserção nas tabelas apresente algum tipo de 
  // erro a função rollBack retorna as tabelas envonlvidas na
  // transação para seu estado inicial
  $pdo->rollBack();
  if ($e->errorInfo[1] === 1062)
    exit('Dados duplicados: ' . $e->getMessage());
  else
    exit('Falha ao cadastrar os dados: ' . $e->getMessage());
}
