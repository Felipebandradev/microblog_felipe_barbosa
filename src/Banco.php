<?php

namespace Microblog;

use PDO, Exception;

abstract class Banco
{
    private static string $servidor = "localhost";
    private static string $usuario = "root";
    private static string $senha = "";
    private static string $banco = "microblog_felipe_barbosa";
    /* operador "nullabel typehint"
        Quando usado indica que a propriedade/atributo
         pode conter null ou pode ser tipo PDO
        
        Neste caso, a propriedade conexao é inicialiada como
        nula, mas apartir do momento em que uma conexao é feita,
         ela passa a valer PDO */
    private static ?PDO $conexao = null;

    public static function conecta(): PDO
    {
        if (self::$conexao === null) {
            try {
                self::$conexao = new PDO(
                    "mysql:host=" . self::$servidor . "; 
                    dbname=" . self::$banco . ";
                    charset=utf8",
                    self::$usuario,
                    self::$senha
                );
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $erro) {
                die("Deu ruim: " . $erro->getMessage());
            }
        }
        return self::$conexao;
    }
}
