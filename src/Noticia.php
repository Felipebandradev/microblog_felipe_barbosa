<?php

namespace Microblog;

use PDO, Exception;

final class Noticia
{
    private int $id;
    private string $data;
    private string $titulo;
    private string $texto;
    private string $resumo;
    private string $imagem;
    private string $destaque;
    private string $termo; // será usado na busca
    private PDO $conexao;

    // Propriedades cujo tipo são ASSOCIADOS à classes já existentes. Isso permitirá usar recursos destas classes à partir de Noticia.
    public Usuario $usuario;
    public Categoria $categoria;

    public function __construct()
    {
        // Ao criar um objeto Noticia, aproveitamos para instanciar objetos de Usuario e Categoria
        $this->usuario = new Usuario;
        $this->categoria = new Categoria;
        $this->conexao = Banco::conecta();
    }





    /* Métodos CRUD */

    public function inserir(): void
    {
        $sql = "INSERT INTO noticias(
             titulo, texto, resumo, 
             imagem, destaque, usuario_id, categoria_id
             ) 
            VALUES (
                :titulo, :texto, :resumo, 
             :imagem, :destaque, :usuario_id, :categoria_id
            )";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindValue(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindValue(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindValue(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindValue(":destaque", $this->destaque, PDO::PARAM_STR);

            /* Aqui Primeiro chamamos os getters de id do usuario e de Categoria, para só depois 
            associar os valoresaos parâmetros da consulta SQL.
            Isso é possivel devido à associassão entre as Classes. */
            $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            $consulta->bindValue(":categoria_id", $this->categoria->getId(), PDO::PARAM_INT);

            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro ao inserir noticia" . $erro->getMessage());
        }
    }


    public function listar(): array
    {
        if ($this->usuario->getTipo() === "admin") {

            // sql ADMIN pode ver todo mundo
            $sql = "SELECT noticias.id, 
                       noticias.titulo, 
                       noticias.data,
                       usuarios.nome AS autor,
                       noticias.destaque
                FROM noticias INNER JOIN usuarios
                ON noticias.usuario_id = usuarios.id
                ORDER BY data DESC";
        } else {

            // sql EDITOR pode ver só as noticias dele
            $sql = "SELECT id, titulo, data, destaque FROM noticias WHERE usuario_id = :usuario_id ORDER BY data DESC";
        }
        try {
            $consulta = $this->conexao->prepare($sql);

            if ($this->usuario->getTipo() === "editor") {
                $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }
            $consulta->execute();
            $resultado = $consulta->fetchALL(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao listar noticia" . $erro->getMessage());
        }

        return $resultado;
    }

    public function listarUm(): array
    {
        if ($this->usuario->getTipo() === 'admin') {
            $sql  = "SELECT * FROM noticias WHERE id = :id";
        } else {
            $sql = "SELECT * FROM noticias 
                    WHERE id = :id AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->getId(), PDO::PARAM_INT);
            if ($this->usuario->getTipo() === "editor") {
                $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao listar noticia" . $erro->getMessage());
        }

        return $resultado;
    }

    public function atualizar(): void
    {
        if ($this->usuario->getTipo() === 'admin') {
            $sql  = "UPDATE  noticias SET 
                        titulo = :titulo, texto = :texto, resumo = :resumo,
                        imagem = :imagem, categoria_id = :categoria_id, destaque = :destaque 
                    WHERE id = :id";
        } else {
            $sql = "UPDATE  noticias SET 
                        titulo = :titulo, texto = :texto, resumo = :resumo,
                        imagem = :imagem, categoria_id = :categoria_id, destaque = :destaque 
                    WHERE id = :id AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->getId(), PDO::PARAM_INT);
            $consulta->bindValue(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindValue(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindValue(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindValue(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindValue(":destaque", $this->destaque, PDO::PARAM_STR);
            if ($this->usuario->getTipo() === "editor") {
                $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }
            $consulta->bindValue(":categoria_id", $this->categoria->getId(), PDO::PARAM_INT);

            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro ao atualizar noticia" . $erro->getMessage());
        }
    }

    public function excluir(): void
    {
        if ($this->usuario->getTipo() === 'admin') {
            $sql  = "DELETE FROM noticias
                    WHERE id = :id";
        } else {
            $sql = "DELETE FROM noticias
                    WHERE id = :id AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->getId(), PDO::PARAM_INT);
            if ($this->usuario->getTipo() !== "admin") {
                $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
            }
            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro ao excluir noticia" . $erro->getMessage());
        }
    }



    /* Método upload de Foto */

    public function upload(array $arquivo): void
    {
        //Definir os tipos validos
        $tiposValidos = [
            "image/png",
            "image/jpeg",
            "image/gif",
            "image/svg+xml"
        ];

        // varificando se o arquivo não é um dos tipos válidos
        if (!in_array($arquivo["type"], $tiposValidos)) {
            // alertamos o usuário e o fazemos voltar para o form.
            die("
            <script>
                alert('Formato Inválido!');
                history.back();
            </script>
            ");
        }

        // Acessand APENAS o nome/extensão do arquivo
        $nome = $arquivo["name"];

        // Acessando os Dados de Acesso/armazenamento temporários
        $temporario = $arquivo["tmp_name"];

        // Definindo o local/pasta de destino das imgaens no site
        $pastaFinal = "../imagens/" . $nome;


        move_uploaded_file($temporario, $pastaFinal);
    }


    /* Métodos Área Pública */
    // index.php
    public function listarDestaque(): array
    {
        $sql = "SELECT id, titulo, resumo, imagem FROM noticias WHERE destaque = :destaque ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchALL(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao listar noticia" . $erro->getMessage());
        }

        return $resultado;
    }

    // todas.php
    public function listarTodas(): array
    {
        $sql = "SELECT id, titulo, data, resumo FROM noticias  ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchALL(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao listar noticia" . $erro->getMessage());
        }

        return $resultado;
    }


    // noticia.php

    public function listarDetalhes(): array
    {
        $sql = "SELECT 
                     noticias.titulo, noticias.texto, noticias.id,
                     noticias.data, noticias.imagem,usuarios.nome AS autor
                FROM noticias INNER JOIN usuarios
                ON noticias.usuario_id = usuarios.id
                WHERE noticias.id = :id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $this->getId(), PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao carregar dados da noticia" . $erro->getMessage());
        }

        return $resultado;
    }

    // noticias-por-categoria.php
    public function listarPorCategoria(): array
    {
        $sql = "SELECT 
                    noticias.titulo, noticias.resumo, noticias.id,  noticias.data, 
                    usuarios.nome AS autor,
                    categorias.nome AS categoria
                FROM noticias 
                    INNER JOIN usuarios ON noticias.usuario_id = usuarios.id 
                    INNER JOIN categorias ON noticias.categoria_id = categorias.id
            
                WHERE noticias.categoria_id = :categoria_id";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":categoria_id", $this->categoria->getId(), PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao carregar dados da noticia" . $erro->getMessage());
        }

        return $resultado;
    }

    public function busca(): array
    {

        $sql = "SELECT id, titulo, data, resumo FROM noticias
                WHERE
                    titulo LIKE :termo
                OR  resumo LIKE :termo
                OR  texto  LIKE  :termo  
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":termo", "%" . $this->getTermo() . "%", PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro ao carregar dados da noticia" . $erro->getMessage());
        }

        return $resultado;
    }






    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);;
        $this->id = $id;
        return $this;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): self
    {
        $this->data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->data = $data;
        return $this;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = filter_var($titulo, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->titulo = $titulo;
        return $this;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = filter_var($texto, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->texto = $texto;
        return $this;
    }

    public function getResumo(): string
    {
        return $this->resumo;
    }

    public function setResumo(string $resumo): self
    {
        $this->resumo = filter_var($resumo, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->resumo = $resumo;
        return $this;
    }

    public function getImagem(): string
    {
        return $this->imagem;
    }

    public function setImagem(string $imagem): self
    {
        $this->imagem = filter_var($imagem, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->imagem = $imagem;
        return $this;
    }

    public function getDestaque(): string
    {
        return $this->destaque;
    }

    public function setDestaque(string $destaque): self
    {
        $this->destaque = filter_var($destaque, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->destaque = $destaque;
        return $this;
    }

    public function getTermo(): string
    {
        return $this->termo;
    }

    public function setTermo(string $termo): self
    {
        $this->termo = filter_var($termo, FILTER_SANITIZE_SPECIAL_CHARS);
        $this->termo = $termo;
        return $this;
    }
}
