<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php') ?>

<body id="index_menu" class="body_menu">

    <?php require_once('template/header.php') ?>

    <main class="main-menu">
        <section class="destaque">
            <h2>SERVIÇOS EM DESTAQUE</h2>

            <div class="carousel-wrapper">
                <div class="destaque__container" id="carouselContainer">
                    <?php if (!empty($servicos)) : ?>
                        <?php foreach ($servicos as $servico) : ?>
                            <div class="card-servico">
                                <a href="index.php?url=servico&id=<?= $servico['id_servico'] ?>">
                                    <img src="<?= FOTO_BASE . "assets/img/servico/" . $servico['foto_servico'] ?>">
                                    <h3><?= $servico['nome_servico'] ?></h3>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="no-services-message">Nenhum serviço disponível no momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="menu-navegacao">
            <ul class="menu-lista">
                <li><a href="index.php?url=agendamento"> AGENDAMENTO</a></li>
                <li><a href="index.php?url=servicos"> SERVIÇOS</a></li>
                <li><a href="index.php?url=editarcadastro"> EDITAR DADOS</a></li>
                <li><a href="index.php?url=veiculo"> MEUS CARROS</a></li>
                <li><a href="index.php?url=historico"> HISTÓRICO</a></li>
                <li><a href="index.php?url=login/logout" class="sair">SAIR</a></li>
            </ul>
        </section>
    </main>

    <?php require_once('template/footer.php') ?>
    <script src="assets/js/script.js"></script>

</body>

</html>