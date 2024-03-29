<?php

namespace Microblog;

use PDO, Exception;

class Usuario
{
    private int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $tipo;
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = Banco::conecta();
    }



    /* Métodos para rotinas de CRUD no Bando */

    // INSERT de usuario
    public function inserir(): void
    {
        $sql = "INSERT INTO usuarios(nome, email, senha, tipo)
                         VALUES(:nome, :email,:senha, :tipo)";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":nome", $this->nome, PDO::PARAM_STR);
            $consulta->bindValue(":email", $this->email, PDO::PARAM_STR);
            $consulta->bindValue(":senha", $this->senha, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $this->tipo, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro ao inserir usuario" . $erro->getMessage());
        }
    }

    // SELECT de usuarios
    public function listar(): array
    {
        $sql = "SELECT * FROM usuarios ORDER BY nome";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchALL(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao listar usuario" . $erro->getMessage());
        }

        return $resultado;
    }

    // SELECT de usuario
    public function listarUm(): array
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao carregar doados do usuário: " . $erro->getMessage());
        }
        return $resultado;
    }

    // UPDATE de usuário
    public function atualizar(): void
    {
        $sql = "UPDATE usuarios SET 
                    nome = :nome,
                    email = :email,
                    senha = :senha,
                    tipo = :tipo
                WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->bindValue(":nome", $this->nome, PDO::PARAM_STR);
            $consulta->bindValue(":email", $this->email, PDO::PARAM_STR);
            $consulta->bindValue(":senha", $this->senha, PDO::PARAM_STR);
            $consulta->bindValue(":tipo", $this->tipo, PDO::PARAM_STR);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro ao atualizar usuario" . $erro->getMessage());
        }
    }

    // DELETE de Usuário
    public function excluir(): void
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->id, PDO::PARAM_INT);
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro ao deletar usuario" . $erro->getMessage());
        }
    }










    /* Método par buscar no banco um usuário através do email */

    public function buscar(): array | bool
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":email", $this->email, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao buscar usuario" . $erro->getMessage());
        }
        return $resultado;
    }




    /* Método para codificar senha */
    public function codificaSenha(string $senha): string
    {
        return password_hash($senha, PASSWORD_DEFAULT);
    }

    public function verificaSenha(string $senhaFormulario, string $senhaBanco): string
    {

        if (password_verify($senhaFormulario, $senhaBanco)) {
            return $senhaBanco;
        } else {
            return $this->codificaSenha($senhaFormulario);
        }
    }







    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id =  filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }


    public function setNome(string $nome): self
    {
        $this->nome = filter_var($nome, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = filter_var($email, FILTER_SANITIZE_EMAIL, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): self
    {
        $this->senha = filter_var($senha, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = filter_var($tipo, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }
}
