<?php
include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");

// イベント削除
try {
  $stmt = $db->prepare('DELETE FROM events WHERE id = :id');
  $stmt->execute(array(':id' => $_GET["delete"])); ?>

  <!DOCTYPE html>
  <html lang="ja">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>Schedule | POSSE</title>
    <link rel="stylesheet" href="delete.css">
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
      <div class="delete__complete">
        <p class="delete__complete__text">削除完了しました！</p>
        <img class="checked" src="/img/checked.png" alt="チェックマーク">
      </div>
    </main>
  </body>
  </html>

<?php } catch (Exception $e) {
  echo 'エラーが発生しました。:' . $e->getMessage();
}
?>
<!-- 追加したイベントの一覧に戻るボタン -->
<a href="http://localhost/manage/eventlist/">追加したイベントの一覧ページに戻る</a>