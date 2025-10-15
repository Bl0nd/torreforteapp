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

<body class="body_cadastro page-detalhes-servico">

    <?php require_once('template/header.php') ?>

    <!-- Botão voltar -->
    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <!--a class login-container mantem a estrutura padrão do container nas diferentes páginas-->
    <section class="servico-container">
        <h2>DETALHES DO SERVIÇO</h2>

        <div class="detalhes-servico">
            <?php if (!empty($dados['servicos'])) : ?>
                <p><strong>Nome:</strong> <?= $servicos['nome_servico'] ?></p>
                <img src="<?= FOTO_BASE . 'assets/img/servico/' . $servicos['foto_servico'] ?>" alt="<?= $servicos['nome_servico'] ?>">
                <p><strong>Preço:</strong> <?= $servicos['preco_base_servico'] ?></p>
                <p><strong>Descrição:</strong> <?= $servicos['descricao_servico'] ?? 'Sem descrição' ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>