<?php

class AuxiliarToken
{
    private const SECRET_KEY = 'TIPI03Senac2025'; // Variável de ambiente

    /**
     * O que é essa parte: Documentação para outros desenvolvedores explicando o que a função faz, quais parâmetros recebe e o que retorna.
     * Gera um token assinado com HMAC-SHA256 ( https://www.devglan.com/online-tools/hmac-sha256-online )
     *
     * @param array $dados Associativo com claims como 'id', 'email, exp'
     * @return string Token JWT compactado
     * 
     * Função pública e estática - Gerar que recebe um array associativo com os dados e retorna uma string
     */


    public static function gerar(array $dados): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        /**
         * Array criado com:
         * alg → Algoritmo de assinatura (HS256) - O algoritmo HS256, ou HMAC com SHA-256, é um algoritmo de assinatura digital usado para garantir a integridade e autenticidade de tokens
         * typ → Tipo do token (JWT). https://www.alura.com.br/artigos/o-que-e-json-web-tokens
         */
        $payload = array_merge($dados);
        /** (dados do token id, email e exp) */

        $base64Header = self::base64UrlEncode(json_encode($header));
        /** Codifica esse JSON em Base64URL (versão sem caracteres +, / e =). */
        $base64Payload = self::base64UrlEncode(json_encode($payload));
        /** Codifica esse JSON em Base64URL (versão sem caracteres +, / e =). */

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", self::SECRET_KEY, true);
        /**
         * Usa o HMAC com SHA256 para assinar a junção:
         * header + "." + payload
         * Usa a chave secreta SECRET_KEY (armazenada na classe).
         * O true indica que o retorno é binário (não em hexadecimal).
         */

        $base64Signature = self::base64UrlEncode($signature);
        /** Converte a assinatura binária para Base64URL. */

        return "$base64Header.$base64Payload.$base64Signature";
        /** Junta as três partes com pontos. */
    }

    /**
     * Valida um token e retorna o payload se válido, ou null caso contrário
     *
     * @param string $token
     * @return array|null Dados do token (payload) ou null
     */
    public static function validar(string $token): ?array
    {
        $partes = explode('.', $token);
        if (count($partes) !== 3) {
            return null;
        }

        [$base64Header, $base64Payload, $base64Signature] = $partes;

        // Recria assinatura
        $assinaturaCalculada = hash_hmac('sha256', "$base64Header.$base64Payload", self::SECRET_KEY, true);

        if (!hash_equals(self::base64UrlEncode($assinaturaCalculada), $base64Signature)) {
            return null;
        }

        $payload = json_decode(self::base64UrlDecode($base64Payload), true);
        if (!is_array($payload)) {
            return null;
        }

        if (isset($payload['exp']) && time() > $payload['exp']) {
            return null;
        }

        return $payload;
    }


    /** Gera um JWT específico para recuperação de senha */
    public static function gerarReset(int $id, string $email, int $ttlSegundos = 3600): string
    {
        $agora = time();
        $dados = [
            'sub'   => $id,            
            'email' => $email,
            'scope' => 'pwd_reset',    
            'iat'   => $agora,         
            'exp'   => $agora + $ttlSegundos
        ];
        return self::gerar($dados); 
    }

    /** Valida token de reset e confere o escopo */
    public static function validarReset(string $token): ?array
    {
        $payload = self::validar($token);
        if (!$payload || ($payload['scope'] ?? null) !== 'pwd_reset') {
            return null;
        }
        return $payload;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        $pad = strlen($data) % 4;
        if ($pad) {
            $data .= str_repeat('=', 4 - $pad);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
