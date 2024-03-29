<?php
require_once "../inc/cabecalho-admin.php";

use Microblog\Usuario;
use Microblog\Utilitarios;

$usuarios = new Usuario;
$sessao->verificarAcessoAdmin();
$listar = $usuarios->listar();

$qtddeUsuarios = count($listar);


?>


<div class="row">
	<article class="col-12 bg-white rounded shadow my-1 py-4">

		<h2 class="text-center">
			Usuários <span class="badge bg-dark"><?= $qtddeUsuarios ?></span>
		</h2>

		<!-- <?php //if ($_GET['status'] === "atualizado") { ?>
			<h3 class="alert alert-success text-center">
				Atualizado com Sucesso
			</h3>
		<?php //} ?> -->


		<p class="text-center mt-5">
			<a class="btn btn-primary" href="usuario-insere.php">
				<i class="bi bi-plus-circle"></i>
				Inserir novo usuário</a>
		</p>

		<div class="table-responsive">

			<table class="table table-hover">
				<thead class="table-light">
					<tr>
						<th>Nome</th>
						<th>E-mail</th>
						<th>Tipo</th>
						<th class="text-center">Operações</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($listar as $itemUsuario) { ?>
						<tr>
							<td> <?= $itemUsuario['nome'] ?> </td>
							<td> <?= $itemUsuario['email'] ?> </td>
							<td> <?= $itemUsuario['tipo'] ?> </td>
							<td class="text-center">
								<a class="btn btn-warning" href="usuario-atualiza.php?id=<?= $itemUsuario['id'] ?>">
									<i class="bi bi-pencil"></i> Atualizar
								</a>

								<a class="btn btn-danger excluir" href="usuario-exclui.php?id=<?= $itemUsuario['id'] ?>">
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