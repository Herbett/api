<?php
namespace Src\DAO;

use PDO;
use Src\Model\Produto;

class ProdutoDAO
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Verifica se um produto com o id existe
    public function exists(string $id): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    // Insere um novo produto no banco
    public function insert(Produto $produto): bool
    {
        $sql = "INSERT INTO produtos (id, nome, tipo, valor) VALUES (:id, :nome, :tipo, :valor)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $produto->getId(),
            'nome' => $produto->getNome(),
            'tipo' => $produto->getTipo(),
            'valor' => $produto->getValor(),
        ]);
    }

    // (Opcional) Buscar produto por id
    public function findById(string $id): ?Produto
    {
        $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Produto(
                $row['id'],
                $row['nome'],
                $row['tipo'],
                (float)$row['valor']
            );
        }

        return null;
    }
}