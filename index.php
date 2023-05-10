<?php
echo "<pre>";


$json = '{
        "numero": "22BR000396386-0",
        "ruc": "2BR8263864420000000009999999999",
        "identificacao": "EXBR104513",
        "declarante": "82638644000174",
        "moeda": "220",
        "incoterm": "FCA",
        "pais_destino": "BOL",
        "despacho_rfb": "0000700",
        "despacho_em_recinto": 1,
        "despacho_recinto": "201701",
        "embarque_rfb": "0000700",
        "embarque_em_recinto": 1,
        "embarque_recinto": "201701",
        "informacoes_complementares": "EXBR104513 - 2 CONTAINERS - FCA",
        "importador_nome": "COMPANHIA INDUSTRIAL",
        "importador_pais": "BOL",
        "importador_endereco": "AVENIDA CHACALTAYA NO 2141, LA PAZ, , BOLIVIA",
        "due_itens": [
            {
                "item": 1,
                "nfe_chave": "43220382638644000174550010001089351014405555",
                "nfe_item": 1,
                "ncm": "24013000",
                "valor_merc_local_embarque": 12790.00,
                "valor_merc_condicao_venda": 18490.00,
                "peso_liquido": 19800.0,
                "enquadramento1": "80000",
                "enquadramento2": null,
                "enquadramento3": null,
                "enquadramento4": null,
                "descricao_complementar": "RESÍDUOS DE TABACO, VIRGINIA, ESTUFA, ESTERELIZADO, TEE-RES-ST, MARCA ULT90003"
            },
            {
                "item": 2,
                "nfe_chave": "43220382638644000174550010001089361019925555",
                "nfe_item": 1,
                "ncm": "24013000",
                "valor_merc_local_embarque": 77290.00,
                "valor_merc_condicao_venda": 66490.00,
                "peso_liquido": 19800.0,
                "enquadramento1": "99101",
                "enquadramento2": null,
                "enquadramento3": null,
                "enquadramento4": null,
                "descricao_complementar": "RESÍDUOS DE TABACO, VIRGINIA, ESTUFA, ESTERELIZADO, TEE-RES-ST, MARCA ULT90003"
            },
            {
                "item": 3,
                "nfe_chave": "43220382638644000174550010001089361019925555",
                "nfe_item": 1,
                "ncm": "48202000",
                "valor_merc_local_embarque": 77290.00,
                "valor_merc_condicao_venda": 66490.00,
                "peso_liquido": 19800.0,
                "enquadramento1": "81101",
                "enquadramento2": null,
                "enquadramento3": null,
                "enquadramento4": null,
                "descricao_complementar": "RESÍDUOS DE TABACO, VIRGINIA, ESTUFA, ESTERELIZADO, TEE-RES-ST, MARCA ULT90003"
            },
            {
                "item": 4,
                "nfe_chave": "43220382638644000174550010001089361019925555",
                "nfe_item": 1,
                "ncm": "45781236",
                "valor_merc_local_embarque": 77290.00,
                "valor_merc_condicao_venda": 66490.00,
                "peso_liquido": 19800.0,
                "enquadramento1": "81101",
                "enquadramento2": null,
                "enquadramento3": null,
                "enquadramento4": null,
                "descricao_complementar": "RESÍDUOS DE TABACO, VIRGINIA, ESTUFA, ESTERELIZADO, TEE-RES-ST, MARCA ULT90003"
            }
        ]
}';

//Json Decodificado
$json_decoded = json_decode($json, true);

class JsonHandler
{
    public $json_decoded;
    public $sum;
    public $peso_liquido_agrupamento;
    public $ncms;

    public function __construct()
    {
        $this->peso_liquido_agrupamento = [];
        $this->ncms = [];
    }

    public function set_json_decoded($array)
    {
        $this->json_decoded = $array;
    }

    public function get_json_decoded()
    {
        return $this->json_decoded;
    }

    public function SortArray()
    {
        usort($this->json_decoded, function ($a, $b) {
            return $a['enquadramento1'] - $b['enquadramento1'];
        });
    }
    public function SumValues($prop)
    {
        foreach ($this->json_decoded as $item) {
            $this->sum += $item[$prop];
            return $this->sum;
        }
        return $this->sum;
    }

    public function escolherEnquad($enquad)
    {
        foreach ($this->json_decoded as $item) {
            if ($item[$enquad]) {
                array_push($this->peso_liquido_agrupamento, $item['peso_liquido']);
            }
        }
        return $this->peso_liquido_agrupamento;
    }

    private function getNCM()
    {
        foreach ($this->json_decoded as $item) {
            array_push($this->ncms, $item['ncm']);
        }
        return $this->ncms;
    }

    public function uniqueNCM()
    {
        $array = $this->getNCM();
        return array_unique($array);
    }
}


$json_teste = new JsonHandler();
$json_teste->set_json_decoded($json_decoded['due_itens']);


//Sortida
print_r($json_teste->get_json_decoded());
echo $json_teste->SortArray();
print_r($json_teste->get_json_decoded());

//Somatória
print_r("Somatório é igual a: " . $json_teste->SumValues('valor_merc_local_embarque'));
print_r("Somatório é igual a: " . $json_teste->SumValues('valor_merc_condicao_venda'));

//Peso líquido por enquadramento
print_r($json_teste->escolherEnquad('enquadramento1'));

//NCM unico
print_r($json_teste->uniqueNCM());
