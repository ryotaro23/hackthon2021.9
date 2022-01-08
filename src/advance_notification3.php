<?php
include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");
mb_language('ja');
mb_internal_encoding('UTF-8');

$stmt = $db->query("SELECT events.name, events.start_at , event_details.text , users.name as user_name , users.email , users.slack_id FROM events
INNER JOIN event_attendance ON events.id = event_attendance.event_id
INNER JOIN event_details  ON events.id = event_details.event_id
INNER JOIN users ON event_attendance.user_id = users.id
WHERE event_attendance.status_id = 0 AND events.start_at >= DATE_ADD(CURDATE(), INTERVAL 3 DAY)  AND events.start_at <  DATE_ADD(CURDATE(), INTERVAL 4 DAY)
ORDER BY events.id");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$events) {
    exit;
}
$page_url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"]."/";
foreach ($events as $event) {
    $to = $event["email"];
    $subject = $event["name"];
    $body = $event["text"];
    $headers = ["From" => "system@posse-ap.com", "Content-Type" => "text/plain; charset=UTF-8", "Content-Transfer-Encoding" => "8bit"];

    $name = $event["user_id"];
    $date = $event["start_at"];
    $event_name = $event["name"];
    $body = <<<EOT
    {$name}さん
    
    ${date}に${event_name}を開催します。
    参加／不参加の回答をお願いします。
    
    ${page_url}
    EOT;
    mb_send_mail($to, $subject, $body, $headers);
}


$slack_text = "";
$event_name_list = array();
foreach($events as $event){
    if (!in_array($event["name"],$event_name_list)) {
        array_push($event_name_list,$event["name"]);
        $slack_text =$slack_text."\n" . $event["start_at"]."に".$event["name"]."をやるから回答してね\n【詳細】\n".$event["text"]."\n";
    }
    $slack_text = $slack_text."<@".$event["slack_id"].">";
}

$text = <<<EOT
${slack_text}

${page_url}
EOT;

$url = "https://hooks.slack.com/services/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";
$message = [
    'channel' => '#ダークライ',
    'username' => 'System',
    'text' => $text,
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

echo "メールを送信しました";
