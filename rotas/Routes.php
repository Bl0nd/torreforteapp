<?php

class Routes
{
    public function execute()
    { //* Define a URL padrão como "/"
        $url = '/';

        //* Verifica se existe um parâmetro 'url'
        if (isset($_GET['url'])) {
            $url .= $_GET['url']; //? Concatena a URL recebida
        }

        //! Inicializa um array para armazenar os parâmetros da URL
        $parametro = array();

        if (!empty($url) && $url != '/') {

            //* Divide a URL em partes separadas por "/"
            $url = explode('/', $url);

            //!---------------------- Remove o primeiro elemento vazio da URL
            array_shift($url);

            //? Define o nome do controlador com a primeira letra maiúscula e adiciona "Controller"
            $controladorAtual = ucfirst($url[0]) . 'Controller';

            //!---------------------- Remove o nome do controlador da URL
            array_shift($url);

            //* Verifica se há uma ação especificada na URL
            if (isset($url[0]) && !empty($url[0])) {
                $acaoAtual = $url[0]; //? Define a ação atual
                array_shift($url); //! Remove a ação da URL
            } else {
                $acaoAtual = 'index'; //todo---------- Define a ação padrão como "index" caso não seja especificada
            }

            if (count($url) > 0) { // Se ainda houver itens na URL, são considerados parâmetros
                $parametro = $url; // Define os parâmetros
            }
        } else {
            $controladorAtual = 'LoginController'; //todo -------- Define o controlador padrão como HomeController
            $acaoAtual = 'index';
        }

        //* Verifica se o controlador existe dentro da pasta controllers e se o método existe na classe
        if (!file_exists('../app/controllers/' . $controladorAtual . '.php') || !method_exists($controladorAtual, $acaoAtual)) {
            echo 'Não tem o ' . $controladorAtual . ' NEM A ' . $acaoAtual; //! Mensagem de erro

            $controladorAtual = "ErroController"; //caso o arquivo ou método não exista
            $acaoAtual = "index";
        }

        $controller = new $controladorAtual(); // Instancia a classe do controlador atual

        //* Chama dinamicamente o método do controlador passando os parâmetros da URL - (carregar os arquivos)
        call_user_func_array(array($controller, $acaoAtual), $parametro);
    }
}
