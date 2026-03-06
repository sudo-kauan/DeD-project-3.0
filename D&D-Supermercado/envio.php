<?php
include_once "conexao.php"; 

$con = $con ?? ($mysql ?? null); 
if ($con === null) {
    header("Location: index.php?cadastro_erro=3"); 
    exit();
}

$nome = $con->real_escape_string(isset($_POST["nome"]) ? $_POST["nome"] : '');
$data_nasc = $con->real_escape_string(isset($_POST["data_nasc"]) ? $_POST["data_nasc"] : ''); 
$telefone = $con->real_escape_string(isset($_POST["telefone"]) ? $_POST["telefone"] : '');
$sexo = $con->real_escape_string(isset($_POST["sexo"]) ? $_POST["sexo"] : '');
$email = $con->real_escape_string(isset($_POST["email"]) ? $_POST["email"] : '');
$senha = isset($_POST["senha"]) ? $_POST["senha"] : '';
$confirmar_senha = isset($_POST["confirma_senha"]) ? $_POST["confirma_senha"] : ''; 

if ($senha !== $confirmar_senha) {
    header("Location: index.php?cadastro_erro=1");
    exit();
}

$check_sql = "SELECT id FROM usuarios WHERE email = '$email'";
$check_result = $con->query($check_sql);
if ($check_result->num_rows > 0) {
    header("Location: index.php?cadastro_erro=2");
    exit();
}

$senha_criptografada = md5($senha); 

$sql = "INSERT INTO usuarios (nome, email, senha, data_nasc, telefone, sexo) 
        VALUES ('$nome', '$email', '$senha_criptografada', '$data_nasc', '$telefone', '$sexo')";


if ($con->query($sql) === TRUE) {
    $con->close();
    header("Location: index.php?cadastro_sucesso=1");
    exit();
} else {
    $erro_mysql = $con->error;
    $con->close();

    die("<h1>Erro Crítico no Cadastro!</h1><p>Houve um erro no MySQL. Verifique a estrutura da sua tabela 'usuarios'.</p><p><strong>Query:</strong> $sql</p><p><strong>Erro MySQL:</strong> $erro_mysql</p>");
}
