<?php
namespace Src\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\DAO\EstatisticaDAO;

class EstatisticaController
{
    private EstatisticaDAO $estatisticaDAO;

    public function __construct(EstatisticaDAO $estatisticaDAO)
    {
        $this->estatisticaDAO = $estatisticaDAO;
    }

    public function index(Request $request, Response $response): Response
    {
        $estatistica = $this->estatisticaDAO->calcularEstatisticas();

        // Se não houver compras, o DAO deve retornar zeros, mas só por segurança:
        if ($estatistica->getCount() === 0) {
            $data = [
                'count' => 0,
                'sum' => 0,
                'avg' => 0,
                'sumTx' => 0,
                'avgTx' => 0,
            ];
        } else {
            $data = [
                'count' => $estatistica->getCount(),
                'sum' => $estatistica->getSum(),
                'avg' => $estatistica->getAvg(),
                'sumTx' => $estatistica->getSumTx(),
                'avgTx' => $estatistica->getAvgTx(),
            ];
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}