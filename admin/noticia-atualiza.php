<?php
require_once "../inc/cabecalho-admin.php";

use Microblog\Noticia;

$noticia = new Noticia;

$listaDeCategoria = $noticia->categoria->listar();

$noticia->usuario->setId($_SESSION['id']);
$noticia->usuario->setTipo($_SESSION['tipo']);
$noticia->setId($_GET['id']);

$dados = $noticia->listarUm();

if (isset($_POST['atualizar'])){
    $noticia->setTitulo($_POST['titulo']);
	$noticia->setTexto($_POST['texto']);
	$noticia->setResumo($_POST['resumo']);
	$noticia->setDestaque($_POST['destaque']);


	$noticia->categoria->setId($_POST['categoria']);

	$imagem = $_FILES['imagem']; 
	$noticia->upload($imagem);
	$noticia->setImagem($imagem["name"]);
    

}

?>


<div class="row">
    <article class="col-12 bg-white rounded shadow my-1 py-4">

        <h2 class="text-center">
            Atualizar dados da notícia
        </h2>

        <form class="mx-auto w-75" action="" method="post" id="form-atualizar" name="form-atualizar" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label" for="categoria">Categoria:</label>
                <select class="form-select" name="categoria" id="categoria" required>
                    <option value=""></option>
                    <?php foreach ($listaDeCategoria as $itemCategoria) { ?>
                        <option <?php
                                if ($itemCategoria['id'] === $dados['categoria_id']) {
                                    echo "selected";
                                }
                                ?> value="<?= $itemCategoria['id'] ?>">
                            <?= $itemCategoria['nome'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" for="titulo">Título:</label>
                <input class="form-control" required type="text" id="titulo" name="titulo" value="<?= $dados['titulo'] ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="texto">Texto:</label>
                <textarea class="form-control" required name="texto" id="texto" cols="50" rows="6"><?= $dados['texto'] ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="resumo">Resumo (máximo de 300 caracteres):</label>
                <span id="maximo" class="badge bg-danger">0</span>
                <textarea class="form-control" required name="resumo" id="resumo" cols="50" rows="2" maxlength="300"><?= $dados['resumo'] ?></textarea>
            </div>

            <div class="mb-3">
                <label for="imagem-existente" class="form-label">Imagem da notícia:</label>
                <!-- campo somente leitura, meramente informativo -->
                <input class="form-control" type="text" id="imagem-existente" name="imagem-existente" readonly value="<?= $dados['imagem'] ?>">
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Caso queira mudar, selecione outra imagem:</label>
                <input class="form-control" type="file" id="imagem" name="imagem" accept="image/png, image/jpeg, image/gif, image/svg+xml">
            </div>

            <div class="mb-3">
                <p>Deixar a notícia em destaque?
                    <input <?php
                                if ($dados['destaque'] === "nao") {
                                    echo "checked";
                                }
                                ?> 
                     type="radio" class="btn-check" name="destaque" id="nao" autocomplete="off"  value="nao">
                    <label class="btn btn-outline-danger" for="nao">Não</label>

                    <input  <?php
                                if ($dados['destaque'] === "sim") {
                                    echo "checked";
                                }
                                ?> 
                     type="radio" class="btn-check" name="destaque" id="sim" autocomplete="off" value="sim">
                    <label class="btn btn-outline-success" for="sim">Sim</label>
                </p>
            </div>

            <button class="btn btn-primary" name="atualizar"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
        </form>

    </article>
</div>


<?php
require_once "../inc/rodape-admin.php";
?>