<?php
namespace Src\Model;

class Produto
{
    private string $id;
    private string $nome;
    private ?string $tipo;  // tipo é opcional
    private float $valor;

    public function __construct(string $id, string $nome, ?string $tipo, float $valor)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->valor = $valor;
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    // Setters (se precisar modificar depois, opcional)
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setTipo(?string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    // Método para facilitar conversão para array (útil para resposta JSON, se precisar)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'valor' => $this->valor,
        ];
    }
}
