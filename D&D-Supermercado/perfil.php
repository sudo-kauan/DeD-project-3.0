<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

$sql = "SELECT nome, email, data_nasc, telefone, sexo 
        FROM usuarios 
        WHERE id = '$id_usuario'";

$result = $con->query($sql);

if ($result->num_rows === 1) {
    $dados_usuario = $result->fetch_assoc();
} else {
    die("Erro: Não foi possível carregar os dados do usuário.");
}

$status = $_GET['status'] ?? '';
$mensagem = '';
if ($status === 'success') {
    $mensagem = '<div class="alert alert-success" role="alert">Dados atualizados com sucesso!</div>';
} elseif ($status === 'error') {
    $mensagem = '<div class="alert alert-danger" role="alert">Erro ao atualizar os dados. Tente novamente.</div>';
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - D&D Supermercado</title>
    
    <link rel="icon" type="image/png" href="imgs/Logo_D_D2.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light"> 

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white" style="background-color: var(--primaria, #be5c00);">
                        <h4 class="mb-0"><i class="fa-solid fa-user-circle me-2"></i> Meu Perfil</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php echo $mensagem; ?>

                        <h5 class="card-title mb-4">Atualizar Dados</h5>

                        <form action="salvar_perfil.php" method="POST">
                            
                            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome Completo</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($dados_usuario['nome']); ?>" disabled>
                                <div class="form-text">Entre em contato para alterar seu nome.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($dados_usuario['email']); ?>" disabled>
                            </div>
                            
                            <div class="row">
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold" for="dataNasc">Nascimento</label>
                                    <input type="date" class="form-control" id="dataNasc" name="data_nasc" 
                                           value="<?php echo htmlspecialchars($dados_usuario['data_nasc']); ?>" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold" for="telefone">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" 
                                        value="<?php echo htmlspecialchars($dados_usuario['telefone']); ?>" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold" for="sexo">Sexo</label>
                                    <select class="form-select" id="sexo" name="sexo" required>
                                        <option value="">Selecione...</option>
                                        <option value="M" <?php echo ($dados_usuario['sexo'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                        <option value="F" <?php echo ($dados_usuario['sexo'] == 'F') ? 'selected' : ''; ?>>Feminino</option>
                                        <option value="O" <?php echo ($dados_usuario['sexo'] == 'O') ? 'selected' : ''; ?>>Outro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-block mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: var(--primaria, #be5c00); border-color: var(--primaria, #be5c00);">
                                    <i class="fa-solid fa-save me-1"></i> Salvar Alterações
                                </button>
                                <a href="index.php" class="btn btn-secondary float-md-end"><i class="fa-solid fa-arrow-left me-1"></i> Voltar à Loja</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>