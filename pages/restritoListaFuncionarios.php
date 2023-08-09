<?php

require_once "conexaoMysql.php";
require_once "autenticacao.php";
session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

try {

  $sql1 = <<<SQL
  SELECT nome, email, telefone, cep, dataContrato, 
        salario, cidade, funcaoFuncionario
  FROM clinica_pessoa 
  INNER JOIN clinica_pessoa_funcionario  ON clinica_pessoa.codigo_pessoa = codigo_funcionario
SQL;

  // Nesse caso não é necessário utilizar prepared statements
  // porque não há possibilidade de injeção de SQL, 
  // pois nenhum parâmetro é utilizado na query SQL
  $stmt1 = $pdo->query($sql1);
} catch (Exception $e) {
  // error_log($e->getMessage(), 3, 'log.php');
  exit('Ocorreu uma falha: ' . $e->getMessage());
}

try {

  $sql2 = <<<SQL
  SELECT nome, email, telefone, cep, dataContrato, 
        salario, especialidade, funcaoFuncionario
  FROM clinica_pessoa 
  INNER JOIN clinica_pessoa_funcionario  ON clinica_pessoa.codigo_pessoa = codigo_funcionario
  INNER JOIN clinica_pessoa_funcionario_medico  ON clinica_pessoa_funcionario.codigo_funcionario = codigo_medico
SQL;

  // Nesse caso não é necessário utilizar prepared statements
  // porque não há possibilidade de injeção de SQL, 
  // pois nenhum parâmetro é utilizado na query SQL
  $stmt2 = $pdo->query($sql2);
} catch (Exception $e) {
  // error_log($e->getMessage(), 3, 'log.php');
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Funcionários Cadastrados</title>
    <meta name="description" content="Descrição da Clínica....">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <header class="listaMenu">
        <div class="row g-3">
            <div class="col-sm-4">
                <img src="images/logo_clinica.png" alt="Logomarca da Clínica" width="45" height="45">
            </div>
            <div class="col-sm-4">
                <h2>Clínica Bem Viver</h2>
            </div>
        </div>
    </header>  
    <!-- <nav>
        <ul class="listaMenuInterno palavrasMenuInterno"> 
            <li><a href="restritoPrincipal.php">Principal</a></li> 
            <li><a href="restritoFormFuncionario.html">Novo Funcionário</a></li> 
            <li><a href="restritoFormPaciente.html">Novo Paciente</a></li>
            <li><a href="restritoListaFuncionarios.php">Listar Funcionários</a></li>
            <li><a href="restritoListaPacientes.php">Listar Pacientes</a></li>
            <li><a href="restritoListaBaseEnderecos.php">Listar Endereços</a></li>
            <li><a href="#">Listar todos Agendamentos</a></li>
            <li><a href="#">Listar meus Agendamentos</a></li>
        </ul>
    </nav> -->
    <div class="container">
        <main class="btnInterno">
            <h2>Funcionários Cadastrados</h2>
            <table class="table table-striped table-hover">
              <tr>
                <th></th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Cep</th>
                <th>Cidade</th>
                <th>DataContrato</th>
                <th>Salário</th>
              </tr>

              <?php
                while ($row = $stmt1->fetch()) {

                  // Limpa os dados produzidos pelo usuário
                  // com possibilidade de ataque XSS
                  $funcaoFuncionario = $row['funcaoFuncionario'];
                  $nome = htmlspecialchars($row['nome']);
                  $email = htmlspecialchars($row['email']);
                  $telefone = htmlspecialchars($row['telefone']);
                  $cep = htmlspecialchars($row['cep']);
                  $cidade = htmlspecialchars($row['cidade']);
                  $salario = htmlspecialchars($row['salario']);
    
                  $dataContrato = new DateTime($row['dataContrato']);
                  $dataFormatoDiaMesAno = $dataContrato->format('d-m-Y');

                  if ($funcaoFuncionario === "funcionarioNormal"){    
                    echo <<<HTML
                      <tr>
                        <td><a href="restritoExcluiPessoa.php?email=$email">
                          <img class="imgDelete" src="images/delete.png"></a>
                        </td>
                        <td>$nome</td> 
                        <td>$email</td>
                        <td>$telefone</td>
                        <td>$cep</td>
                        <td>$cidade</td>
                        <td>$dataFormatoDiaMesAno</td>
                        <td>$salario</td>
                      </tr>      
HTML;
                  }
                }
              ?>
            </table>
            <h2>Funcionários Médicos Cadastrados</h2>
            <table class="table table-striped table-hover">
              <tr>
                <th></th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>DataContrato</th>
                <th>Salário</th>
                <th>Especialidade</th>
              </tr>

              <?php
              while ($row = $stmt2->fetch()) {

                // Limpa os dados produzidos pelo usuário
                // com possibilidade de ataque XSS
                $funcaoFuncionario = $row['funcaoFuncionario'];
                $nome = htmlspecialchars($row['nome']);
                $email = htmlspecialchars($row['email']);
                $telefone = htmlspecialchars($row['telefone']);
                $salario = htmlspecialchars($row['salario']);
                $especialidade = htmlspecialchars($row['especialidade']);

                $dataContrato = new DateTime($row['dataContrato']);
                $dataFormatoDiaMesAno = $dataContrato->format('d-m-Y');

                if ($funcaoFuncionario === "funcionarioMedico"){
                  echo <<<HTML
                  <tr>
                    <td><a href="restritoExcluiPessoa.php?email=$email">
                      <img class="imgDelete" src="images/delete.png"></a>
                    </td>
                    <td>$nome</td> 
                    <td>$email</td>
                    <td>$telefone</td>
                    <td>$dataFormatoDiaMesAno</td>
                    <td>$salario</td>
                    <td>$especialidade</td>
                  </tr>      
HTML;
                }
                
              }
              ?>
            </table>
            <div class="centralizaTexto btnInterno">
              <a href="restritoPrincipal.php">Voltar para Página Principal</a>
            </div>
        </main>
    </div>
    <div class="footer">
        <footer>
            <p>© Copyright 2021. Todos os direitos reservados.</p>
        </footer>
    </div>
</body>
</html>