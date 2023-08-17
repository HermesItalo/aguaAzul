<?php

namespace App\Service;

class VtexGet
{
    public function getCupom(string $string):string
    {
        $quantidadeUso = 0;
        $a = 0;
        $b = 1;
        $valorFinal = 0;
        $arraytotal = [];
        $maxtotal = [];
        $maxtotal['0'] = [
            "orderId" => 'orderIdRef',
            "usageDateUtc" => 'usageDateUtcRef'
        ];
        $arrayDeBuscaordens = [];
        $ch = curl_init();

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'X-VTEX-API-AppKey: vtexappkey-tfcxa2-VKFXGO',
            'X-VTEX-API-AppToken: OWVQNAHTMYZJTYLBGYSGMNCMURNABRMBELHYKCZVEACMIGKOJOLCZDABCINSDXLGFRHHMGQCZREQYHQTADHEPPSYDOZWIHILLPTPNBUSHCEGSHMZEBQCHOCYBCBMHPIW'
        );

        $url = "https://tfcxa2.vtexcommercestable.com.br/api/rnb/pvt/coupon/usage/".$string;

        $certPath = __DIR__.'/cacert.pem';

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, $certPath);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        $obj = json_decode($data, true);
        curl_close($ch);

        foreach ($obj['profileUsages'] as $obj_id => $obj_frag) {


            $arraytotal = array_merge($arraytotal, $obj_frag['orderUsage']);

        }

        foreach ($arraytotal as $obj_id => $obj_frag) {


            $explodido = explode("-", $obj_frag['usageDateUtc']);

            if ($explodido['0'] == "2023" && $explodido['1'] == "07") {


                $maxtotal[$b] = [
                    "orderId" => $obj_frag['orderId'],
                    "usageDateUtc" => $obj_frag['usageDateUtc']
                ];

                if ($obj_frag['orderId'] != $maxtotal[$b-1]['orderId']) {

                    $arrayDeBuscaordens[$a] = [
                        "orderId" => $obj_frag['orderId'],
                        "usageDateUtc" => $obj_frag['usageDateUtc']
                    ];
                    $a++;
                }
                $b++;
            }

        }

        foreach ($arrayDeBuscaordens as $obj_id => $obj_frag) {


            $ch = curl_init();

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
                'X-VTEX-API-AppKey: vtexappkey-tfcxa2-VKFXGO',
                'X-VTEX-API-AppToken: OWVQNAHTMYZJTYLBGYSGMNCMURNABRMBELHYKCZVEACMIGKOJOLCZDABCINSDXLGFRHHMGQCZREQYHQTADHEPPSYDOZWIHILLPTPNBUSHCEGSHMZEBQCHOCYBCBMHPIW'
            );
            $url = "https://tfcxa2.vtexcommercestable.com.br/api/oms/pvt/orders/".$obj_frag['orderId'];

            $certPath = __DIR__.'/cacert.pem';

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CAINFO, $certPath);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $data = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }

            $obj = json_decode($data, true);
            curl_close($ch);
            $totalize = $obj['totals']['0']['value'] + $obj['totals']['1']['value'];
            $valorFinal = $valorFinal + $totalize;
            $quantidadeUso++;

        }
        echo "</table>";
        $valorFinalInteiro = $valorFinal;
        $valorFinal = substr_replace($valorFinal, '.', -2, 0);
        $quantidadeVenda = number_format($valorFinal, 2, ',', ' ');
        $dadosDeVenda =  $quantidadeVenda."/".$quantidadeUso."/".$valorFinalInteiro;

        return $dadosDeVenda;
    }

    public function getCupomItems(string $string):array
    {
        $a = 0;
        $b = 1;
        $e = 0;
        $arraytotal = [];
        $maxtotal = [];
        $maxtotal['0'] = [
            "orderId" => 'orderIdRef',
            "usageDateUtc" => 'usageDateUtcRef'
        ];
        $arrayDeBuscaordens = [];
        $ch = curl_init();

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'X-VTEX-API-AppKey: vtexappkey-tfcxa2-VKFXGO',
            'X-VTEX-API-AppToken: OWVQNAHTMYZJTYLBGYSGMNCMURNABRMBELHYKCZVEACMIGKOJOLCZDABCINSDXLGFRHHMGQCZREQYHQTADHEPPSYDOZWIHILLPTPNBUSHCEGSHMZEBQCHOCYBCBMHPIW'
        );

        $url = "https://tfcxa2.vtexcommercestable.com.br/api/rnb/pvt/coupon/usage/".$string;

        $certPath = __DIR__.'/cacert.pem';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, $certPath);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        $obj = json_decode($data, true);
        curl_close($ch);
        foreach ($obj['profileUsages'] as $obj_id => $obj_frag) {
            $arraytotal = array_merge($arraytotal, $obj_frag['orderUsage']);
        }
        foreach ($arraytotal as $obj_id => $obj_frag) {
            $explodido = explode("-", $obj_frag['usageDateUtc']);
            if ($explodido['0'] == "2023" && $explodido['1'] == "07") {
                $maxtotal[$b] = [
                    "orderId" => $obj_frag['orderId'],
                    "usageDateUtc" => $obj_frag['usageDateUtc']
                ];
                if ($obj_frag['orderId'] != $maxtotal[$b-1]['orderId']) {
                    $arrayDeBuscaordens[$a] = [
                        "orderId" => $obj_frag['orderId'],
                        "usageDateUtc" => $obj_frag['usageDateUtc']
                    ];
                    $a++;
                }
                $b++;
            }
        }
        foreach ($arrayDeBuscaordens as $obj_id => $obj_frag) {
            $ch = curl_init();
            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
                'X-VTEX-API-AppKey: vtexappkey-tfcxa2-VKFXGO',
                'X-VTEX-API-AppToken: OWVQNAHTMYZJTYLBGYSGMNCMURNABRMBELHYKCZVEACMIGKOJOLCZDABCINSDXLGFRHHMGQCZREQYHQTADHEPPSYDOZWIHILLPTPNBUSHCEGSHMZEBQCHOCYBCBMHPIW'
            );
            $url = "https://tfcxa2.vtexcommercestable.com.br/api/oms/pvt/orders/".$obj_frag['orderId'];
            $certPath = __DIR__.'/cacert.pem'; // Substitua pelo caminho real do arquivo no seu servidor
            // Configurar opções cURL
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CAINFO, $certPath);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            $obj = json_decode($data, true);
            curl_close($ch);

            foreach ($obj['items'] as $key => $obj_items) {

                $meusItens[$e] = [
                    'ordem' => $obj_frag['orderId'],
                    'nome' => $obj_items['name'],
                    'valor' => $obj_items['price'],
                    'quantidade' => $obj_items['quantity']
                ];
                $e++;
            }
        }
        return $meusItens;
    }
}