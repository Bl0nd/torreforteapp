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

<body class="body_cadastro page-cadastro">

    <?php require_once('template/header.php') ?>

    <section class="cadastro-container">
        <h2>CADASTRO</h2>

        <form class="cadastro-form" method="post" action="<?=URL_BASE;?>index.php?url=cadastro/cadastrar">
            
            <label for="nome">Nome:</label>
            <input type="text" name="nome_cliente" id="nome" placeholder="Nome: " required>

            <label for="email">E-mail:</label>
            <input type="email" name="email_cliente" id="email" placeholder="E-mail:" required>

            <label for="telefone">Telefone:</label>
            <input type="tel" name="telefone_cliente" id="telefone" placeholder="(00) 00000-0000)" required>

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf_cliente" id="cpf" placeholder="000.000.000-00" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha_cliente" id="senha" placeholder="******" required>

            <button class="btn-criar-conta" type="submit" data-tts="entrar">CRIAR CONTA</button>
        </form>
    </section>

    <script src="assets/js/script.js"> </script>
</body>

</html>