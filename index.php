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
    <nav>
        <ul class="listaMenu palavrasMenu"> 
            <li><a href="#">Home</a></li> 
            <li><a href="galeria.html">Galeria</a></li>
            <li><a href="formNovoEndereco.html">Novo Endereço</a></li>
            <li><a href="formAgendamento.php">Agendar Consulta</a></li>
            <li class="btnLogin"><a href="paginaLogin.html">Login</a></li>
        </ul>
    </nav>
    <div class="container">
        <main>
            <div id="imagemPrincipal">
                <img src="images/imagemPrincipal.png" alt="Logomarca da Clínica" width="300" height="200">
                <p>Clínica Bem Viver</p>
            </div>
            <hr>
            <section id="nomeClinica">
                <h2>Clínica Bem Viver</h2>
                <p>Com mais de 15 anos no mercado, nossa clinica já foi eleita sete vezes como a melhor clínica da região.</p>
				<p>Iniciamos nossas atividades em 2002 com apenas um pediatra e dois clinicos gerais, já em 2005 contavamos com nosso proprio ambulatorio
				ganhando assim pela primeira vez como melhor clinica medica de Nlogonia e região.</p>
				<p>Hoje contamos com amplo hospital capaz de receber dezenas internações, e desde 2018 contamos com 4 unidades de tratamento intensivo.</p>
            </section>
            <hr>
            <section id="descricaoClinica">
                <h2>Descrição</h2>
				<ul>
					<li>Mais de 150 leitos no hospital</li>
					<li>4 UTI's</li>
					<li>dezenas de medicos especialistas</li>
					<li>Planos de saude especializados</li>
					<li>atendimento 24 horas</li>
				</ul>
            </section>
            <hr>
            <section id="valoresClinica"></section>
                <h2>Nossos Valores</h2>
                <h3>Nosso compromisso é com a saúde</h3>
				<p>Acreditamos que é obrigação de um serviço de saúde esforsar-se ao máximo para restaurar a saúde de seus pacientes, portanto
				não medimos esforços para obter sucesso em nossos tratamentos.</p>
				<p>Para nós a um funcionario fisico e mentalmente saldavel é importante, por isso distribuimos tarefas de maneira a não sobrecarregar ninguem,
				oferecemos também apoio psicologico e passes em academia para todos funcionarios, mente sã corpo são.</p>
				<p>Sabemos que o acompanhamento da familia é importante para a recuperação do paciente, por isso oferecemos um serviço de atendimento aos familiares,
				para que eles possam acompanhar a evolução do quadro de seus familiares, alem disso procuramos ser muito receptivos durante os horarios de visita.</p>
            </section>
            <hr>
            <div class="centralizaTexto">
                <section id="informacoescontato">
                    <h2>Informações de Contato</h2> 
                    <a href="mailto:clinicabemviver@gmail.com">Entre em contato por e-mail.</a><br>
                    <a href="tel:034-3814-2745"> Entre em contato por telefone.</a>
                    <Address>
                        Rua José Miguel Saramago, número xxxx - Bairro Santa Mônica<br>
                        Uberlândia, MG<br>
                        CEP: 38400-000
                    </Address>
                    <div id="bordaIframe">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3774.235184772845!2d-48.2477636!3d-18.9209814!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94a4457ac54935c1%3A0x2a41f8a38cb75d68!2sR.%20Jos%C3%A9%20Miguel%20Saramago%2C%201045%20-%20Santa%20M%C3%B4nica%2C%20Uberl%C3%A2ndia%20-%20MG%2C%2038408-222!5e0!3m2!1spt-BR!2sbr!4v1615073929177!5m2!1spt-BR!2sbr" 
                            width="500" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>" 
                    </div>
                </section>
            </div>     
        </main>
    </div>
    <footer>
        <p>© Copyright 2021. Todos os direitos reservados.</p>
    </footer>

</body>

</html>
