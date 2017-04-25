<?php

namespace App;

use Carbon\Carbon;

class CheckSsl
{
    public static function check($url)
    {
        if(isset($url)){
            try{
                $url = 'http://' . $url;
                $originalParse = parse_url($url, PHP_URL_HOST);
                $get = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
                $read = stream_socket_client("ssl://".$originalParse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
                $cert = stream_context_get_params($read);
                $certInfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
            }
            catch (\ErrorException $e){
                return false;
            }

            $timeFrom = Carbon::createFromTimestamp($certInfo['validFrom_time_t']);
            $timeTo = Carbon::createFromTimestamp($certInfo['validTo_time_t']);

            $issuer = 'Issuer: ' . $certInfo['issuer']['CN'];
            $valid =  $timeFrom < Carbon::now() && $timeTo > Carbon::now();
            $expire = $valid ? 'Expired In: ' . $timeTo->diffInDays(Carbon::now()) . ' days' : ' Expired.';
            $valid = 'Is valid: ' . var_export($valid, true);

            return compact('issuer', 'valid', 'expire');
        }
        return false;
    }
}
