<?php

function android($data, $reg_id, $key)
{
    $url = 'https://android.googleapis.com/gcm/send';
    $message = array(
        'title' => 'MyanCash',
        'message' => $data,
        'subtitle' => '',
        'tickerText' => '',
        'msgcnt' => 1,
        'vibrate' => 1
    );

    $headers = array(
        'Authorization: key=' . $key,
        'Content-Type: application/json'
    );

    $fields = array(
        'registration_ids' => array($reg_id),
        'data' => $message,
    );

    useCurl($url, $headers, json_encode($fields));
}


function useCurl($url, $headers, $fields = null)
{
    $ch = curl_init();
    if ($url) {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($fields) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }

        curl_exec($ch);
        curl_close($ch);
    }
}

function iOS($data, $devicetoken, $pem)
{
    $passphrase = '';
    $deviceToken = $devicetoken;
    $ctx = stream_context_create();

    stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

    $fp = stream_socket_client(
        'ssl://gateway.push.apple.com:2195', $err,
        $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
    if (!$fp)
        exit("Failed to connect: $err $errstr" . PHP_EOL);

    $body['aps'] = array(
        'alert' => array(
            'title' => 'MyanCash',
            'body' => $data,
        ),
        'sound' => 'default'
    );

    $payload = json_encode($body);
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
    fwrite($fp, $msg, strlen($msg));
    fclose($fp);
}