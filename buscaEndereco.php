<?php

class Endereco
{
    public $logradouro;
    public $cidade;
    public $estado;

    function __construct($logradouro, $cidade, $estado) 
    {
      $this->logradouro = $logradouro;
      $this->cidade = $cidade;
      $this->estado = $estado;
    }
}

require "conexaoMysql.php";
$pdo = mysqlConnect();

$endereco = $cep = "";

if (isset($_GET["cep"]))
    $cep = $_GET["cep"];

try {
    $sql = <<<SQL
    SELECT logradouro, cidade, estado
    FROM clinica_base_enderecos
    WHERE cep = ?
SQL;

    // Neste caso utilize prepared statements para prevenir
    // ataques do tipo SQL Injection, pois a declaração
    // SQL contem um parâmetro (cep) vindo da URL
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cep]);

    while ($row = $stmt->fetch()) {

        $endereco = new Endereco($row['logradouro'], $row['cidade'], $row['estado']);

    }
    
    echo json_encode($endereco);
}
catch (Exception $e) {
    $msgErro = $e->getMessage();
    echo $msgErro;
}
