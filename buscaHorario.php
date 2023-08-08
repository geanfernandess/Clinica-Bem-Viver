<?php

class HorariosMarcados {
    public $horariosMarcados;

    function __construct($horariosMarcados) 
    {
        $this->horariosMarcados = $horariosMarcados;
    }
}

require "conexaoMysql.php";
$pdo = mysqlConnect();

$especialidade = $dataConsulta = $horariosMarcados = "";

if (isset($_POST["especialidade"]))
    $especialidade = $_POST["especialidade"];

if (isset($_POST["dataConsulta"]))
    $dataConsulta = $_POST["dataConsulta"];
  
try {
    $sql = <<<SQL
    SELECT horarioConsulta
    FROM clinica_agenda 
    INNER JOIN clinica_pessoa_funcionario_medico ON clinica_agenda.codigo_medico = clinica_pessoa_funcionario_medico.codigo_medico
    WHERE especialidade = '$especialidade' AND dataConsulta = '$dataConsulta'
SQL;

    // Nesse caso não é necessário utilizar prepared statements
    // porque não há possibilidade de injeção de SQL, pois os 
    // parâmetros utilizados na query SQL (especialidade) e (dataConsulta) 
    // são fornecidas pelo usuário através de um campo select 
    $stmt = $pdo->query($sql);

    $arrayHorariosConsultas = [];

    while ($row = $stmt->fetch()) {

        $horariosMarcados = new HorariosMarcados($row['horarioConsulta']);

        $arrayHorariosConsultas [] = $horariosMarcados;

    }
    
    echo json_encode($arrayHorariosConsultas);

}
catch (Exception $e) {
    // altera o código de retorno de status para '500 Internal Server Error'.
    // A função http_response_code deve ser chamada antes do script enviar qualquer
    // texto para a saída padrão
    http_response_code(500);

    $msgErro = $e->getMessage();
    echo $msgErro;
}
