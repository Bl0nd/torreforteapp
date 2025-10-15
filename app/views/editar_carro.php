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

<body class="body_cadastro page-editar-carro">

    <?php require_once('template/header.php') ?>

    <!-- Botão voltar -->
    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <section class="editar-carro-container">
        <h2>EDITAR CARRO</h2>

        <?php if (!empty($dados['veiculos'])): ?>
            <?php $veiculo = $dados['veiculos'][0]; ?>

            <form class="editar-carro-form" method="post" action="<?= URL_BASE; ?>index.php?url=editarcarro/salvar">
                <input type="hidden" name="id_veiculo" value="<?= $veiculo['id_veiculo'] ?>">

                <label for="modelo_veiculo">Modelo:</label>
                <input type="text" name="modelo_veiculo" id="modelo_veiculo" value="<?= $veiculo['modelo_veiculo']; ?>" placeholder="Nome do veículo" required>

                <label for="placa">Placa:</label>
                <input type="text" name="placa_veiculo" id="placa" value="<?= $veiculo['placa_veiculo'] ?>" placeholder="Placa" required>

                <label for="cor">Cor:</label>
                <input type="text" name="cor_original_veiculo" id="cor" value="<?= $veiculo['cor_original_veiculo'] ?>" placeholder="Cor" required>

                <label for="primeiro_dono">Primeiro dono:</label>
                <select name="primeiro_dono" id="primeiro_dono" required>
                    <option value="sim" <?= strtolower($veiculo['primeiro_dono']) === 'sim' ? 'selected' : '' ?>>Sim</option>
                    <option value="nao" <?= strtolower($veiculo['primeiro_dono']) === 'nao' ? 'selected' : '' ?>>Não</option>
                </select>

                <button class="btn-salvar-edicao" type="submit">Salvar</button>
            </form>
        <?php endif; ?>
    </section>

    <?php require_once('template/footer.php') ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>