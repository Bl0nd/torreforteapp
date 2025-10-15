<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <!-- TAG's essenciais para tratar a responsividade em diferentes aparelhos  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torre Forte Garage - <?= $titulo; ?> </title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
</head>

<body class="body_cadastro page-agendamentos">

    <?php require_once('template/header.php') ?>

    <!-- Botão voltar -->
    <a href="<?= URL_BASE; ?>index.php?url=menu" class="btn-voltar" aria-label="Voltar">
        <i class="uil uil-arrow-left"></i>
    </a>

    <section class="agendamentos-container">
        <h2>AGENDAMENTOS</h2>

        <form class="agendamentos-form" method="post" action="<?= URL_BASE; ?>index.php?url=agendamento/agendar">

            <!-- Selecionar Veículo -->
            <label for="selecionar_veiculo">Selecione o veículo:</label>
            <select class="form-select select-field" name="selecionar_veiculo" required>
                <option selected disabled>Escolha um veículo</option>
                <?php foreach ($veiculos as $veiculo): ?>
                    <option value="<?= $veiculo['id_veiculo']; ?>"><?= $veiculo['modelo_veiculo']; ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Tipo de Serviço (opcional) -->
            <label for="tipo_servico">Selecione o Tipo do Serviço:</label>
            <select class="form-select select-field" name="tipo_servico">
                <option selected disabled>Escolha um serviço</option>
                <?php foreach ($servicos as $servico): ?>
                    <option value="<?= $servico['id_servico']; ?>"><?= $servico['nome_servico']; ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Descrição do Problema -->
            <label for="descrever_problema">Descrever o problema:</label>
            <textarea class="select-field" id="descrever_problema" name="descrever_problema" rows="2" placeholder="Descrever o problema de forma objetiva" required></textarea>

            <!-- Data e Hora do Agendamento -->
            <label for="data_hora">Selecionar data e hora disponível:</label>
            <input class="select-field" type="datetime-local" id="data_hora" name="data_hora" required>

            <!-- Botão de Agendamento -->
            <button class="btn-agendar" type="submit">AGENDAR</button>
        </form>
    </section>

    <?php require_once('template/footer.php') ?>

    <!-- Flatpickr (biblioteca do calendário) -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Inicializar o calendário -->
    <script>
        flatpickr("#data_hora", {
            enableTime: true,
            // dateFormat: "d/m/Y H:i",
            minDate: "today",
            enable: [
                function(date) {
                    return (date.getDay() !== 0 && date.getDay() !== 6);
                }
            ],
            time_24hr: true
        });
    </script>

    <!-- Seu script.js normal -->
    <script src="assets/js/script.js"></script>

</body>

</html>