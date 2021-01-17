<?php

namespace App\Util;

use Symfony\Component\Config\Definition\Exception\Exception;

class UtilServer
{
    public static function getUser($contract){
        $base_url = $contract['url'];
        $userKey = $contract['userKey'];
        $passKey = $contract['passKey'];
        $output = null;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $base_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-AUTH-USER:'.$userKey, 'X-AUTH-TOKEN:'.$passKey, ]);
            $output = curl_exec($ch);

            if (curl_errno($ch)) {
                $output = curl_error($ch);
            }
            curl_close($ch);

           // var_dump($output);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return $output;
    }
    public static function sendProd($contract)
    {
        $base_url = $contract['url'];
        $sender = $contract['senderName'];
        $reciepient = $contract['reciepient'];
        $message = $contract['message'];
        $userKey = $contract['userKey'];
        $passKey = $contract['passKey'];

        // Create SMS Body for request
        $sms_body = [
            'senderName' => $sender,
            'numberTo' => $reciepient,
            'message' => $message,
        ];
        $output = null;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $base_url); //Remote Location URL
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sms_body)); //parameters data
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-AUTH-USER:'.$userKey, 'X-AUTH-TOKEN:'.$passKey, ]);
            $output = curl_exec($ch);

            /*if (curl_errno($ch)) {
                $output = curl_error($ch);
            }*/
            curl_close($ch);

           // die($output);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $output;
    }
    public static function sendMultipleProd($contract)
    {
        $base_url = $contract['url'];
        /*$sender = $contract['senderName'];
        $reciepient = $contract['reciepient'];*/
        $data = $contract['data'];
        $userKey = $contract['userKey'];
        $passKey = $contract['passKey'];

        // Create SMS Body for request
        /*$sms_body = [
            'senderName' => $sender,
            'numberTo' => $reciepient,
            'message' => $message,
        ];*/
        $output = null;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $base_url); //Remote Location URL
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //parameters data
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-AUTH-USER:'.$userKey, 'X-AUTH-TOKEN:'.$passKey, ]);
            $output = curl_exec($ch);

            /*if (curl_errno($ch)) {
                $output = curl_error($ch);
            }*/
            curl_close($ch);

            // die($output);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }

        return $output;
    }
}
