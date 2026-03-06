<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - D&D Supermercado</title>
    <link rel="icon" type="image/png" href="imgs/Logo_D_D2.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        .sobre-nos-banner {
            /* *** MUDAR AQUI PARA O CAMINHO DA SUA IMAGEM ANTIGA *** */
            background-image: url('imgs/fundo.jpg');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            /* Deixando a imagem mais transparente */
            background-color: rgba(255, 255, 255, 0.4);
            background-blend-mode: overlay;

            min-height: 550px;
            padding: 40px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .sobre-nos-caixa {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 50px;
            width: 90%;
            max-width: 1000px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .texto-sobre-box p {
            text-align: justify;
            font-size: 1.05rem;
            line-height: 1.8;
            color: #444;
            margin-bottom: 1.5rem;
        }

        #sobre-titulo {
            position: relative;
            display: inline-block;
            margin-bottom: 25px !important;
        }

        #sobre-titulo::after {
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

        @media (max-width: 768px) {
            .sobre-nos-banner {
                min-height: initial;
            }

            .sobre-nos-caixa {
                padding: 30px;
            }

            .texto-sobre-box p {
                font-size: 1rem;
                text-align: left;
            }

            .notify .form-control {
                width: 100% !important;
            }
        }

        /* Estilo para links de rodapé (garante que <p> tenha margem controlada) */
        .texto-links {
            font-size: 0.95rem;
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
                        data-bs-target="#categoriesOffcanvas">
                        <i class="fa-solid fa-list-ul fa-xl"></i>
                    </button>
                    <button class="btn text-white d-md-none p-0 me-3" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#categoriesOffcanvas">
                        <i class="fa-solid fa-bars fa-xl"></i>
                    </button>
                    <a href="index.php" class="logo text-decoration-none text-white fw-bold mx-auto mx-md-0">
                        <img src="imgs/Logo_D_D2.png" alt="Logo" height="90">
                    </a>
                </div>
                <div class="col-12 col-md-6 order-3 order-md-2">
                    <form class="d-flex" role="search" action="index.php">
                        <div class="input-group">
                            <input type="search" name="q" class="form-control" placeholder="Buscar..."
                                aria-label="Buscar">
                            <button type="submit" class="btn text-white fw-bold"
                                style="background-color: var(--secundaria, #c47f00); border:none;">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-6 col-md-4 order-2 order-md-3 d-flex justify-content-end align-items-center gap-2">
                    <nav class="actions">
                        <a href="#" class="action text-white text-decoration-none me-2" data-bs-toggle="modal"
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
                <a href="lojas.php" class="list-group-item list-group-item-action fw-bold"><i
                        class="fa-solid fa-shop me-2"></i> Lojas</a>
                <a href="sobre_nos.php" class="list-group-item list-group-item-action fw-bold active"><i
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

        <section class="sobre-nos-banner">
            <div class="sobre-nos-caixa container">
                <h1 class="display-5 fw-bold" id="sobre-titulo">Sobre Nós</h1>

                <div class="texto-sobre-box">
                    <p>No <strong>Supermercado D&D</strong>, acreditamos que comprar bem vai muito além de
                        encher o carrinho — trata-se de viver uma experiência agradável, prática e acolhedora.
                        Desde os nossos primeiros passos, temos um propósito claro: oferecer qualidade em cada
                        detalhe, desde os produtos até o atendimento. Trabalhamos todos os dias com dedicação
                        para construir um ambiente onde você se sinta à vontade, valorizado e bem atendido.</p>

                    <p>Nossa missão está firmemente enraizada no compromisso com a comunidade. Buscamos entender
                        as reais necessidades dos nossos clientes e, por isso, investimos em uma ampla variedade
                        de produtos, prezando sempre pela procedência, frescor e diversidade. Valorizamos os
                        produtores locais, incentivamos a economia regional e escolhemos nossos fornecedores com
                        responsabilidade, priorizando parcerias éticas e duradouras.</p>

                    <p>Sabemos que preço justo faz diferença na sua rotina. Por isso, mantemos uma política de
                        preços transparente, equilibrando qualidade e economia para garantir o melhor
                        custo-benefício para você e sua família. Além disso, estamos constantemente inovando,
                        modernizando nossos processos e acompanhando as tendências do mercado para oferecer
                        serviços ainda mais eficientes, rápidos e personalizados.</p>

                    <p>Seja para a compra do dia a dia, aquela refeição especial de domingo ou uma emergência de
                        última hora, estamos aqui para facilitar a sua vida. Mais do que clientes, vemos nossos
                        visitantes como vizinhos e amigos — e cada visita é uma oportunidade de fortalecer essa
                        relação de confiança, respeito e proximidade.</p>

                    <p class="fw-bold text-center mt-4 fs-5" style="color: var(--primaria); text-align: center;">No
                        Supermercado
                        D&D, você é sempre bem-vindo. Conte conosco!</p>
                </div>

            </div>
        </section>

        <div class="notify bg-light w-100 py-3 mt-5">
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
                <button type="submit" class="btn btn-primary btn-cadastrar-notify text-white"
                    style="background-color: var(--primaria, #be5c00); border-color: var(--primaria, #be5c00);">CADASTRAR</button>

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
                <div class="modal-body">Formulário de Login...</div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cepModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">Consultar CEP...</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>

</html>