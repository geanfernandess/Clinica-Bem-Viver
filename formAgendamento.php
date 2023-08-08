<?php

require "conexaoMysql.php";
$pdo = mysqlConnect();

try {

  $sql = <<<SQL
  SELECT DISTINCT especialidade
  FROM clinica_pessoa 
  INNER JOIN clinica_pessoa_funcionario  ON clinica_pessoa.codigo_pessoa = codigo_funcionario
  INNER JOIN clinica_pessoa_funcionario_medico  ON clinica_pessoa_funcionario.codigo_funcionario = codigo_medico
SQL;

  // Nesse caso não é necessário utilizar prepared statements
  // porque não há possibilidade de injeção de SQL, 
  // pois nenhum parâmetro é utilizado na query SQL
  $stmt = $pdo->query($sql);
} catch (Exception $e) {
  // error_log($e->getMessage(), 3, 'log.php');
  exit('Ocorreu uma falha: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Agendamento Consulta</title>
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
            <li><a href="index.html">Home</a></li> 
            <li><a href="galeria.html">Galeria</a></li>
            <li><a href="formNovoEndereco.html">Novo Endereço</a></li>
            <li><a href="#">Agendar Consulta</a></li>
            <li class="btnLogin"><a href="paginaLogin.html">Login</a></li>
        </ul>
    </nav>
    <div class="container">
        <main>
            <form action="cadastraConsulta.php" name="formAgendamento" method="POST" >
                <fieldset>
                    <legend><h2>Agendar Consulta</h2></legend>

                        <div class="row g-3">
                            <div class="col-sm-5">
                                <label for="nome" class="form-label form-label-sm">Nome</label>
                                <input type="text" class="form-control form-control-sm" id="nome" name="nome">
                                <span></span>
                            </div>
                            <div class="col-sm-5">
                                <label for="email" class="form-label form-label-sm">E-mail</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email">
                                <span></span>
                            </div>
                            <div class="col-sm-2">
                                <label for="sexo" class="form-label form-label-sm">Sexo</label>
                                <input type="text" class="form-control form-control-sm" id="sexo" name="sexo">
                                <span></span>
                            </div>
                            <div class="col-sm-3">
                                <label for="especialidade" class="form-label form-label-sm">Especialidade</label>
                                <select class="form-select form-select-sm" name="especialidade" id="especialidade">
                                    <option value="Selecione">Selecione</option>
                                    <?php
                                    while ($row = $stmt->fetch()) {
                                        $especialidade = htmlspecialchars($row['especialidade']);
                                        echo <<<HTML
                                            <option value="$especialidade">$especialidade</option>
                                        HTML;
                                    }
                                    ?>
                                </select>
                                <span></span>
                            </div>
                            <div class="col-sm-3">
                                <label for="medicoEspecialista" class="form-label form-label-sm">Médico Especialista</label>
                                <select class="form-select form-select-sm" name="medicoEspecialista" id="medicoEspecialista">
                                </select>
                                <span></span>
                            </div>
                            <div class="col-sm-3">
                                <label for="dataConsulta" class="form-label form-label-sm">Data Consulta</label>
                                <input type="date" class="form-control form-control-sm" id="dataConsulta" name="dataConsulta">
                                <span></span>
                            </div>
                            <div class="col-sm-3">
                                <label for="horarioConsulta" class="form-label form-label-sm">Horário disponivel</label>
                                <select class="form-select form-select-sm" name="horarioConsulta" id="horarioConsulta">
                                </select>
                                <span></span>
                            </div>
                        </div>
                </fieldset>
                <div class="col-sm-12 divButton">
                    <button class="btn btn-dark btn-sm">Agendar Consulta</button>
                </div>
            </form>
        </main>

        <script>

            window.onload = function () {
                document.forms.formAgendamento.onsubmit = validaForm;
            
                const inputEspecialidade = document.querySelector("#especialidade");
                inputEspecialidade.onchange = () => atualizaCampoMedicoEspecialista(inputEspecialidade.value);

                const inputDataConsulta = document.querySelector("#dataConsulta");
                inputDataConsulta.onchange = () => atualizaCampoHorarioConsulta(inputDataConsulta.value);
            }


            function atualizaCampoMedicoEspecialista(especialidade) {

                // Seleciona o select referente ao medico especialista e caso o mesmo
                // possua opções, as mesmas são removidas. Quando não existir mais opções 
                //no select referente ao medico especialista a função buscaEspecialidade é 
                // chamada. Para que seja criada novas opções de medicos de acordo com a 
                // especialidade selecionada.
                let campoSelectMedico = document.querySelector("#medicoEspecialista");
                while (campoSelectMedico.options.length > 0) {                
                    campoSelectMedico.remove(0);
                }  

                buscaEspecialidade(especialidade);
            }


            function buscaEspecialidade(especialidade) {

                let xhr = new XMLHttpRequest();
                xhr.open("GET", "buscaEspecialidade.php?especialidade=" + especialidade, true);

                xhr.onload = function () {
                    
                    // verifica o código de status retornado pelo servidor.
                    if (xhr.status != 200) {
                        console.error("Falha inesperada: " + xhr.responseText);
                        return;
                    }

                    // converte a string JSON para objeto JavaScript.
                    try {
                        var dadosMedicos = JSON.parse(xhr.responseText);
                    }
                    catch (e) {
                        console.error("String JSON inválida: " + xhr.responseText);
                        return;
                    }

                    // utiliza os dados retornados para preencher o select.
                    let campoSelect = document.getElementById("medicoEspecialista"); 
                    dadosMedicos.forEach(elemento => {
                        var option = document.createElement("option"); 
                        option.text = elemento.nome_medico;
                        option.value = elemento.codigo_medico;
                        campoSelect.add(option);
                    });
                }

                xhr.onerror = function () {
                    console.error("Erro de rede - requisição não finalizada");
                };

                xhr.send();
            }


            function atualizaCampoHorarioConsulta(dataConsulta) {

                // Seleciona o select referente ao horário da consulta e caso o mesmo
                // possua opções, as mesmas são removidas. Quando não existir mais opções 
                // no select referente ao horário da consulta  é relizada uma verificação
                // para saber se o select referente ao médico especialista foi selecionado 
                // e em seguida a função buscaEspecialidade é chamada. Para que seja criada 
                // novas opções de medicos de acordo com a especialidade selecionada.
                let campoSelectHorario = document.querySelector("#horarioConsulta");
                while (campoSelectHorario.options.length > 0) {                
                    campoSelectHorario.remove(0);
                }

                // Seleciona o span referente a data da consulta, o select referente ao médico
                // especialista. Em seguida faz a verificação para ver se o mesmo foi selecionado, caso 
                // tenha sido selecionado chama a função buscaHorariosMarcados, caso não tenha sido 
                // selecionado apresenta uma mensagem solicitando a seleção da especialidade e do médico.
                const spanDataConsulta = formAgendamento.dataConsulta.nextElementSibling;
                spanDataConsulta.textContent = "";
                let campoSelectMedico = document.querySelector("#medicoEspecialista");
                if (campoSelectMedico.options.length === 0){
                    spanDataConsulta.textContent = "Selecione a especialidade e o médico para que os horários sejam carregados";
                } 

                // Chama a função buscaHorariosMarcados
                else {
                    buscaHorariosMarcados(dataConsulta);
                }
            }


            function buscaHorariosMarcados(dataConsulta) {

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "buscaHorario.php", true);

                xhr.onload = function () {
                    
                    // verifica o código de status retornado pelo servidor.
                    if (xhr.status != 200) {
                        console.error("Falha inesperada: " + xhr.responseText);
                        return;
                    }

                    // converte a string JSON para objeto JavaScript.
                    try {
                        var horariosMarcados = JSON.parse(xhr.responseText);
                    }
                    catch (e) {
                        console.error("String JSON inválida: " + xhr.responseText);
                        return;
                    }

                    // Cria-se um array com todos os horários disponíveis para agendamento.
                    let horariosDisponiveis = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17];

                    // Utiliza os dados retornados pelo servidor e com o auxilio do forEach e do indexOf
                    // verifica a posição que os mesmos se encontram no array de horários disponiveis para 
                    // consulta. Em seguida os horários retornados pelo servidor são retirados do array 
                    // de horários disponiveis. Após isso, cria-se opções para o select verificaBuscaHorarioConsulta
                    // com os horários disponiveis ainda restantes no array horariosDisponiveis.
                    horariosMarcados.forEach(elemento => {
                        horariosDisponiveis.splice(horariosDisponiveis.indexOf(elemento.horariosMarcados), 1);
                    });

                    let campoSelect = document.getElementById("horarioConsulta"); 
                    horariosDisponiveis.forEach(elemento => {
                        var option = document.createElement("option"); 
                        option.text = elemento + "h00";
                        option.value = elemento;
                        campoSelect.add(option);
                    });
                }

                xhr.onerror = function () {
                    console.error("Erro de rede - requisição não finalizada");
                };

                const form = document.querySelector("form");
                xhr.send(new FormData(form));
            }


            function validaForm (e) {
            let form = e.target;
            let formValido = true;

            const spanNome = form.nome.nextElementSibling;
            const spanEmail = form.email.nextElementSibling;
            const spanSexo = form.sexo.nextElementSibling;
            const spanEspecialidade = form.especialidade.nextElementSibling;
            const spanMedicoEspecialista = form.medicoEspecialista.nextElementSibling;
            const spanDataConsulta = form.dataConsulta.nextElementSibling;
            const spanHorarioConsulta = form.horarioConsulta.nextElementSibling;

            spanNome.textContent = "";
            spanEmail.textContent = "";
            spanSexo.textContent = "";
            spanEspecialidade.textContent = "";
            spanMedicoEspecialista.textContent = "";
            spanDataConsulta.textContent = "";
            spanHorarioConsulta.textContent = "";

            if (form.nome.value === "") {
                spanNome.textContent = 'O nome deve ser preenchido';
                formValido = false;
            }

            if (form.email.value === "") {
                spanEmail.textContent = 'O e-mail deve ser preenchido';
                formValido = false;
            }

            if (form.sexo.value === "") {
                spanSexo.textContent = 'Preencha o sexo';
                formValido = false;
            }

            if (form.especialidade.value === "Selecione") {
                spanEspecialidade.textContent = 'Selecione a especialidade médica';
                formValido = false;
            }

            if (form.medicoEspecialista.value === "") {
                spanMedicoEspecialista.textContent = 'Selecione o médico especialista';
                formValido = false;
            }

            if (form.dataConsulta.value === "") {
                spanDataConsulta.textContent = 'Escolha a data da consulta';
                formValido = false;
            }

            if (form.horarioConsulta.value === "") {
                spanHorarioConsulta.textContent = 'Selecione o horário da consulta';
                formValido = false;
            }

            return formValido;
        }
            
        </script>

    </div>
    <footer class="footer">
        <p>© Copyright 2021. Todos os direitos reservados.</p>
    </footer>

</body>

</html>