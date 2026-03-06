<?php
session_start();
include 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&D-Supermercado</title>
    <link rel="icon" type="image/png" href="imgs/Logo_D_D2.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!--Header-->
    <header class="py-2" style="background-color: var(--primaria, #be5c00);">
        <div class="container">
            <div class="row align-items-center g-2 gx-0">

                <div class="col-6 col-md-2 d-flex align-items-center">

                    <button class="btn fw-bold text-white d-none d-md-block" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#categoriesOffcanvas" aria-controls="categoriesOffcanvas"
                        style="background-color: var(--secundaria, #c47f00); border-color: var(--secundaria, #c47f00);">
                        <i class="fa-solid fa-list-ul fa-xl"></i>
                    </button>

                    <button class="btn text-white d-md-none p-0 me-3" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#categoriesOffcanvas" aria-controls="categoriesOffcanvas"
                        style="background-color: var(--secundaria, #c47f00); border: none;">
                        <i class="fa-solid fa-bars fa-xl"></i>
                    </button>

                    <a href="index.php" class="logo text-decoration-none text-white fw-bold mx-auto mx-md-0">
                        <img src="imgs/Logo_D_D2.png" alt="Logo do Projeto D & D" height="90">
                    </a>
                </div>

                <div class="col-12 col-md-6 order-3 order-md-2">
                    <form class="d-flex" role="search" action="#" method="get">
                        <div class="input-group">
                            <input type="search" name="q" class="form-control" placeholder="Buscar itens..."
                                aria-label="Buscar itens">

                            <button type="submit" class="btn text-white fw-bold"
                                style="background-color: var(--secundaria, #c47f00); border:none;">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div
                    class="col-6 col-md-4 order-2 order-md-3 d-flex justify-content-end align-items-center gap-2 gap-md-3">
                    <nav class="actions" aria-label="Ações do usuário">

                        <a href="#" class="action text-white text-decoration-none me-2 me-md-3" data-bs-toggle="modal"
                            data-bs-target="#cepModal" title="Informe seu CEP">
                            <i class="fa-solid fa-truck fa-lg"></i>
                            <span class="d-none d-sm-inline">Informe seu CEP</span>
                        </a>

                        <?php
                        // Verifica se o usuário está logado
                        if (isset($_SESSION['usuario_login'])):
                            ?>
                            <div class="dropdown d-inline-block me-2">
                                <a href="#" class="action text-white text-decoration-none dropdown-toggle" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false" title="Minha Conta">
                                    <i class="fa-solid fa-user fa-lg d-inline d-sm-none"></i>
                                    <span class="d-none d-sm-inline">Minha Conta</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <h6 class="dropdown-header">
                                            <?php echo htmlspecialchars($_SESSION['usuario_login']); ?>
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>

                                    <?php
                                    if (isset($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] === 'admin'):
                                        ?>
                                        <li><a class="dropdown-item" href="admin.php"
                                                style="font-weight: bold; color: var(--primaria, #be5c00);">
                                                <i class="fa-solid fa-screwdriver-wrench me-1"></i> Painel Admin
                                            </a></li>
                                    <?php endif; ?>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="#" class="action text-white text-decoration-none me-2" data-bs-toggle="modal"
                                data-bs-target="#loginRegisterModal" title="Entre ou cadastre-se">
                                <i class="fa-solid fa-user fa-lg d-inline d-sm-none"></i>
                                <span class="d-none d-sm-inline">Entre ou cadastre-se</span>
                            </a>
                        <?php endif; ?>
                        <button class="action cart text-white text-decoration-none btn btn-link" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                            Carrinho <span id="cartCount" class="badge bg-light text-dark rounded-pill">0</span>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!--Barra Lateral-->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="categoriesOffcanvas"
        aria-labelledby="categoriesOffcanvasLabel">
        <div class="offcanvas-header" style="background-color: var(--primaria, #be5c00);">
            <h5 class="offcanvas-title text-white" id="categoriesOffcanvasLabel">Navegação Principal</h5>
            <button type="button" class="btn-close text-reset bg-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column justify-content-between">

            <div>
                <div class="list-group list-group-flush">

                    <a href="#collapseCategories" class="list-group-item list-group-item-action fw-bold"
                        data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseCategories">
                        <i class="fa-solid fa-list-ul me-2"></i> Categorias
                    </a>

                    <div class="collapse" id="collapseCategories">
                        <div class="list-group list-group-flush p-0">
                            <a href="#" class="list-group-item list-group-item-action ps-4">Açougue</a>
                            <a href="#" class="list-group-item list-group-item-action ps-4">Bebidas</a>
                            <a href="#" class="list-group-item list-group-item-action ps-4">Bebidas Alcoólicas</a>
                            <a href="#" class="list-group-item list-group-item-action ps-4">Hortifruti</a>
                            <a href="#" class="list-group-item list-group-item-action ps-4">Limpeza</a>
                            <a href="#" class="list-group-item list-group-item-action ps-4">Padaria</a>
                            <a href="#" class="list-group-item list-group-item-action ps-4">Higiene</a>
                            <a href="#" class="list-group-item list-group-item-action ps-4">Frios</a>
                        </div>
                    </div>
                </div>

                <div class="list-group list-group-flush mt-2 bg-light py-1">

                    <a href="#" class="list-group-item list-group-item-action fw-bold bg-light">Ofertas</a>
                    <a href="#" class="list-group-item list-group-item-action fw-bold bg-light">Lançamentos</a>
                    <a href="#" class="list-group-item list-group-item-action bg-light">Ajuda</a>

                    <a href="#" class="list-group-item list-group-item-action bg-light d-none d-md-block">Minhas
                        Listas</a>

                </div>

                <hr class="my-2">

                <h6 class="mt-4 border-top pt-3 d-md-none">Conta</h6>
                <div class="list-group list-group-flush d-md-none">
                    <a href="#" class="list-group-item list-group-item-action">Minhas listas</a>
                </div>
            </div>

            <div class="text-center mt-auto p-3 border-top">
                <img src="imgs/Logo_D_D2.png" alt="Logo do Projeto D & D" height="80">
            </div>
        </div>
    </div>

    <main>
        <!--Carrosel-->
        <div class="container mt-3 mb-4">
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0"
                        class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>

                <div class="carousel-inner"><!--1200px x 500px-->
                    <div class="carousel-item active">
                        <img src="imgs/slide 2.jpeg" class="d-block w-100" alt="promoçao">
                    </div>
                    <div class="carousel-item">
                        <img src="imgs/slide.jpeg" class="d-block w-100" alt="promoçao">
                    </div>
                    <div class="carousel-item">
                        <img src="imgs/slide 3.png" class="d-block w-100" alt="promoçao">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        </div>

        <!--Container texto-->
        <section class="section-second my-4">
            <div class="container">

                <div class="row container-slots g-3 justify-content-center">

                    <div class="col-lg-5 col-12">
                        <div class="slot1"><!--500px x 267px-->
                            <img src="imgs/ca 1.png" class="img-fluid rounded" alt="Imagem de promoção 1">
                        </div>
                    </div>

                    <div class="col-lg-7 col-12">
                        <div class="slot2"><!--700px x 267px-->
                            <img src="imgs/ca 2.png" class="img-fluid rounded" alt="Imagem de promoção 2">
                        </div>
                    </div>

                </div>

                <div class="row container-second-section-bar mt-4">
                    <div class="col-12">
                        <div class="second-section-bar d-flex justify-content-center flex-wrap p-3 rounded gap-3"
                            style="background-color: var(--claro, #f8f9fa);">

                            <div class="p-2">🚛 <strong>Frete grátis</strong> compras acima de R$100</div>
                            <div class="p-2">⭐ <strong>Clube D&D</strong> com vantagens</div>
                            <div class="p-2">🛍️ <strong>Compre e Retire</strong> na loja mais próxima</div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!--Categorias-->
        <section class="section-third text-center my-5">

            <h2 id="categoria-titulo" class="display-6 mb-4">Compre por Categoria</h2>

            <div class="container">
                <div class="row container-categories g-3 justify-content-center">

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 1.png" class="img-category img-fluid" alt="Ícone Categoria 1">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 2.png" class="img-category img-fluid" alt="Ícone Categoria 2">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 3.png" class="img-category img-fluid" alt="Ícone Categoria 3">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 4.png" class="img-category img-fluid" alt="Ícone Categoria 4">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 5.png" class="img-category img-fluid" alt="Ícone Categoria 5">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 6.png" class="img-category img-fluid" alt="Ícone Categoria 6">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 7.png" class="img-category img-fluid" alt="Ícone Categoria 7">
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 col-xl-1">
                        <div class="category p-3 rounded-3 shadow-sm">
                            <img src="imgs/categoria 8.png" class="img-category img-fluid" alt="Ícone Categoria 8">
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!--Produtos-->

        <?php
        ?>

        <section class="section-fourth text-center my-5">

            <h2 id="produtos-titulo" class="display-6 mb-4">Produtos em Oferta</h2>

            <div class="container produtos p-3 p-md-4 rounded-3 shadow-sm"
                style="background-color: var(--claro, #f8f9fa);">

                <div class="row g-3 justify-content-center">

                    <?php
                    $db_conn = $con;

                    $sql_ofertas = "SELECT id, nome, preco, imagem, descricao FROM produtos WHERE em_oferta = 1 ORDER BY id DESC LIMIT 12";
                    $res_ofertas = $db_conn->query($sql_ofertas);

                    if ($res_ofertas && $res_ofertas->num_rows > 0) {
                        while ($produto = $res_ofertas->fetch_assoc()) {
                            $preco_formatado = number_format($produto['preco'], 2, ',', '.');

                            $data_id_sanitized = str_replace(' ', '-', strtoupper($produto['nome']));
                            $data_price_raw = $produto['preco'];
                            ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                <div class="card h-100 text-center"
                                    data-id="<?php echo htmlspecialchars($data_id_sanitized); ?>"
                                    data-price="<?php echo htmlspecialchars($data_price_raw); ?>">

                                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                        class="card-img-top mx-auto mt-2"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">

                                    <div class="card-body d-flex flex-column justify-content-between p-2">
                                        <h5 class="card-title fw-bold">
                                            <?php echo htmlspecialchars($produto['nome']); ?>
                                        </h5>
                                        <p class="card-text text-muted small mb-1">
                                            <?php echo htmlspecialchars($produto['descricao']); ?>
                                        </p>
                                        <p class="card-text mb-2 fs-5">R$ <?php echo $preco_formatado; ?> un</p>

                                        <button class="btn btn-warning btn-comprar w-100"
                                            data-product-id="<?php echo htmlspecialchars($produto['id']); ?>">
                                            Adicionar ao Carrinho
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-12"><p class="text-muted">Nenhum produto em oferta no momento.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!--Todos os Produtos-->
        <section class="section-fourth text-center my-5">

            <h2 id="produtos-titulo" class="display-6 mb-4">Todos os Produtos</h2>

            <div class="container produtos p-3 p-md-4 rounded-3 shadow-sm"
                style="background-color: var(--claro, #f8f9fa);">

                <div class="row g-3 justify-content-center">

                    <?php
                    $db_conn = $con;

                    $sql_ofertas = "SELECT id, nome, preco, imagem, descricao FROM produtos WHERE em_oferta = 1 ORDER BY id DESC LIMIT 12";
                    $res_ofertas = $db_conn->query($sql_ofertas);

                    if ($res_ofertas && $res_ofertas->num_rows > 0) {
                        while ($produto = $res_ofertas->fetch_assoc()) {
                            $preco_formatado = number_format($produto['preco'], 2, ',', '.');

                            $data_id_sanitized = str_replace(' ', '-', strtoupper($produto['nome']));
                            $data_price_raw = $produto['preco'];
                            ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                <div class="card h-100 text-center"
                                    data-id="<?php echo htmlspecialchars($data_id_sanitized); ?>"
                                    data-price="<?php echo htmlspecialchars($data_price_raw); ?>">

                                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                        class="card-img-top mx-auto mt-2"
                                        alt="<?php echo htmlspecialchars($produto['nome']); ?>">

                                    <div class="card-body d-flex flex-column justify-content-between p-2">
                                        <h5 class="card-title fw-bold">
                                            <?php echo htmlspecialchars($produto['nome']); ?>
                                        </h5>
                                        <p class="card-text text-muted small mb-1">
                                            <?php echo htmlspecialchars($produto['descricao']); ?>
                                        </p>
                                        <p class="card-text mb-2 fs-5">R$ <?php echo $preco_formatado; ?> un</p>

                                        <button class="btn btn-warning btn-comprar w-100"
                                            data-product-id="<?php echo htmlspecialchars($produto['id']); ?>">
                                            Adicionar ao Carrinho
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-12"><p class="text-muted">Nenhum produto em oferta no momento.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!--Cadastro Receber Ofertas-->
        <div class="notify bg-light w-100 py-3 mt-4">
            <div class="container d-flex align-items-center justify-content-center flex-wrap gap-3">

                <img src="imgs/cesta.png" width="100px" class="img-notify rounded-circle">

                <div class="container-titulo-notify me-lg-3">
                    <div class="titulo-notify fw-bold fs-6">Receba Ofertas e descontos exclusivos do nosso site!</div>
                    <p class="p text-muted small mb-0">Ao cadastrar-se você concorda em receber notificação sobre
                        produtos deste site</p>
                </div>

                <input type="text" class="form-control w-auto" style="min-width: 180px;" placeholder="Digite seu nome">
                <input type="email" class="form-control w-auto" style="min-width: 180px;"
                    placeholder="Digite seu e-mail">
                <button type="submit" class="btn btn-primary btn-cadastrar-notify">CADASTRAR</button>

            </div>
        </div>

        <!--Links/Ajuda-->
        <div class="links container-fluid py-4">
            <div class="container">
                <div class="row justify-content-between gy-4">

                    <div class="col-12 col-md-3 col-lg-2 d-flex flex-column align-items-start">
                        <img src="imgs/Logo_D_D2.png" width="150px" class="mb-3">
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <h4 class="fw-bold fs-5">Institucional</h4>
                        <a href="lojas.php" class="texto-links mt-2 mb-1 text-decoration-none text-dark d-block">Lojas
                            Físicas</a>
                        <p class="texto-links mb-1">Clube D&D</p>
                        <p class="texto-links mb-1">Trabalhe Conosco</p>
                        <a href="sobre_nos.php" class="texto-links mb-1 text-decoration-none text-dark d-block">Sobre
                            Nós</a>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <h4 class="fw-bold fs-5">Ajuda</h4>
                        <a href="perfil.php" class="texto-links mt-2 mb-1 text-decoration-none text-dark d-block">Minha
                            Conta</a>
                        <p class="texto-links mb-1">Meus Pedidos</p>
                        <p class="texto-links mb-1">Frete e Entregas</p>
                        <p class="texto-links mb-1">Fale conosco</p>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <h4 class="fw-bold fs-5">Atendimento</h4>
                        <p class="texto-links mt-2 mb-1">Semana toda / 6h às 21h</p>
                        <p class="texto-links mb-1">+55 (DD) XXXXX-XXXX</p>
                        <p class="texto-links mb-1">dedsupermercados@gmail.com</p>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2 d-flex flex-column justify-content-start">
                        <div class="links-sociais d-flex gap-2 mt-3">
                            <div class="container-img-sociais">
                                <img src="imgs/facebook.jpeg" width="24px" height="24px" class="img-socias">
                            </div>
                            <div class="container-img-sociais">
                                <img src="imgs/WhatsApp.jpeg" width="24px" height="24px" class="img-socias">
                            </div>
                            <div class="container-img-sociais">
                                <img src="imgs/instagram.jpeg" width="24px" height="24px" class="img-socias">
                            </div>
                            <div class="container-img-sociais">
                                <img src="imgs/x.jpeg" width="24px" height="24px" class="img-socias">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!--Footer-->
        <footer class="footer bg-light text-center py-3">
            <p class="mb-0 text-muted small">&copy; 2025 D&D Supermercado. Todos os direitos reservados.</p>
        </footer>

    </main>

    <!--Login-->
    <div class="modal fade" id="loginRegisterModal" tabindex="-1" aria-labelledby="loginRegisterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header" style="background-color: var(--primaria, #be5c00); color: white;">
                    <h5 class="modal-title" id="loginRegisterModalLabel">Faça Login</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="valida.php" method="POST">

                        <div class="mb-3">
                            <label for="emailInput" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailInput" name="email"
                                placeholder="seu-email@exemplo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordInput" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="passwordInput" name="senha" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"
                            style="background-color: var(--primaria, #be5c00); border-color: var(--primaria, #be5c00);">Entrar</button>

                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <a href="#">Esqueci a senha</a>
                    <span>|</span>

                    <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal"
                        style="text-decoration: none;">
                        Criar uma conta
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!--Cadastre-se-->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header" style="background-color: var(--primaria, #be5c00); color: white;">
                    <h5 class="modal-title" id="registerModalLabel">Criar sua Conta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <form id="registerForm" action="envio.php" method="POST">

                        <div class="mb-3">
                            <label for="registerName" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="registerName" name="nome" required>
                        </div>

                        <div class="mb-3">
                            <label for="registerBirthDate" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="registerBirthDate" name="data_nasc" required>
                        </div>

                        <div class="mb-3">
                            <label for="registerPhone" class="form-label">Telefone / Celular</label>
                            <input type="tel" class="form-control" id="registerPhone" name="telefone" required
                                placeholder="(XX) XXXXX-XXXX" maxlength="15">
                        </div>

                        <div class="mb-3">
                            <label for="registerGender" class="form-label">Sexo</label>
                            <select class="form-select" id="registerGender" name="sexo" required>
                                <option value="" selected disabled>Selecione uma opção...</option>
                                <option value="F">Feminino</option>
                                <option value="M">Masculino</option>
                                <option value="O">Outro</option>
                                <option value="N">Prefiro não informar</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required
                                placeholder="seu-email@exemplo.com">
                        </div>

                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="registerPassword" name="senha" minlength="6"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="registerConfirmPassword" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="registerConfirmPassword"
                                name="confirma_senha" minlength="6" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="termsCheck" required>
                            <label class="form-check-label" for="termsCheck">
                                Eu concordo com os <a href="#">Termos de Serviço</a>.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3"
                            style="background-color: var(--primaria, #be5c00); border-color: var(--primaria, #be5c00);">Cadastrar</button>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <p class="mb-0">
                        Já tem uma conta?
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginRegisterModal" data-bs-dismiss="modal"
                            class="text-decoration-none">
                            Faça Login aqui.
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!--Informe seu CEP-->
    <div class="modal fade" id="cepModal" tabindex="-1" aria-labelledby="cepModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <div class="modal-header" style="background-color: var(--primaria, #be5c00); color: white;">
                    <h5 class="modal-title" id="cepModalLabel">Localização</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <p>Digite seu CEP para verificarmos a disponibilidade e o prazo de entrega.</p>

                    <form id="cepForm">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="fa-solid fa-truck"></i>
                            </span>
                            <input type="text" class="form-control" id="cepInput" placeholder="00000-000" maxlength="9"
                                required>
                        </div>
                        <small id="cepHelp" class="form-text text-muted">Apenas números ou com hífen (Ex:
                            00000-000).</small>
                    </form>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="submit" form="cepForm" class="btn btn-warning w-100">
                        Consultar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!--Carrinho-->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="cartOffcanvasLabel"><i class="fa-solid fa-cart-shopping me-2"></i> Meu
                Carrinho</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div id="cartList" class="cart-items flex-grow-1 overflow-auto">
                <p class="cart-empty text-center text-muted">Seu carrinho está vazio</p>
            </div>

            <div class="cart-footer mt-3">
                <h5 class="mb-0 fw-bold">Total: R$ <span id="cartTotal">0,00</span></h5>
                <button class="btn btn-success btn-checkout" id="checkoutBtn">Finalizar Compra</button>
            </div>
        </div>
    </div>

    <!--Toast (Notificação de adicionado ao carrinho)-->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa-solid fa-check-circle me-2"></i> Produto adicionado ao carrinho!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="script.js"></script>

</body>

</html>