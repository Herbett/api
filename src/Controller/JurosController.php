<?php
namespace Src\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\DAO\JurosDAO;
use Src\Service\JurosService;
use Src\Model\Juros;

class JurosController
{
    private JurosDAO $jurosDAO;
    private JurosService $jurosService;

    public function __construct(JurosDAO $jurosDAO, JurosService $jurosService)
    {
        $this->jurosDAO = $jurosDAO;
        $this->jurosService = $jurosService;
    }

    public function update(Request $request, Response $response): Response
    {
        $body = (string)$request->getBody();
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $response->withStatus(400);
        }

        $dataInicio = $data['dataInicio'] ?? null;
        $dataFinal = $data['dataFinal'] ?? null;

        // Validações
        if (!$dataInicio || !$dataFinal) {
            return $response->withStatus(422);
        }

        if ($dataInicio > $dataFinal) {
            return $response->withStatus(422);
        }

        if ($dataInicio < '2010-01-01') {
            return $response->withStatus(422);
        }

        if ($dataFinal > date('Y-m-d')) {
            return $response->withStatus(422);
        }

        // Pega a taxa Selic calculada via serviço
        $taxa = $this->jurosService->calcularJurosSELIC($dataInicio, $dataFinal);

        if ($taxa === null) {
            // Erro no cálculo ou na consulta da Selic
            return $response->withStatus(500);
        }

        $juros = new Juros($taxa, $dataInicio, $dataFinal);

        $ok = $this->jurosDAO->insert($juros);

        if (!$ok) {
            return $response->withStatus(500);
        }

        $response->getBody()->write(json_encode(['taxa' => $taxa]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}