<?php
require_once "inc/cabecalho.php";
$noticia->categoria->setId($_GET['id']);
// $categoria = $noticia->categoria->listarUm();
$listaPorCategoria =  $noticia->listarPorCategoria();
?>


<div class="row my-1 mx-md-n1">

    <article class="col-12">
        <?php if (count($listaPorCategoria) > 0) { ?> <!--$categoria['nome'] -->
            <h2 class="text-center">
                Notícias sobre
                <span class="badge bg-primary">
                    <?= $listaPorCategoria[0]['categoria'] ?>
                </span>
            </h2>
        <?php } else { ?>
            <h2 class="alert alert-warning text-center">
                Não há noticias desta categoria
            </h2>
        <?php } ?>

        <div class="row my-1">
            <div class="col-12 px-md-1">
                <div class="list-group">
                    <?php foreach ($listaPorCategoria as $itemCategoria) { ?>
                        <a href="noticia.php?id=<?= $itemCategoria['id'] ?>" class="list-group-item list-group-item-action">
                            <h3 class="fs-6"><?= $itemCategoria['titulo'] ?></h3>
                            <p><time><?= $itemCategoria['data'] ?></time> - <?= $itemCategoria['autor'] ?></p>
                            <p><?= $itemCategoria['resumo'] ?></p>
                        </a>
                    <?php } ?>


                </div>
            </div>
        </div>


    </article>


</div>


<?php
require_once "inc/todas.php";
require_once "inc/rodape.php";
?>