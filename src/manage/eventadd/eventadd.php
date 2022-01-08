<?php
include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");
include($_SERVER['DOCUMENT_ROOT'] . "/session/admin_session_check.php");

// eventsに追加
$sql = "INSERT INTO events (id , name , start_at , end_at) 
          VALUES (:id , :name , :start_at , :end_at)
          ON DUPLICATE KEY UPDATE 
          name = :name , start_at = :start_at , end_at = :end_at";

$stmt = $db->prepare($sql);

$params = array(':id' => (int) $_POST["id"] , ':name' => $_POST["name"], ':start_at' => $_POST["start_at"], ':end_at' => $_POST["end_at"]);

$stmt->execute($params);

// イベントの詳細追加
if ($_POST['id']) {
  $eventId = $_POST['id'];
} else {
  $eventId = $db->lastInsertId();
  $sql = "INSERT INTO `event_attendance`( `event_id`, `user_id`,status_id) 
  SELECT :id , users.id,0 FROM users";
  $stmt = $db->prepare($sql);
  $params = array(':id' => $eventId);
  $stmt->execute($params);
}

$sql = "INSERT INTO event_details ( id , event_id , text )
        VALUES (:id , :event_id , :text )
        ON DUPLICATE KEY UPDATE text = :text";

$stmt = $db->prepare($sql);

$params = array(':id' => (int)$_POST["id"], ':event_id' => $eventId, ':text' => $_POST["text"]);

$stmt->execute($params);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Schedule | POSSE</title>
  <link rel="stylesheet" href="eventadd.css">
</head>
<body>
  <header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
      <a href="/manage/eventlist/index.php"><img src="/img/header-logo.png" alt="posseロゴ" class="h-full"></a> 
      </div>
      <div class="box1">
        <div class="box px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/manage/eventlist/index.php">イベント一覧</a></div>
        <div class="box px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/manage/eventadd/eventform.php">イベント追加</a></div>
        <div class="box px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/">ユーザー画面へ</a></div>
        <div class="box px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/auth/signup">ユーザー追加</a></div>
      </div>
    </div>
  </header>
  <main class="bg-gray-100 h-screen">
    <h1>登録完了しました！</h1>
    <img class="checked" src="/img/checked.png" alt="">
  </main>
</body>
</html>
