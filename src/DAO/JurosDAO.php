<?php
namespace Src\DAO;

use PDO;
use Src\Model\Juros;

class JurosDAO
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Busca a taxa de juros mais recente
    public function getLatest(): ?Juros
    {
        $stmt = $this->conn->prepare("SELECT taxa, dataInicio, dataFinal FROM juros ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Juros(
                (float)$row['taxa'],
                $row['dataInicio'],
                $row['dataFinal']
            );
        }

        return null;
    }

    // Insere uma nova taxa de juros
    public function insert(Juros $juros): bool
    {
        $sql = "INSERT INTO juros (taxa, dataInicio, dataFinal) VALUES (:taxa, :dataInicio, :dataFinal)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'taxa' => $juros->getTaxa(),
            'dataInicio' => $juros->getDataInicio(),
            'dataFinal' => $juros->getDataFinal(),
        ]);
    }
}
