<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php'); ?>

<body class="body_cadastro">

    <?php require_once('template/header.php'); ?>

    <a href="<?= URL_BASE; ?>index.php?url=historico" class="btn-voltar" aria-label="Voltar"><i class="uil uil-arrow-left"></i></a>

    <section class="avaliar">
        <h2>Avaliar Serviço</h2>

        <?php if (isset($_SESSION['erro'])): ?>
            <p class="alerta-erro"><?= $_SESSION['erro'];
                                    unset($_SESSION['erro']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <p class="alerta-sucesso"><?= $_SESSION['sucesso'];
                                        unset($_SESSION['sucesso']); ?></p>
        <?php endif; ?>

        <form method="POST" action="<?= URL_BASE; ?>index.php?url=avaliar/postarAvaliacao/<?= $id_agendamento;?>">

            <input type="hidden" name="id_agendamento" value="<?= $id_agendamento ?? '' ?>">

            <div class="form-group">
                <label for="comentario">Comentário sobre o Serviço:</label>
                <textarea name="comentario" id="comentario" rows="5" class="form-control" required placeholder="Deixe seu feedback sobre o serviço concluído."></textarea>
            </div>
            <button type="submit" class="btn-avaliar"><i class="uil uil-star"></i> Avaliar</button>
        </form>
    </section>

    <?php require_once('template/footer.php'); ?>

    <script src="assets/js/script.js"> </script>
</body>

</html>