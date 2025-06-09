<?php
namespace Src\Model;

class Juros
{
    private float $taxa;
    private string $dataInicio;
    private string $dataFinal;

    public function __construct(float $taxa, string $dataInicio, string $dataFinal)
    {
        $this->taxa = $taxa;
        $this->dataInicio = $dataInicio;
        $this->dataFinal = $dataFinal;
    }

    public function getTaxa(): float
    {
        return $this->taxa;
    }

    public function setTaxa(float $taxa): void
    {
        $this->taxa = $taxa;
    }

    public function getDataInicio(): string
    {
        return $this->dataInicio;
    }

    public function setDataInicio(string $dataInicio): void
    {
        $this->dataInicio = $dataInicio;
    }

    public function getDataFinal(): string
    {
        return $this->dataFinal;
    }

    public function setDataFinal(string $dataFinal): void
    {
        $this->dataFinal = $dataFinal;
    }
}
