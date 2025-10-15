<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require_once('template/head.php') ?>
</head>

<body class="body_cadastro">

    <?php require_once('template/header.php') ?>

    <section class="esqueci">
        <h2>ESQUECEU SENHA</h2>

        <P>Informe o e-mail que foi utilizado no cadastro para enviarmos um link de recuperação.</P>

        <form method="post" action="<?= URL_BASE ?>index.php?url=esqueceusenha/enviarLink">

            <label for="email">E-mail:</label>
            <input type="email" name="email_cliente" id="email_cliente" data-tts="email" class="bi bi-envelope" placeholder="seuemail@aqui.com" required>
            <p id="mensagem" class="mensagem-sucesso" style="display: none;">✅ Link de redefinição de senha enviado para seu email</p>
            <button class="btn-login" type="submit" data-tts="entrar">ENVIAR E-MAIL</button>
            
            <div class="retornar">
                <a href="index.php?url=login" data-tts="voltar para o login">VOLTAR PARA LOGIN</a>
            </div>
        </form>

    </section>

    <script src="assets/js/script.js"> </script>
</body>

</html>