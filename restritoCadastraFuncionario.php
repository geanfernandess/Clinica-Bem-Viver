<?php

require_once "conexaoMysql.php";
require_once "autenticacao.php";
session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

// Inicializa e resgata dados do Funcionario
$nome = $sexo = $email = $telefone = "";
$cep = $logradouro = $cidade = $estado = "";
$funcaoFuncionario = $dataContrato = $salario = "";
$senhaHash = $especialidade = $crm = "";

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

if (isset($_POST["funcaoFuncionario"]))
  $funcaoFuncionario = $_POST["funcaoFuncionario"];

if (isset($_POST["dataContrato"]))
  $dataContrato = $_POST["dataContrato"];

if (isset($_POST["salario"]))
  $salario = $_POST["salario"];

if (isset($_POST["senha"]))
  $senha = $_POST["senha"];

if (isset($_POST["especialidade"]))
  $especialidade = $_POST["especialidade"];

if (isset($_POST["crm"]))
  $crm = $_POST["crm"];

// calcula um hash de senha seguro para armazenar no BD    
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

if ($funcaoFuncionario === "funcionarioNormal"){
  $sql1 = <<<SQL
    INSERT INTO clinica_pessoa 
      (nome, sexo, email, telefone, cep, logradouro, cidade, estado)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
SQL;

  $sql2 = <<<SQL
    INSERT INTO clinica_pessoa_funcionario 
      (funcaoFuncionario, dataContrato, salario, senhaHash, codigo_funcionario)
    VALUES (?, ?, ?, ?, ?)
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
      $nome, $sexo, $email, $telefone,
      $cep, $logradouro, $cidade, $estado
    ])) throw new Exception('Falha na primeira inserção');

    // Inserção na tabela pessoa_funcionario
    // Precisamos do id da pessoa cadastrado, que
    // foi gerado automaticamente pelo MySQL
    // na operação acima (campo auto_increment), para
    // prover valor para o campo chave estrangeira
    $idNovoFuncionario = $pdo->lastInsertId();
    $stmt2 = $pdo->prepare($sql2);
    if (!$stmt2->execute([
      $funcaoFuncionario, $dataContrato, $salario, 
      $senhaHash, $idNovoFuncionario
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
}

if ($funcaoFuncionario === "funcionarioMedico"){
  $sql1 = <<<SQL
    INSERT INTO clinica_pessoa 
            (nome, sexo, email, telefone, cep, logradouro, cidade, estado)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
SQL;

    $sql2 = <<<SQL
    INSERT INTO clinica_pessoa_funcionario 
            (funcaoFuncionario, dataContrato, salario, senhaHash, codigo_funcionario)
    VALUES (?, ?, ?, ?, ?)
SQL;

  $sql3 = <<<SQL
    INSERT INTO clinica_pessoa_funcionario_medico 
      (especialidade, crm, codigo_medico)
    VALUES (?, ?, ?)
SQL;

  try {

    // Inicia a transação de cadastro na tabela pessoa, funcionário e médico
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

    // Inserção na tabela pessoa_funcionario
    // Precisamos do id da pessoa cadastrado, que
    // foi gerado automaticamente pelo MySQL
    // na operação acima (campo auto_increment), para
    // prover valor para o campo chave estrangeira
    $idNovoFuncionario = $pdo->lastInsertId();
    $stmt2 = $pdo->prepare($sql2);
    if (!$stmt2->execute([
      $funcaoFuncionario, $dataContrato, $salario, 
      $senhaHash, $idNovoFuncionario
    ])) throw new Exception('Falha na segunda inserção');

    // Inserção na tabela pessoa_funcionario_medico
    // Precisamos do id da pessoa cadastrado, que
    // foi gerado automaticamente pelo MySQL
    // na operação acima (campo auto_increment), para
    // prover valor para o campo chave estrangeira
    $idNovoMedico = $pdo->lastInsertId();
    $stmt3 = $pdo->prepare($sql3);
    if (!$stmt3->execute([
      $especialidade, $crm, $idNovoFuncionario
    ])) throw new Exception('Falha na terceira inserção');

    // Caso a inserção seja feita nas três tabelas sem
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
}
