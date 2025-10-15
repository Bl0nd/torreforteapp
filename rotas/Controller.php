<?php

class Controller
{

    public function carregarViews($views, $dados = array())
    {
        //? Extrai os dados do array associativo para variáveis individuais
        extract($dados);

        //? Inclui o arquivo da view correspondente, localizado na pasta "views"
        require_once '../app/views/' . $views . '.php';
    }
}
