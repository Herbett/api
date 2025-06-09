<?php
namespace Src\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\DAO\CompraDAO;
use Src\DAO\ProdutoDAO;
use Src\Model\Compra;

class CompraController
{
    private CompraDAO $compraDAO;
    private ProdutoDAO $produtoDAO;

    public function __construct(CompraDAO $compraDAO, ProdutoDAO $produtoDAO)
    {
        $this->compraDAO = $compraDAO;
        $this->produtoDAO = $produtoDAO;
    }

    public function store(Request $request, Response $response): Response
    {
        $body = (string)$request->getBody();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $response->withStatus(400);
        }

        // Validações básicas
        if (
            empty($data['id']) || !preg_match('/^[a-f0-9\-]{36}$/i', $data['id']) ||
            !isset($data['valorEntrada']) || !is_numeric($data['valorEntrada']) || $data['valorEntrada'] < 0 ||
            !isset($data['qtdParcelas']) || !is_int($data['qtdParcelas']) || $data['qtdParcelas'] < 0 ||
            empty($data['idProduto']) || !preg_match('/^[a-f0-9\-]{36}$/i', $data['idProduto'])
        ) {
            return $response->withStatus(422);
        }

        // Verifica se compra com o id já existe
        if ($this->compraDAO->exists($data['id'])) {
            return $response->withStatus(422);
        }

        // Verifica se produto existe
        if (!$this->produtoDAO->exists($data['idProduto'])) {
            return $response->withStatus(422);
        }

        // Buscar produto para validar valorEntrada <= produto.valor
        $produto = $this->produtoDAO->findById($data['idProduto']);
        if ($produto === null) {
            return $response->withStatus(422);
        }

        if ($data['valorEntrada'] > $produto->getValor()) {
            return $response->withStatus(422);
        }

        // Cria objeto Compra
        $compra = new Compra(
            $data['id'],
            (float)$data['valorEntrada'],
            $data['qtdParcelas'],
            $data['idProduto']
        );

        $inseriu = $this->compraDAO->insert($compra);

        if ($inseriu) {
            // TODO: se compra for parcelada (>6 parcelas), salvar parcelas e juros (em outro endpoint)
            return $response->withStatus(201);
        } else {
            return $response->withStatus(500);
        }
    }
}