<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html");
    exit();
}

$perfil = $_SESSION["usuario_perfil"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Início</title>
</head>

<body>
    <h1>Bem-vindo, <?php echo $_SESSION['usuario_login']; ?>!</h1>

    <?php if ($perfil == 'admin') { ?>
        <h2>Opções de Administrador</h2>
        <p>Acesso total ao painel de controle.</p>
        <ul>
            <li><a href="admin_produtos.php">Gerenciar Produtos</a></li>
            <li><a href="#">Relatórios de Vendas</a></li>
        </ul>
    <?php } else { ?>
        <h2>Opções de Cliente</h2>
        <p>Área de cliente e pedidos.</p>
        <ul>
            <li><a href="#">Meus Pedidos</a></li>
            <li><a href="#">Minha Conta</a></li>
        </ul>
    <?php } ?>

    <p><a href="logout.php">Sair (Logout)</a></p>
</body>

</html>