<?php

namespace App\Util;

use Symfony\Component\Config\Definition\Exception\Exception;

class Utils
{
    public static function correctNumero($numero, $indicatif = '237')
    {
        $render = false;

        if (9 == strlen($numero)) {
            $numero = substr($numero, 0, -9);
        }
        $numero = str_replace('&#039;', '', $numero);
        $destinataire = preg_replace('/[^0-9]/', '', $numero);
        while ('0' == @$destinataire[0]) {
            $destinataire = substr($destinataire, 1);
        }

        if (is_numeric($destinataire)) {
            $len = strlen($destinataire);
            if (9 == $len || 8 == $len) {
                $render = '+'.$indicatif.$destinataire;
            } elseif (10 == $len or 11 == $len or 12 == $len or 13 == $len) {
                $render = '+'.$destinataire;
            }
        }

        return $render;
    }

    public static function sendProd($contract)
    {
        // $url = 'https://app.techsoft-web-agency.com/sms/api?action=send-sms&api_key=bWJhaDpNYmFoQDIwMTk=&to=PhoneNumber&from=SenderID&sms=YourMessage';
        $base_url = 'https://app.techsoft-web-agency.com/sms/api?action=send-sms';
        $sender = $contract['senderName'];
        $reciepient = $contract['reciepient'];
        $message = $contract['message'];
        $apiKey = $contract['apiKey'];

        //$url = $base_url.'&api_key='.$apiKey.'&to='.$reciepient.'&from='.$sender.'&sms='.$message;
        $url = 'https://app.techsoft-web-agency.com/sms/api';
        // Create SMS Body for request
        $sms_body = [
            'action' => 'send-sms',
            'api_key' => $apiKey,
            'to' => $reciepient,
            'from' => $sender,
            'sms' => $message,
        ];

        $send_data = http_build_query($sms_body);

        $gateway_url = $url.'?'.$send_data;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $gateway_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            $output = curl_exec($ch);

            if (curl_errno($ch)) {
                $output = curl_error($ch);
            }
            curl_close($ch);

            var_dump($output);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public static function sendSMS($contract)
    {
        $return = [];
        $return['results'] = false;
        $return['erreur'] = 0;
        $return['json'] = [];

        $url = 'http://193.105.74.159/api/v3/sendsms/json';
        $user = $contract['user'];
        $password = $contract['password'];
        $sender = $contract['senderName'];
        $reciepient = $contract['reciepient'];
        $message = $contract['message'];
        $data = [
            'authentication' => [
                'username' => $user,
                'password' => $password,
            ],
            'messages' => [
                'sender' => $sender,
                'text' => $message,
                'type' => 'longSMS',
                'recipients' => [
                    'gsm' => $reciepient,
                ],
            ],
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //Remote Location URL
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //parameters data
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Host:193.105.74.159', 'Accept:*/*', ]);
        $reponse_json = curl_exec($ch);

        if (empty($reponse_json)) {
            return ['results' => 0, 'erreur' => 'error', 'status' => 400];
        }
        curl_close($ch);
        $reponse_array = json_decode($reponse_json, true);
        $rep = $reponse_array['results'];
        $statut = $rep[0];
        if ('0' == $statut['status']) {
            $return['results'] = true;
            $return['status'] = '200';
        }
        //$return['status'] = $statut['status'];

        return $return;
    }

    public static function sendSMSJsonMany($contract, $contacts, $message = '')
    {
        $return = [];
        $recips = [];
        $return['results'] = false;
        $return['erreur'] = 0;
        $return['json'] = [];

        $url = 'http://193.105.74.159/api/v3/sendsms/json';
        $user = $contract['user'];
        $password = $contract['passworld'];
        $sender = $contract['senderName'];
        $message = $contract['message'];
        foreach ($contacts as $contact) {
            //array_push($recips,'gsm'.$contact);
            $recips2[] = ['gsm' => str_replace("\r", '', $contact)];
            // $recips='gsm:'.$contact;
        }
        $data = [
            'authentication' => [
                'username' => $user,
                'password' => $password,
            ],
            'messages' => [
                'sender' => $sender,
                'text' => $message,
                'type' => 'longSMS',
                'recipients' => $recips2,
            ],
        ];
        $params = '';
        foreach ($data as $key => $value) {
            $params .= $key.':'.$value.',';
        }

        $params = trim($params, '');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //Remote Location URL
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //parameters data
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Host:193.105.74.159', 'Accept:*/*', ]);
        $reponse_json = curl_exec($ch);

        if (empty($reponse_json)) {
            return ['results' => 0, 'erreur' => 13];
        }
        curl_close($ch);
        $reponse_array = json_decode($reponse_json, true);
        $rep = $reponse_array['results'];
        $statut = $rep[0];

        if ('0' == $statut['status']) {
            $return['results'] = true;
        }
        $return['status'] = $statut['status'];

        return $return;
    }

    public static function check_internet($domain)
    {
        $file = @fsockopen($domain, 80); //@fsockopen is used to connect to a socket

        return $file;
    }
}
