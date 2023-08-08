<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

// Inicializa e resgata dados do Funiconario
$email = $senhaHash = "";

if (isset($_POST["email"]))
  $email = $_POST["email"];

if (isset($_POST["senha"]))
  $senha = $_POST["senha"];

// calcula um hash de senha seguro para armazenar no BD    
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$sql1 = <<<SQL
  INSERT INTO clinica_pessoa (email)
  VALUES (?)
  SQL;

$sql2 = <<<SQL
  INSERT INTO clinica_pessoa_funcionario (senhaHash, codigo_funcionario)
  VALUES (?, ?)
  SQL;

try {

  // Inicia a transação de cadastro na tabela pessoa e funcionário
  $pdo->beginTransaction();

  // Inserção na tabela pessoa
  // Neste caso utilize prepared statements para prevenir
  // ataques do tipo SQL Injection, pois estamos
  // inseririndo dados fornecidos pelo usuário
  $stmt1 = $pdo->prepare($sql1);
  if (!$stmt1->execute([
    $email
  ])) throw new Exception('Falha na primeira inserção');

  // Inserção na tabela pessoa_funcionario
  // Precisamos do id da pessoa cadastrado, que
  // foi gerado automaticamente pelo MySQL
  // na operação acima (campo auto_increment), para
  // prover valor para o campo chave estrangeira
  $idNovoFuncionario = $pdo->lastInsertId();
  $stmt2 = $pdo->prepare($sql2);
  if (!$stmt2->execute([
    $senhaHash, $idNovoFuncionario
  ])) throw new Exception('Falha na segunda inserção');

  // Caso a inserção seja feita nas duas tabelas sem
  // apresentar nenhum tipo de erro as transações são efetivadas
  $pdo->commit();

  header("location: paginaLogin.html");
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

