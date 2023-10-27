<?php

use Microblog\Noticia;
use Microblog\Utilitarios;

require_once "vendor/autoload.php";
$noticia = new Noticia;
$noticia->setTermo($_POST['busca']);
$resultados = $noticia->busca();

$qtd = count($resultados);

if($qtd > 0){
?>

    <h2 class="fs-5">Resultado: <span><?=$qtd?></span></h2>
    <div class="list-group">
        <?php foreach($resultados AS $itemNoticia){?>
        <a class="list-goup-item list-group-item-action"
             href="noticia.php?id=<?=$itemNoticia['id']?>">
             <?=$itemNoticia['titulo']?>
        </a>
        <?php }?>
    </div>

<?php
} else {
?>
    <h2 class="fs-5 text-danger">Sem Notícias</h2>
<?php
} 
?>

