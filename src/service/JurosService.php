<?php
namespace Src\Service;

class JurosService
{
    public function calcularJurosSELIC(string $dataInicio, string $dataFinal): ?float
    {
        // Formata as datas para o formato DD/MM/YYYY que a API do BCB usa
        $dataInicioFormat = date('d/m/Y', strtotime($dataInicio));
        $dataFinalFormat = date('d/m/Y', strtotime($dataFinal));

        $url = "https://api.bcb.gov.br/dados/serie/bcdata.sgs.11/dados?formato=json&dataInicial={$dataInicioFormat}&dataFinal={$dataFinalFormat}";

        // Busca os dados da API
        $json = @file_get_contents($url);

        if ($json === false) {
            return null; // erro na requisição
        }

        $dados = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($dados)) {
            return null; // erro no JSON
        }

        // Calcula a taxa acumulada da SELIC no período
        // A SELIC é dada em % ao ano, diariamente, para simplificar podemos calcular a média ou produto das taxas diárias convertidas.

        // O valor de 'valor' no retorno é a taxa SELIC diária em % (ex: 0.05 significa 0.05%)
        // Para taxa acumulada simples, podemos somar as taxas diárias e converter para decimal.

        $taxaAcumulada = 0.0;

        foreach ($dados as $registro) {
            // converte percentual para decimal
            $taxaDiaria = ((float)$registro['valor']) / 100;
            $taxaAcumulada += $taxaDiaria;
        }

        // A taxa acumulada total no período em decimal
        return round($taxaAcumulada, 6);
    }
}