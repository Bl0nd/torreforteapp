<?php

class EsqueceuSenhaController extends Controller
{
    public function index()
    {
        $dados = array();
        $dados['titulo'] = "Esqueceu aSenha";

        $this->carregarViews('esqueceu_senha', $dados);
    }

    public function enviarLink()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email_cliente'] ?? '';

            $url = API_BASE . "recuperarSenhaCliente";
            $postData = http_build_query([
                "email_cliente" => $email
            ]);
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                $_SESSION['erro_login'] = "Erro ao conectar com o servidor";
                header("Location: " . URL_BASE . "index.php?url=esqueceu_senha");
                exit;
            }
            curl_close($ch);
            echo $response;
            if ($httpCode === 200) {
                header("Location: " . URL_BASE . "index.php?url=login");
                exit;
            }
        }
    }
}
