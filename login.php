<?php
require_once "vendor/autoload.php";

use Microblog\ControleDeAcesso;
use Microblog\Usuario;
use Microblog\Utilitarios;

require_once "inc/cabecalho.php";

/* Programação das menssagens de Feedbacks */
if (isset($_GET['campos_obrigatorios'])) {
	$feedback = "Você deve preencecher os campos primeiro!";
} elseif (isset($_GET['dados_incorretos'])) {
	$feedback = "Dados Incorretos Tente novamente";
}


?>


<div class="row">
	<div class="bg-white rounded shadow col-12 my-1 py-4">
		<h2 class="text-center fw-light">Acesso à área administrativa</h2>

		<form action="" method="post" id="form-login" name="form-login" class="mx-auto w-50">

			<?php if (isset($feedback)) { ?>
				<p class="my-2 alert alert-warning text-center"><?= $feedback ?></p>
			<?php } ?>
			<div class="mb-3">
				<label for="email" class="form-label">E-mail:</label>
				<input class="form-control" type="email" id="email" name="email">
			</div>
			<div class="mb-3">
				<label for="senha" class="form-label">Senha:</label>
				<input class="form-control" type="password" id="senha" name="senha">
			</div>

			<button class="btn btn-primary btn-lg" name="entrar" type="submit">Entrar</button>

		</form>

		<?php

		if (isset($_POST['entrar'])) {
			// verificar se foi preenchido
			if (empty($_POST['email']) || empty($_POST['senha'])) {
				header("location:login.php?campos_obrigatorios");
			} else {
				// Capturar o e-mail 
				$usuario = new Usuario;
				$usuario->setEmail($_POST['email']);

				// Buscar o usuario/email no banco de dados

				$dados = $usuario->buscar();


				// se não  existir o usuário/e-mail, continua em login
				if (!$dados) { // ou if ($dados === false)
					header("location:login.php?dados_incorretos");
				} else {
					if(password_verify($_POST['senha'],$dados['senha'])){
					// Se existir:
					$sessao = new ControleDeAcesso;
					$sessao->login($dados['id'],$dados['nome'],$dados['tipo']);
					header("location:admin/index.php");
					// verificar a senha
					// Está corrta? Iniciar o processo de login
					// Não está? continua em login 
					} else{
						header("location:login.php?dados_incorretos");
					}
				}
			}
		}

		?>

	</div>


</div>






<?php
require_once "inc/rodape.php";
?>