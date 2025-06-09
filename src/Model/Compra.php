<?php
namespace Src\Model;

class Compra
{
    private string $id;
    private float $valorEntrada;
    private int $qtdParcelas;
    private string $idProduto;

    public function __construct(string $id, float $valorEntrada, int $qtdParcelas, string $idProduto)
    {
        $this->id = $id;
        $this->valorEntrada = $valorEntrada;
        $this->qtdParcelas = $qtdParcelas;
        $this->idProduto = $idProduto;
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getValorEntrada(): float
    {
        return $this->valorEntrada;
    }

    public function getQtdParcelas(): int
    {
        return $this->qtdParcelas;
    }

    public function getIdProduto(): string
    {
        return $this->idProduto;
    }

    // Setters (se precisar modificar depois)
    public function setValorEntrada(float $valorEntrada): void
    {
        $this->valorEntrada = $valorEntrada;
    }

    public function setQtdParcelas(int $qtdParcelas): void
    {
        $this->qtdParcelas = $qtdParcelas;
    }

    public function setIdProduto(string $idProduto): void
    {
        $this->idProduto = $idProduto;
    }

    // Para converter para array, útil para retorno JSON ou logs
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'valorEntrada' => $this->valorEntrada,
            'qtdParcelas' => $this->qtdParcelas,
            'idProduto' => $this->idProduto,
        ];
    }
}