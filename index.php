<?php

use Microblog\Utilitarios;

require_once "inc/cabecalho.php";

$noticia->setDestaque("sim");
$ListaDeDestaque = $noticia->listarDestaque();


?>


<div class="row my-1 mx-md-n1">
    <?php foreach ($ListaDeDestaque as $itemDestaque) { ?>
        <!-- INÍCIO Card -->
        <div class="col-md-6 my-1 px-md-1">
            <article class="card shadow-sm h-100">
                <a href="noticia.php?id=<?= $itemDestaque['id'] ?>" class="card-link">
                    <img src="imagens/<?= $itemDestaque['imagem'] ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h3 class="fs-4 card-title"><?= $itemDestaque['titulo'] ?></h3>
                        <p class="card-text"><?= $itemDestaque['resumo'] ?></p>
                    </div>
                </a>
            </article>
        </div>
        <!-- FIM Card -->
    <?php } ?>
</div>





<?php
require_once "inc/todas.php";
require_once "inc/rodape.php";
?>