<?php

session_start();
include('conexao.php');

$con = $con ?? ($mysql ?? null);

if (isset($_POST['email']) && isset($_POST['senha']) && $con !== null) {

    $email = $con->real_escape_string($_POST['email']);
    $senha = md5($con->real_escape_string($_POST['senha'])); 

    $sql = "SELECT id, email, perfil FROM usuarios WHERE email = '$email' AND senha = '$senha'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $_SESSION['usuario_id'] = $row['id'];
        $_SESSION['usuario_login'] = $row['email'];
        $_SESSION['usuario_perfil'] = $row['perfil'];

        $con->close();
        header("Location: index.php");
        exit();
    } else {
        $con->close();
        header("Location: index.php?erro=1");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}