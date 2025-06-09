<?php
namespace Src\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\DAO\ProdutoDAO;
use Src\Model\Produto;

class ProdutoController
{
    private ProdutoDAO $produtoDAO;

    public function __construct(ProdutoDAO $produtoDAO)
    {
        $this->produtoDAO = $produtoDAO;
    }

    public function store(Request $request, Response $response): Response
    {
        $body = (string)$request->getBody();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // JSON inválido
            return $response->withStatus(400);
        }

        // Validações obrigatórias
        if (
            empty($data['id']) || !preg_match('/^[a-f0-9\-]{36}$/i', $data['id']) ||
            empty($data['nome']) ||
            !isset($data['valor']) || !is_numeric($data['valor']) || $data['valor'] < 0
        ) {
            return $response->withStatus(422);
        }

        // Verifica se id já existe (unicidade)
        if ($this->produtoDAO->exists($data['id'])) {
            return $response->withStatus(422);
        }

        // Monta objeto Produto
        $produto = new Produto(
            $data['id'],
            $data['nome'],
            $data['tipo'] ?? null,
            (float)$data['valor']
        );

        $inseriu = $this->produtoDAO->insert($produto);

        if ($inseriu) {
            return $response->withStatus(201);
        } else {
            return $response->withStatus(500);
        }
    }
}