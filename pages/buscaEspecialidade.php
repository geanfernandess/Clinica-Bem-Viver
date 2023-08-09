<?php

class DadosMedicos {
    public $nome_medico;
    public $codigo_medico;

    function __construct($nome_medico, $codigo_medico) 
    {
        $this->nome_medico = $nome_medico;
        $this->codigo_medico = $codigo_medico;
    }
}

require "conexaoMysql.php";
$pdo = mysqlConnect();

$especialidade = "";

if (isset($_GET["especialidade"]))
    $especialidade = $_GET["especialidade"];

try {
    $sql = <<<SQL
    SELECT nome, codigo_medico
    FROM clinica_pessoa 
    INNER JOIN clinica_pessoa_funcionario  ON clinica_pessoa.codigo_pessoa = codigo_funcionario
    INNER JOIN clinica_pessoa_funcionario_medico  ON clinica_pessoa_funcionario.codigo_funcionario = codigo_medico
    WHERE especialidade = ?
SQL;

    // Neste caso utilize prepared statements para prevenir
    // ataques do tipo SQL Injection, pois a declaração
    // SQL contem um parâmetro (especialidade) vindo da URL
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$especialidade]);

    $arrayDadosMedicos = [];

    while ($row = $stmt->fetch()) {

        $dadosMedicos = new DadosMedicos($row['nome'], $row['codigo_medico']);

        $arrayDadosMedicos [] = $dadosMedicos;
    }
    
    echo json_encode($arrayDadosMedicos);
}
catch (Exception $e)
{
    // altera o código de retorno de status para '500 Internal Server Error'.
    // A função http_response_code deve ser chamada antes do script enviar qualquer
    // texto para a saída padrão
    http_response_code(500);

    $msgErro = $e->getMessage();
    echo $msgErro;
}
