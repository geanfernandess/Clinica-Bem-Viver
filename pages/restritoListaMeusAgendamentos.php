<?php

require_once "conexaoMysql.php";
require_once "autenticacao.php";
session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);

$email = $_SESSION['emailUsuario'];

try {

  $sql = <<<SQL
  SELECT A.nome AS nome_paciente, A.email, A.sexo, A.dataConsulta, A.horarioConsulta, P.nome AS nome_medico
  FROM clinica_agenda A
  INNER JOIN clinica_pessoa_funcionario_medico M ON A.codigo_medico = M.codigo_medico
  INNER JOIN clinica_pessoa_funcionario F ON M.codigo_medico = F.codigo_funcionario
  INNER JOIN clinica_pessoa P ON F.codigo_funcionario = P.codigo_pessoa
  WHERE A.codigo_medico = (SELECT codigo_pessoa FROM clinica_pessoa WHERE email = ?)
SQL;

  // Nesse caso não é necessário utilizar prepared statements
  // porque não há possibilidade de injeção de SQL, 
  // pois nenhum parâmetro é utilizado na query SQL
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
} catch (Exception $e) {
  // error_log($e->getMessage(), 3, 'log.php');
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Agendamentos Marcados</title>
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
            <h2>Agendamentos Marcados</h2>
            <table class="table table-striped table-hover">
              <tr>
                <th></th>
                <th>Nome</th>
                <th>Email</th>
                <th>Sexo</th>
                <th>Data Consulta</th>
                <th>Horário Consulta</th>
              </tr>

              <?php

              // Lê os dados retornados pela tabela agenda
              while ($row = $stmt->fetch()) {

                // Limpa os dados produzidos pelo usuário
                // com possibilidade de ataque XSS
                $nome = htmlspecialchars($row['nome_paciente']);
                $email = htmlspecialchars($row['email']);
                $sexo = htmlspecialchars($row['sexo']);

                $dataConsulta = new DateTime($row['dataConsulta']);
                $dataFormatoDiaMesAno = $dataConsulta->format('d-m-Y');

                echo <<<HTML
                  <tr>
                    <td><a href="restritoExcluiAgendamento.php?email=$email&dataConsulta={$row['dataConsulta']}&horarioConsulta={$row['horarioConsulta']}">
                      <img class="imgDelete" src="images/delete.png"></a>
                    </td>
                    <td>$nome</td> 
                    <td>$email</td>
                    <td>$sexo</td>
                    <td>$dataFormatoDiaMesAno</td>
                    <td>{$row['horarioConsulta']}h00</td> 
                  </tr>    
                HTML;
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