<?php
// Painel de Administração

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    die('Acesso negado. <a href="login.php">Fazer Login</a>');
}

if (!isset($_SESSION['usuario_perfil']) || $_SESSION['usuario_perfil'] !== 'admin') {
    die('Acesso negado. Você não tem permissão de administrador.');
}

include 'conexao.php';
$db_conn = $con;


// LÓGICA DE CATEGORIAS E FILTRAGEM
$categorias = [
    'acougue' => 'Açougue',
    'bebidas_nao_alcoolicas' => 'Bebidas Não Alcoólicas',
    'bebidas_alcoolicas' => 'Bebidas Alcoólicas',
    'hortifruti' => 'Hortifruti',
    'limpeza' => 'Limpeza',
    'padaria' => 'Padaria',
    'higiene' => 'Higiene Pessoal',
    'frios' => 'Frios e Laticínios',
    'mercearia' => 'Mercearia',
    'condimentos' => 'Condimentos',
];

// Filtragem da Tabela
$sql_where = "";
$categoria_selecionada = $_GET['categoria'] ?? 'todos';
$titulo_filtro = "Todos os Produtos";

if ($categoria_selecionada !== 'todos' && array_key_exists($categoria_selecionada, $categorias)) {
    $sql_where = " WHERE categoria = '{$categoria_selecionada}'";
    $titulo_filtro = $categorias[$categoria_selecionada];
}


// --- DELETE ---
if (isset($_GET['deletar'])) {
    $id = intval($_GET['deletar']);

    // 1.1. Seleciona o caminho da imagem para deletá-la do servidor
    $stmt_select = $db_conn->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result_select = $stmt_select->get_result();

    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();
        $imagem_path = $row['imagem'];

        // 1.2. Query de Deleção Segura
        $stmt_delete = $db_conn->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            if (file_exists($imagem_path) && !is_dir($imagem_path)) {
                unlink($imagem_path);
            }
            // Redireciona mantendo o filtro de categoria se houver
            $redirect_query = $categoria_selecionada !== 'todos' ? "&categoria={$categoria_selecionada}" : "";
            header("Location: admin.php?status=deleted" . $redirect_query);
        } else {
            header("Location: admin.php?status=error&msg=Erro ao deletar produto: " . urlencode($stmt_delete->error));
        }
    } else {
        header("Location: admin.php?status=error&msg=" . urlencode("Produto não encontrado!"));
    }
    exit();
}

// ---Carregar Dados de Edição ---
$produto_para_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    $stmt = $db_conn->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $produto_para_editar = $result->fetch_assoc();
    }
}

// --- Cadastro/Edição (INSERT/UPDATE) ---
if (isset($_POST['cadastrar']) || isset($_POST['editar'])) {

    $id = isset($_POST['id_produto']) ? intval($_POST['id_produto']) : null;
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'] ?? '';
    $preco = floatval($_POST['preco']);
    $estoque = intval($_POST['estoque']);
    $categoria = $_POST['categoria'];
    $em_oferta = isset($_POST['em_oferta']) ? 1 : 0;
    $mostrar_em_produtos = isset($_POST['mostrar_em_produtos']) ? 1 : 0;

    $is_update = isset($_POST['editar']) && $id !== null;
    $imagem_query_part = "";
    $upload_path = null;
    $image_upload_success = true;
    $pasta = "imagens/";

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $arquivo = $_FILES['imagem'];
        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $novoNome = uniqid() . "." . $extensao;
        $upload_path = $pasta . $novoNome;

        if (move_uploaded_file($arquivo["tmp_name"], $upload_path)) {
            $imagem_query_part = ", imagem = ?";
        } else {
            $image_upload_success = false;
            header("Location: admin.php?status=error&msg=" . urlencode("Erro ao fazer upload da imagem."));
            exit();
        }
    } elseif (!$is_update) {
        header("Location: admin.php?status=error&msg=" . urlencode("A imagem é obrigatória para o cadastro de um novo produto."));
        exit();
    }

    if ($image_upload_success) {
        if ($is_update) {

            $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria = ?, em_oferta = ?, mostrar_em_produtos = ? {$imagem_query_part} WHERE id = ?";
            $stmt = $db_conn->prepare($sql);

            if ($imagem_query_part) {
                $stmt->bind_param("ssdissiis", $nome, $descricao, $preco, $estoque, $categoria, $em_oferta, $mostrar_em_produtos, $upload_path, $id);
            } else {
                $stmt->bind_param("ssdissii", $nome, $descricao, $preco, $estoque, $categoria, $em_oferta, $mostrar_em_produtos, $id);
            }

            if ($stmt->execute()) {
                if ($imagem_query_part && $produto_para_editar && $produto_para_editar['imagem'] && file_exists($produto_para_editar['imagem'])) {
                    unlink($produto_para_editar['imagem']);
                }
                header("Location: admin.php?status=updated");
            } else {
                header("Location: admin.php?status=error&msg=Erro ao atualizar produto: " . urlencode($stmt->error));
            }

        } else {
            $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem, categoria, em_oferta, mostrar_em_produtos, data_cadstro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $db_conn->prepare($sql);
            $stmt->bind_param("ssdissii", $nome, $descricao, $preco, $estoque, $upload_path, $categoria, $em_oferta, $mostrar_em_produtos);

            if ($stmt->execute()) {
                header("Location: admin.php?status=inserted");
            } else {
                header("Location: admin.php?status=error&msg=Erro ao cadastrar produto: " . urlencode($stmt->error));
            }
        }
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - D&D-Supermercado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmarExclusao(id) {
            if (confirm("Tem certeza que deseja deletar este produto (ID: " + id + ")? Esta ação é irreversível.")) {
                window.location.href = 'admin.php?deletar=' + id + '<?php echo $categoria_selecionada !== 'todos' ? "&categoria={$categoria_selecionada}" : ""; ?>';
            }
        }
    </script>
</head>

<body class="bg-light">

    <nav class="navbar py-2 mb-4" style="background-color: var(--primaria, #be5c00);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="admin.php">
                <img src="imgs/Logo_D_D2.png" alt="Logo D&D" height="60" class="me-2">
                <span class="text-white">Painel Administrativo</span>
            </a>

            <div class="d-flex align-items-center">
                <a href="index.php" class="btn btn-light fw-bold">
                    <span class="d-none d-sm-inline">Ver Site</span>
                    <i class="bi bi-eye-fill d-sm-none"></i>
                </a>
            </div>

        </div>
    </nav>

    <div class="container">
        <div class="row">

            <div class="col-12 col-md-4 mb-4">
                <div class="card shadow">
                    <div class="card-header text-white" style="background-color: var(--primaria, #be5c00);">
                        <h5><?php echo $produto_para_editar ? 'Editar Produto (ID: ' . $produto_para_editar['id'] . ')' : 'Novo Produto'; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">

                            <?php if ($produto_para_editar): ?>
                                <input type="hidden" name="id_produto" value="<?php echo $produto_para_editar['id']; ?>">
                                <input type="hidden" name="editar" value="1">
                            <?php else: ?>
                                <input type="hidden" name="cadastrar" value="1">
                            <?php endif; ?>

                            <div class="mb-2">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control"
                                    value="<?php echo $produto_para_editar ? htmlspecialchars($produto_para_editar['nome']) : ''; ?>"
                                    required>
                            </div>
                            <div class="mb-2">
                                <label>Categoria</label>
                                <select name="categoria" class="form-select" required>
                                    <?php
                                    $categoria_atual = $produto_para_editar ? $produto_para_editar['categoria'] : '';
                                    foreach ($categorias as $value => $label) {
                                        $selected = ($categoria_atual === $value) ? 'selected' : '';
                                        echo "<option value='{$value}' {$selected}>{$label}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>Descrição (Opcional)</label>
                                <textarea name="descricao" class="form-control"
                                    rows="2"><?php echo $produto_para_editar ? htmlspecialchars($produto_para_editar['descricao']) : ''; ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>Preço (R$)</label>
                                    <input type="number" name="preco" class="form-control" step="0.01"
                                        value="<?php echo $produto_para_editar ? htmlspecialchars($produto_para_editar['preco']) : ''; ?>"
                                        required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label>Estoque</label>
                                    <input type="number" name="estoque" class="form-control" min="0"
                                        value="<?php echo $produto_para_editar ? htmlspecialchars($produto_para_editar['estoque']) : '0'; ?>"
                                        required>
                                </div>
                            </div>
                            <div class="mb-2 form-check">
                                <input type="checkbox" name="em_oferta" class="form-check-input" id="checkOferta" <?php echo ($produto_para_editar && $produto_para_editar['em_oferta'] == 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="checkOferta">Marcar como Oferta</label>
                            </div>
                            <div class="mb-2 form-check">
                                <input type="checkbox" name="mostrar_em_produtos" class="form-check-input"
                                    id="checkProdutos" <?php echo ($produto_para_editar && $produto_para_editar['mostrar_em_produtos'] == 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="checkProdutos">Colocar em Produtos</label>
                            </div>

                            <div class="mb-3">
                                <label>Foto
                                    <?php echo $produto_para_editar ? '(Mantenha vazio para não alterar)' : ''; ?></label>
                                <input type="file" name="imagem" class="form-control" <?php echo $produto_para_editar ? '' : 'required'; ?>>
                                <?php if ($produto_para_editar && $produto_para_editar['imagem']): ?>
                                    <small class="form-text text-muted">Imagem atual:</small>
                                    <img src="<?php echo htmlspecialchars($produto_para_editar['imagem']); ?>" width="50"
                                        class="img-thumbnail mt-1">
                                <?php endif; ?>
                            </div>

                            <button type="submit"
                                class="btn btn-<?php echo $produto_para_editar ? 'warning' : 'success'; ?> w-100">
                                <?php echo $produto_para_editar ? '<i class="bi bi-floppy-fill me-1"></i> Salvar Edição' : '<i class="bi bi-plus-circle-fill me-1"></i> Cadastrar'; ?>
                            </button>

                            <?php if (isset($_GET['editar'])): ?>
                                <div class="d-flex gap-2 mt-2">
                                    <a href="admin.php" class="btn btn-secondary flex-grow-1">
                                        <i class="bi bi-x-circle-fill me-1"></i> Cancelar
                                    </a>
                                    <button type="button"
                                        onclick="confirmarExclusao(<?php echo $produto_para_editar['id']; ?>)"
                                        class="btn btn-danger flex-grow-1">
                                        <i class="bi bi-trash-fill me-1"></i> Deletar
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5>Produtos Cadastrados</h5>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-filter"></i> Filtrar: <?php echo $titulo_filtro; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">Opções de Visualização</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item <?php echo ($categoria_selecionada === 'todos') ? 'active' : ''; ?>"
                                        href="admin.php?categoria=todos">
                                        <i class="bi bi-list-ul me-2"></i> Todos os Produtos
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <h6 class="dropdown-header">Por Categoria</h6>
                                </li>

                                <?php foreach ($categorias as $key => $label): ?>
                                    <li>
                                        <a class="dropdown-item <?php echo ($categoria_selecionada === $key) ? 'active' : ''; ?>"
                                            href="admin.php?categoria=<?php echo $key; ?>">
                                            <?php echo $label; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;" class="text-center">ID</th>
                                        <th style="width: 60px;" class="text-center">Foto</th>
                                        <th style="max-width: 120px;">Nome</th>
                                        <th style="width: 50px;" class="text-center">Estoque</th>
                                        <th style="min-width: 80px;" class="text-center">Oferta</th>
                                        <th style="min-width: 70px;" class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_list = "SELECT id, nome, imagem, estoque, em_oferta FROM produtos {$sql_where} ORDER BY id DESC";
                                    $res = $db_conn->query($sql_list);

                                    if ($res->num_rows > 0) {
                                        while ($row = $res->fetch_assoc()) {
                                            $is_oferta = $row['em_oferta'] == 1;
                                            $oferta_badge_class = $is_oferta ? 'bg-danger' : 'bg-secondary';
                                            $oferta_badge_text = $is_oferta ? 'Em Oferta' : 'Normal';

                                            echo "<tr>";
                                            echo "<td class='small text-center'>" . $row['id'] . "</td>";
                                            echo "<td class='text-center'><img src='" . htmlspecialchars($row['imagem']) . "' width='40' class='img-thumbnail'></td>";
                                            echo "<td class='fw-bold text-truncate' style='max-width: 120px;'>" . htmlspecialchars($row['nome']) . "</td>";
                                            echo "<td class='text-center'>" . $row['estoque'] . "</td>";
                                            echo "<td style='min-width: 80px;' class='text-center'>
                                                <span class='badge {$oferta_badge_class}'>{$oferta_badge_text}</span>
                                            </td>";
                                            echo "<td style='min-width: 70px;' class='text-center'>
                                                <a href='admin.php?editar=" . $row['id'] . "' class='btn btn-sm btn-warning' title='Editar Produto'>
                                                    <i class='bi bi-pencil-fill'></i> <span class='d-none d-sm-inline'>Editar</span>
                                                </a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center text-muted py-4">Nenhum produto encontrado nesta categoria.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="statusToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const toast = document.getElementById('statusToast');
            const toastBody = document.getElementById('toastBody');
            let message = '';
            let bgClass = 'bg-success';
            let icon = '<i class="bi bi-check-circle-fill me-2"></i>';
            let redirect = true;

            if (status) {
                switch (status) {
                    case 'inserted':
                        message = 'Produto cadastrado com sucesso!';
                        break;
                    case 'updated':
                        message = 'Produto atualizado com sucesso!';
                        break;
                    case 'deleted':
                        message = 'Produto deletado com sucesso!';
                        break;
                    case 'error':
                        message = decodeURIComponent(urlParams.get('msg'));
                        bgClass = 'bg-danger';
                        icon = '<i class="bi bi-x-octagon-fill me-2"></i>';
                        redirect = false;
                        break;
                    default:
                        return;
                }

                toast.className = 'toast align-items-center text-white border-0 ' + bgClass;
                toastBody.innerHTML = icon + message;

                const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toast);
                toastBootstrap.show();

                if (redirect) {
                    const currentCategory = urlParams.get('categoria');
                    let newUrl = 'admin.php';
                    if (currentCategory && currentCategory !== 'todos') {
                        newUrl += `?categoria=${currentCategory}`;
                    }
                    setTimeout(() => {
                        window.history.replaceState({}, document.title, newUrl);
                    }, 50);
                }
            }
        });
    </script>
</body>

</html>