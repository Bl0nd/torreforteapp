<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php') ?>

<body id="redefinir-senha" class="body_redefinir-senha">

    <section class="redefinir">
        <h2>Redefinir Senha</h2>
        <h3>Crie uma nova senha de acesso</h3>

        <?php
        if (isset($_SESSION['error_login'])) {
            echo "<p style='color: red;>" . $_SESSION['erro_login'] . "</p>";
            unset($_SESSION['erro_login']); //limpa para não repetir
        }
        ?>

        <form method="POST" id="resetForm" action="<?= URL_BASE; ?>index.php?url=login/salvarNovaSenha">
            <input type="hidden" name="token" value="<?= $token ?? ''; ?>">

            <input type="password" name="nova_senha" placeholder="Digite a nova senha" required>
            <input type="password" name="confirma_senha" placeholder="Confirme a nova senha" required>
            <button class="btnRedefinir" type="submit" data-tts="Redefinir Senha">Redefinir Senha</button>
        </form>

        <!-- <div class="links voltar">
            <a href="<?= URL_BASE; ?>index.php?url=login">
                <i class="bi bi-box-arrow-left" data-tts="voltar para o login"></i>
                voltar para o Login
            </a>
        </div> -->

    </section>

    <script src="assets/js/script.js"></script>

</body>

</html>