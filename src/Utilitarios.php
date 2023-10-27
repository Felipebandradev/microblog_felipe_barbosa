<?php
namespace Microblog;

abstract class Utilitarios{
    public static function dump(array|bool|object $dados):void{
        echo "<pre>";
        var_dump($dados);
        echo "</pre>";
    }

    public static function formataData(string $data):string{
        return date("d/m/Y H:i",strtotime($data));
    }

}

