<?php
    $hostname = "localhost";
    $banco = "clientes"; 
    $usuario = "root"; 
    $senha = "";

    $con = new mysqli($hostname, $usuario, $senha, $banco); 
    
    if ($con->connect_error) { // Usa $con
        die("Falha ao conectar". $con->connect_error);
    }
