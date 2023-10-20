<?php
require_once "../vendor/autoload.php";
use Microblog\ControleDeAcesso;
use Microblog\Categoria;

$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();
$sessao->verificarAcessoAdmin();

$categoria = new Categoria;
$categoria->setId($_GET['id']);
$categoria->excluir();

header("location:categorias.php");