<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <!-- TAG's essenciais para tratar a responsividade em diferentes aparelhos  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torre Forte Garage - <?= $titulo; ?> </title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body class="body_cadastro page-editar-cadastro">

    <?php require_once('template/header.php') ?>

    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <section class="editar-container">
        <h2>EDITAR CADASTRO</h2>

        <form class="editar-form" method="post" action="<?= URL_BASE ?>index.php?url=editarcadastro/salvar">
            <?php if (!empty($dados['clientes'])): ?>
                <label for="nome_cliente">Nome Completo:</label>
                <input type="text" name="nome_cliente" id="nome_cliente" value="<?= $dados['clientes']['nome_cliente']; ?>" placeholder="Nome completo" required>

                <label for="cpf">CPF:</label>
                <input type="text" name="cpf_cliente" id="cpf_cliente" value="<?= $dados['clientes']['cpf_cliente']; ?>" placeholder="000.000.000-00" required>

                <label for="telefone_cliente">Telefone Cliente:</label>
                <input type="tel" name="telefone_cliente" id="telefone_cliente" value="<?= $dados['clientes']['telefone_cliente']; ?>" placeholder="(00) 00000-0000" required>

                <label for="email_cliente">E-mail Cliente:</label>
                <input type="email" name="email_cliente" id="email_cliente" value="<?= $dados['clientes']['email_cliente']; ?>" placeholder="E-mail" required>

                <button class="btn-salvar-alteracoes" type="submit" data-tts="entrar">SALVAR ALTERAÇÕES</button>
            <?php endif; ?>
        </form>
    </section>
    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>