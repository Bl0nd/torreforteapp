<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php') ?>

<body id="index_menu" class="body_menu">

    <?php require_once('template/header.php') ?>

    <!-- Botão voltar -->
    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <main class="main-menu">

        <section class="destaque">
            <h2>MEUS CARROS</h2>

            <div class="destaque__meus-carros">
                <?php if (!empty($dados['veiculos'])): ?>
                    <?php foreach ($dados['veiculos'] as $veiculo): ?>
                        <div class="card-servico">
                            <p><strong>Placa:</strong> <?= $veiculo['placa_veiculo'] ?></p>
                            <p><strong>Modelo:</strong> <?= $veiculo['modelo_veiculo'] ?></p>
                            <p><strong>Cor:</strong> <?= $veiculo['cor_original_veiculo'] ?></p>
                            <p><strong>1º Dono:</strong> <?= strtolower($veiculo['primeiro_dono']) === 'sim' ? 'Sim' : 'Não' ?></p>
                            <p><strong>Status:</strong> <?= $veiculo['status_veiculo'] ?></p>

                            <a href="<?= URL_BASE; ?>index.php?url=editarcarro&id=<?= $veiculo['id_veiculo'] ?>">EDITAR</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum veículo encontrado</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="menu-meus-carros-fixo">
            <a href="index.php?url=cadastrarcarro">CADASTRAR</a>
        </section>

    </main>

    <?php require_once('template/footer.php') ?>
    <script src="assets/js/script.js"></script>

</body>

</html>