<?php


class AuthController extends Controller
{

    public function register()
    {
        $url = API_BASE . 'cadastro';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = filter_input(INPUT_POST, 'nome_cliente', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email_cliente', FILTER_SANITIZE_SPECIAL_CHARS);
            $cpf = filter_input(INPUT_POST, 'cpf_cliente', FILTER_SANITIZE_SPECIAL_CHARS);
            $tel = filter_input(INPUT_POST, 'telefone_cliente', FILTER_SANITIZE_SPECIAL_CHARS);
            $senha = filter_input(INPUT_POST, 'senha_cliente', FILTER_SANITIZE_SPECIAL_CHARS);

            if ((!$nome || !$cpf || !$tel || !$senha)) {
                http_response_code(400);
                echo json_encode(["erro" => "Todos os campos são obrigatórios!"]);
                return;
            }else{
                $postData = [
                    'nome_cliente' => $nome,
                    'email_cliente' => $email,
                    'cpf_cliente' => $cpf,
                    'telefone_cliente' => $tel,
                    'senha_cliente' => $senha
                ];
            }
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

            // Envia os dados como x-www-form-urlencoded
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json'
            ]);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Erro no cURL: ' . curl_error($ch);
            } else {
                $resultado = json_decode($response, true); // decodifica JSON da resposta
                if ($resultado['mensagem'] == 'Tudo Certo!') {
                    header("Location:" . URL_BASE);
                    exit;
                } else {
                    print_r($resultado['erro']);
                }
                curl_close($ch);
            }
        }
    }

    public function login()
    {
        $url = API_BASE . 'login';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email_cliente', FILTER_VALIDATE_EMAIL);
            $senha = filter_input(INPUT_POST, 'senha_cliente', FILTER_SANITIZE_SPECIAL_CHARS);


            if (isset($email) && isset($senha)) {
                $postData = [
                    'email_cliente' => $email,
                    'senha_cliente' => $senha
                ];

                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);

                // Envia os dados como x-www-form-urlencoded
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Accept: application/json'
                ]);

                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    echo 'Erro no cURL: ' . curl_error($ch);
                } else {
                    $resultado = json_decode($response, true);
                    if (isset($resultado['mensagem']) && $resultado['mensagem'] == 'Tudo Certo!') {
                        $_SESSION['userId'] = $resultado['Cliente']['id_cliente'];
                        header("Location:" . URL_BASE . "home");
                        exit;
                    } else {
                        echo $resultado['erro'];
                    }
                }
                curl_close($ch);
            } else {
                echo "Email ou senha inválidos.";
            }
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            header("Location:" . URL_BASE);
            exit;
        }
    }
}
