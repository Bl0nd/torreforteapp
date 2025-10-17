<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php') ?>

<body class="body_cadastro">

    <?php require_once('template/header.php') ?>

    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <section class="perfil">
        <h2>PERFIL</h2>

        <div class="foto-container">
            <img id="profile-pic-preview" src="<?= !empty($dados['cliente']['foto_cliente']) ? FOTO_BASE . "upload/cliente/" . $dados['cliente']['foto_cliente'] : FOTO_BASE . "upload/cliente/sem-foto.jpg"; ?>" alt="Foto do Cliente">
        </div>

        <?php if (!empty($dados['cliente'])): ?>
            <div class="info-item">
                <span class="label">Nome:</span>
                <span class="value"><?= $dados['cliente']['nome_cliente']; ?></span>
            </div>
            <div class="info-item">
                <span class="label">CPF:</span>
                <span class="value"><?= $dados['cliente']['cpf_cliente']; ?></span>
            </div>
            <div class="info-item">
                <span class="label">E-mail:</span>
                <span class="value"><?= $dados['cliente']['email_cliente']; ?></span>
            </div>
            <div class="info-item">
                <span class="label">Telefone:</span>
                <span class="value"><?= $dados['cliente']['telefone_cliente']; ?></span>
            </div>
        <?php endif; ?>
        <a class="editar-btn" href="index.php?url=editarCadastro">Editar Dados</a>
    </section>

    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>