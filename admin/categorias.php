<?php
require_once "../inc/cabecalho-admin.php";
$sessao->verificarAcessoAdmin();

use Microblog\Categoria;



$categorias = new Categoria;

$listaDeCategorias = $categorias->listar();

$qtdDeCategorias = count($listaDeCategorias);

?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Categorias <span class="badge bg-dark"><?= $qtdDeCategorias ?></span>
		</h2>

		<p class="text-center mt-5">
			<a class="btn btn-primary" href="categoria-insere.php">
				<i class="bi bi-plus-circle"></i>
				Inserir nova categoria</a>
		</p>

		<div class="table-responsive">

			<table class="table table-hover">
				<thead class="table-light">
					<tr>
						<th>Nome</th>
						<th class="text-center">Operações</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($listaDeCategorias as $lista) { ?>
						<tr>
							<td> <?= $lista['nome'] ?> </td>
							<td class="text-center">
								<a class="btn btn-warning" href="categoria-atualiza.php?id=<?= $lista['id'] ?>">
									<i class="bi bi-pencil"></i> Atualizar
								</a>

								<a class="btn btn-danger excluir" href="categoria-exclui.php?id=<?= $lista['id'] ?>">
									<i class="bi bi-trash"></i> Excluir
								</a>
							</td>
						</tr>
					<?php } ?>

				</tbody>
			</table>
		</div>

	</article>
</div>


<?php
require_once "../inc/rodape-admin.php";
?>