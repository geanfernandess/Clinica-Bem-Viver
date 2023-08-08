<?php

require_once "conexaoMysql.php";
require_once "autenticacao.php";

session_start();
$pdo = mysqlConnect();
exitWhenNotLogged($pdo);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Clínica Bem Viver</title>
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
            <a href="restritoFormPaciente.html">Cadastrar Novo Paciente</a>
            <a href="restritoListaPacientes.php">Listar Pacientes Cadastrados</a>
            <a href="restritoFormFuncionario.html">Cadastrar Novo Funcionário</a>
            <a href="restritoListaFuncionarios.php">Listar Funcionários Cadastrados</a>
            <a href="restritoListaBaseEnderecos.php">Listar Endereços</a>
            <a href="restritoListaAgendamentos.php">Listar todos Agendamentos</a>
            <a href="restritoListaMeusAgendamentos.php">Listar meus Agendamentos</a>
            <a href="logout.php">Sair</a>
        </main>
    </div>
    <div class="footer">
        <footer>
            <p>© Copyright 2021. Todos os direitos reservados.</p>
        </footer>
    </div>
</body>
</html>