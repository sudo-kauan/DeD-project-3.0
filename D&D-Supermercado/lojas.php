<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nossas Lojas - D&D Supermercado</title>
    <link rel="icon" type="image/png" href="imgs/Logo_D_D2.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        #lojas-titulo {
            position: relative;
            display: inline-block;
            margin-bottom: 40px;
        }

        #lojas-titulo::after {
            content: "";
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -8px;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primaria), var(--secundaria));
            border-radius: 2px;
            animation: underlineGrow 900ms 250ms forwards;
        }

        @keyframes underlineGrow {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }

        .btn-mapa {
            background-color: #ff6600;
            color: white;
            font-weight: bold;
            border: none;
            transition: background 0.3s;
        }

        .btn-mapa:hover {
            background-color: #cc5200;
            color: white;
        }

        /* Estilo para links de rodapé (garante que <p> tenha margem controlada) */
        .texto-links {
            font-size: 0.95rem;
            /* Opcional: ajustar tamanho para melhor leitura no rodapé */
            line-height: 1.2;
        }
    </style>
</head>

<body>

    <header class="py-2" style="background-color: var(--primaria, #be5c00);">
        <div class="container">
            <div class="row align-items-center g-2 gx-0">
                <div class="col-6 col-md-2 d-flex align-items-center">
                    <button class="btn fw-bold text-white d-none d-md-block" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#categoriesOffcanvas"
                        style="background-color: var(--secundaria, #c47f00); border-color: var(--secundaria, #c47f00);">
                        <i class="fa-solid fa-list-ul fa-xl"></i>
                    </button>
                    <button class="btn text-white d-md-none p-0 me-3" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#categoriesOffcanvas"
                        style="background-color: var(--secundaria, #c47f00); border: none;">
                        <i class="fa-solid fa-bars fa-xl"></i>
                    </button>
                    <a href="index.php" class="logo text-decoration-none text-white fw-bold mx-auto mx-md-0">
                        <img src="imgs/Logo_D_D2.png" alt="Logo do Projeto D & D" height="90">
                    </a>
                </div>
                <div class="col-12 col-md-6 order-3 order-md-2">
                    <form class="d-flex" role="search" action="index.php">
                        <div class="input-group">
                            <input type="search" name="q" class="form-control" placeholder="Buscar itens...">
                            <button type="submit" class="btn text-white fw-bold"
                                style="background-color: var(--secundaria, #c47f00); border:none;">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-6 col-md-4 order-2 order-md-3 d-flex justify-content-end align-items-center gap-2 gap-md-3">
                    <nav class="actions">
                        <a href="#" class="action text-white text-decoration-none me-2 me-md-3" data-bs-toggle="modal"
                            data-bs-target="#cepModal">
                            <i class="fa-solid fa-truck fa-lg"></i> <span class="d-none d-sm-inline">Informe seu
                                CEP</span>
                        </a>
                        <?php if (isset($_SESSION['usuario_login'])): ?>
                            <div class="dropdown d-inline-block me-2">
                                <a href="#" class="action text-white text-decoration-none dropdown-toggle" role="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user fa-lg"></i> <span class="d-none d-sm-inline">Minha
                                        Conta</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <h6 class="dropdown-header">
                                            <?php echo htmlspecialchars($_SESSION['usuario_login']); ?>
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="#" class="action text-white text-decoration-none me-2" data-bs-toggle="modal"
                                data-bs-target="#loginRegisterModal">
                                <i class="fa-solid fa-user fa-lg d-inline d-sm-none"></i> <span
                                    class="d-none d-sm-inline">Entre/Cadastre-se</span>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="categoriesOffcanvas">
        <div class="offcanvas-header" style="background-color: var(--primaria, #be5c00);">
            <h5 class="offcanvas-title text-white">Menu</h5>
            <button type="button" class="btn-close text-reset bg-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="list-group list-group-flush border-bottom">
                <a href="index.php" class="list-group-item list-group-item-action fw-bold"><i
                        class="fa-solid fa-house me-2"></i> Início</a>
                <a href="lojas.php" class="list-group-item list-group-item-action fw-bold active"><i
                        class="fa-solid fa-shop me-2"></i> Lojas</a>
                <a href="sobre_nos.php" class="list-group-item list-group-item-action fw-bold"><i
                        class="fa-solid fa-users me-2"></i> Sobre Nós</a>
            </div>
            <div class="p-3">
                <h6 class="text-uppercase text-muted fw-bold">Categorias</h6>
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action">Açougue</a>
                    <a href="#" class="list-group-item list-group-item-action">Hortifrúti</a>
                    <a href="#" class="list-group-item list-group-item-action">Bebidas</a>
                    <a href="#" class="list-group-item list-group-item-action">Limpeza</a>
                    <a href="#" class="list-group-item list-group-item-action">Mercearia</a>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-shrink-0 mb-5">
        <section class="container mt-5">
            <div class="text-center">
                <h2 id="lojas-titulo" class="display-6 fw-bold">Nossas Lojas</h2>
                <p class="text-muted">Encontre a unidade D&D mais próxima de você.</p>
            </div>

            <div class="row g-4 justify-content-center mt-3">

                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 shadow border-0 overflow-hidden">
                        <div style="height: 250px; overflow: hidden;">
                            <img src="imgs/loja.jpg" class="card-img-top w-100 h-100 object-fit-cover" alt="Loja 1"
                                style="object-fit: cover;">
                        </div>
                        <div class="card-body d-flex flex-column text-center">
                            <h3 class="card-title fw-bold" style="color: var(--primaria);">Loja 1</h3>
                            <hr class="w-25 mx-auto my-3" style="border-color: var(--primaria);">

                            <p class="card-text mb-2">
                                <i class="fa-solid fa-location-dot text-danger me-2"></i>
                                <strong>Endereço:</strong><br>
                                ANTONIO NERI LOPES - R. 10 B, 730<br>
                                Banespinha, Guaíra - SP, 14790-000
                            </p>

                            <p class="card-text mt-3">
                                <i class="fa-solid fa-phone text-success me-2"></i>
                                <strong>Telefone:</strong> (17) 3331-5533
                            </p>

                            <a href="https://maps.app.goo.gl/dLap5P5jYePKxtrF8" target="_blank"
                                class="btn btn-mapa w-100 mt-auto py-2">
                                <i class="fa-solid fa-map-location-dot me-2"></i> Ver no Mapa
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 shadow border-0 overflow-hidden">
                        <div style="height: 250px; overflow: hidden;">
                            <img src="imgs/loja2.png" class="card-img-top w-100 h-100 object-fit-cover" alt="Loja 2"
                                style="object-fit: cover;">
                        </div>
                        <div class="card-body d-flex flex-column text-center">
                            <h3 class="card-title fw-bold" style="color: var(--primaria);">Loja 2</h3>
                            <hr class="w-25 mx-auto my-3" style="border-color: var(--primaria);">

                            <p class="card-text mb-2">
                                <i class="fa-solid fa-location-dot text-danger me-2"></i>
                                <strong>Endereço:</strong><br>
                                Rua 2, 1745 - JARDIM ELDORADO<br>
                                Guaíra - SP, 14790-000
                            </p>

                            <p class="card-text mt-3">
                                <i class="fa-solid fa-phone text-success me-2"></i>
                                <strong>Telefone:</strong> (17) 99972-5533
                            </p>

                            <a href="https://maps.app.goo.gl/6dV1PWDvCCYAnUVP6" target="_blank"
                                class="btn btn-mapa w-100 mt-auto py-2">
                                <i class="fa-solid fa-map-location-dot me-2"></i> Ver no Mapa
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>

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

        <footer class="footer bg-light text-center py-3">
            <p class="mb-0 text-muted small">© 2025 D&D Supermercado.</p>
        </footer>
    </main>

    <div class="modal fade" id="loginRegisterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">Login...</div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cepModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">CEP...</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>