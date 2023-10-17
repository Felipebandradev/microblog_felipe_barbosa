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
            header("location:../login.php");
            die(); // ou exit;
        } 

    }
    
}