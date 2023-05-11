require 'json'

json = '{
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
}'

data = JSON.parse json

class JsonHandler
    attr_accessor :json_decoded
    attr_reader :sum, :peso_liquido_enquadramento, :ncms
    def initialize
        @peso_liquido_enquadramento
        @ncms
        @sum
        @json_decoded
    end 

    def sort_array
        @json_decoded = @json_decoded.sort_by { |obj| obj[:enquadramento1] }.reverse
    end

    def sum_values(prop)
        @sum = @json_decoded.map { |obj| obj[prop] }.reduce(:+)
    end

    def escolher_enquad(enquad)
        filtered_enquad = @json_decoded.select { |obj| obj[enquad] }
        @peso_liquido_enquadramento = filtered_enquad.map { |obj| obj["peso_liquido"] }
    end

    def uniqueNCM
        filtered_ncm = @json_decoded.map { |obj| obj["ncm"] }
        @ncms = filtered_ncm.uniq
    end
end

json_teste = JsonHandler.new
json_teste.json_decoded = data["due_itens"]
json_teste.sort_array

puts json_teste.json_decoded
json_teste.sum_values 'valor_merc_local_embarque'
puts "local embarque: #{json_teste.sum}"
json_teste.sum_values 'valor_merc_condicao_venda'
puts "condicao venda: #{json_teste.sum}"

json_teste.escolher_enquad 'enquadramento1'
puts json_teste.peso_liquido_enquadramento
json_teste.uniqueNCM
puts json_teste.ncms
