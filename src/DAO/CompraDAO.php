<?php
namespace Src\DAO;

use PDO;
use Src\Model\Compra;

class CompraDAO
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Verifica se uma compra com o id existe
    public function exists(string $id): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM compras WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    // Verifica se o produto existe
    public function produtoExists(string $idProduto): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $idProduto]);
        return $stmt->fetchColumn() > 0;
    }

    // Insere uma nova compra no banco
    public function insert(Compra $compra): bool
    {
        $sql = "INSERT INTO compras (id, valorEntrada, qtdParcelas, idProduto) 
                VALUES (:id, :valorEntrada, :qtdParcelas, :idProduto)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'id' => $compra->getId(),
            'valorEntrada' => $compra->getValorEntrada(),
            'qtdParcelas' => $compra->getQtdParcelas(),
            'idProduto' => $compra->getIdProduto(),
        ]);
    }

    // Você pode adicionar métodos para buscar compras, listar tudo, etc.
}