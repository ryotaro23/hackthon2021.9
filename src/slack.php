<?php
$url = "https://hooks.slack.com/services/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";
$message = [
    'channel' => '#ダークライ',
    'username' => 'System',
    'text' => 'これはテストです<@U01C72Q45MJ>',
];

$ch = curl_init();
$options = [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'payload' => json_encode($message)
    ])
];
curl_setopt_array($ch, $options);
curl_exec($ch);
curl_close($ch);