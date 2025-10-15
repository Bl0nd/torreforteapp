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

<body class="body_cadastro page-cadastrar-carro">

    <?php require_once('template/header.php') ?>

    <!-- Botão voltar -->
    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <section class="carro-container">
        <h2>CADASTRAR CARRO</h2>

        <form class="carro-form" method="post" action="<?= URL_BASE; ?>index.php?url=cadastrarCarro/cadastrar">
            
            <label for="placa">Placa:</label>
            <input type="text" name="placa_veiculo" id="placa_veiculo" placeholder="Placa:" required>

            <label for="modelo">modelo:</label>
            <input type="text" name="modelo_veiculo" id="modelo_veiculo" placeholder="Modelo:" required>
            
            <label for="cor">Cor:</label>
            <input type="text" name="cor_original_veiculo" id="cor_original_veiculo" placeholder="Cor:" required>
            
            <label for="primeiro_dono">Primeiro dono:</label>
            <select name="primeiro_dono" id="primeiro_dono" required>
                <option value="sim">Sim</option>
                <option value="nao">Não</option>
            </select>

            <button class="btn-salvar-carro" type="submit" data-tts="entrar">SALVAR</button>
        </form>

    </section>

    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>