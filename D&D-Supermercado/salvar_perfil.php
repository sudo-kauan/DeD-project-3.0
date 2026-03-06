<?php
session_start();
include('conexao.php');

$con = $con ?? ($mysql ?? null);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario_id']) || $con === null) {
    header("Location: index.php");
    exit();
}


$id_usuario_sessao = $_SESSION['usuario_id'];

$id_usuario_form = $con->real_escape_string($_POST['id_usuario'] ?? '');

if (empty($id_usuario_form) || $id_usuario_sessao != $id_usuario_form) {
    if ($con)
        $con->close();
    header("Location: perfil.php?status=error&msg=invalido");
    exit();
}

$data_nasc = $con->real_escape_string($_POST['data_nasc'] ?? '');
$telefone = $con->real_escape_string($_POST['telefone'] ?? ''); 
$sexo = $con->real_escape_string($_POST['sexo'] ?? '');

$sql = "UPDATE usuarios SET 
            data_nasc = '$data_nasc', 
            telefone = '$telefone', 
            sexo = '$sexo'
        WHERE id = '$id_usuario_sessao'";

if ($con->query($sql) === TRUE) {
    $con->close();
    header("Location: perfil.php?status=success");
} else {
    $con->close();
    die("ERRO AO SALVAR NO BANCO DE DADOS! Mensagem do MySQL: " . $con->error . " Query executada: " . $sql);
}

exit();
?>