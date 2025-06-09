<?php
namespace Src\DAO;

use PDO;
use Src\Model\Estatistica;

class EstatisticaDAO
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function calcularEstatisticas(): Estatistica
    {
        // Consulta para obter:
        // count: número de compras
        // sum: soma total do valor (entrada + parcelas)
        // avg: média do valor por compra
        // sumTx: soma total de juros pagos
        // avgTx: média de juros pagos por compra

        // Ajuste os nomes das colunas conforme seu esquema

        $sql = "
            SELECT 
                COUNT(*) AS count,
                COALESCE(SUM(valorTotal), 0) AS sum,
                COALESCE(AVG(valorTotal), 0) AS avg,
                COALESCE(SUM(valorJuros), 0) AS sumTx,
                COALESCE(AVG(valorJuros), 0) AS avgTx
            FROM compras
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return new Estatistica(
            (int)$row['count'],
            (float)$row['sum'],
            (float)$row['avg'],
            (float)$row['sumTx'],
            (float)$row['avgTx']
        );
    }
}