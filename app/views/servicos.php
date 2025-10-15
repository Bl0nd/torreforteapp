<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php') ?>

<body class="body_cadastro">

    <?php require_once('template/header.php') ?>

    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <section class="servico">
        <h2>SERVIÇOS</h2>

        <?php if (!empty($dados['servicos'])) : ?>
            <?php foreach ($dados ['servicos'] as $servico) : ?>
                <a href="<?= URL_BASE; ?>index.php?url=detalhe&id=<?= $servico['id_servico'] ?>">
                    <img src="<?= FOTO_BASE . "assets/img/servico/" . $servico['foto_servico'] ?>">
                    <h3><?= $servico['nome_servico'] ?></h3>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Nenhum serviço disponível no momento.</p>
        <?php endif; ?>

    </section>

    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>