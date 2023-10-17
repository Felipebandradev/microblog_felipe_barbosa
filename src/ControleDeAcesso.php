<?php
namespace Microblog;

 final class ControleDeAcesso {
    /* Se não EXISTIR  uma sessão "em andamento" */
    public function __construct(){
        if(!isset ($_SESSION)){
            //Então inicialize uma seção
            session_start();
        }        
    }

    public function verificaAcesso():void{
        /* Se NÃO EXISTER uma variável de sessão chamada "id" (ou seja, ainda não houve um login por parte do usuário) */
        if( !isset($_SESSION['id'])){
            /* ...então destrua qualquer resquício de sesão, redirecione para o formulário de login e pare completamente o script */
            session_destroy();
            header("location:../login.php?acesso_proibido");
            die(); // ou exit;
        } 

    }

    public function login(int $id,string $nome,string $tipo):void{
        /* No momento em que ocorre login, criamos variáveis de sessão contendo os dados que queremos monitorar/controlar/usar através da sessão enquanto a pessoa estiver logada */
        $_SESSION["id"] = $id;
        $_SESSION["nome"] = $nome;
        $_SESSION["tipo"] = $tipo;
    }

    public function logout():void{
        session_start();
        session_destroy();
        header("location:../login.php?logout");
        die();
    }
    
}