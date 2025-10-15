<!DOCTYPE html>
<html lang="pt-br">

<?php require_once('template/head.php') ?>
<?php require_once('template/header.php') ?>

<body id="index_login" class="body_index">

    <section class="login">
        <h2>LOGIN</h2>

        <form method="post" action="<?= URL_BASE; ?>index.php?url=login/logar">

            <input type="email" name="email_cliente" id="email" data-tts="email" class="bi bi-envelope" placeholder="seuemail@aqui.com" required>
            <input type="password" name="senha_cliente" id="senha" data-tts="senha" placeholder="******" required>

            <div class="links">
                <div><a href="index.php?url=cadastro" data-tts="cadastrar-se">CADASTRAR</a></div>
                <div><a href="index.php?url=esqueceuSenha" data-tts='esqueceu a senha'>ESQUECI A SENHA</a></div>
            </div>

            <button class="btn-login" type="submit" data-tts="logar">LOGAR</button>
        </form>
        
        <div id="instalar">
            <p>Deseja instalar o app?</p>
            <div>
                <button id="btnInstalar">Instalar</button>
                <button id="btnFechar">X</button>
            </div>
        </div>
    </section>

    <script src="assets/js/script.js"> </script>
</body>

</html>