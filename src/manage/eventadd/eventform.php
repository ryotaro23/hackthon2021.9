<?php include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");
include($_SERVER['DOCUMENT_ROOT'] . "/session/admin_session_check.php");
$eventId = $_GET['event_id'];
?>
<?php if (isset($eventId)) { ?>
    <?php
    $detail_contents_value = "SELECT* FROM events INNER JOIN event_details ON events.id = event_details.event_id WHERE events.id = $eventId";
    $detail_contents = $db->query($detail_contents_value)->fetch();
    
    // event
    $events_value = "SELECT * FROM events WHERE events.id = $eventId";
    $events = $db->query($events_value)->fetch();
  } ?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Schedule | POSSE</title>
  <link rel="stylesheet" href="index.css">
</head>

<body class="bg-gray-100 h-screen">
  <header class="bg-white h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full ">
      <a href="/manage/eventlist/index.php"><img src="/img/header-logo.png" alt="posseロゴ" class="h-full"></a> 
      </div>
      <div class="box1">
        <div class="box px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/manage/eventlist/index.php">イベント一覧</a></div>
        <div class="box px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/manage/eventadd/eventform.php">イベント追加</a></div>
        <div class="box px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/">ユーザー画面へ</a></div>
        <div class="box py-2 header__add_user__button text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/auth/signup">ユーザー追加</a></div>
      </div>
    </div>
  </header>
  <main class="bg-gray-100 h-screen">
    <div class="form ">
      <h2 class="title">新規イベント追加</h2>
      <form class="event__add__form bg-gray-100 h-screen" action="/manage/eventadd/eventadd.php" method="post">
        <p class="sub">イベント名</p>
        <input class="event__add__form__event__name  event__add__form__item" type="textarea" name="name" value="<?= $events['name'] ?>">
        <p class="sub">開始日時</p>
        <input placeholder="2020-08-09 20:00:00" class="event__add__form__event__date event__add__form__item" type="text" name="start_at" value="<?= $events['start_at'] ?>">
        <p class="sub">終了日時</p>
        <input placeholder="2020-08-09 22:00:00" class="event__add__form__event__date event__add__form__item" type="text" name="end_at" value="<?= $events['end_at'] ?>">
        <p class="sub">イベント詳細</p>
        <textarea class="event__add__form__event__detail" name="text" rows="7" cols="150"><?= $detail_contents["text"] ?></textarea>
        <input type="submit" value="送信" class="event__add__form__button w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300">
        <input type="hidden" value="<?php echo  $eventId; ?>" name="id">
      </form>
    </div>
    <?= $_POST['event_name'] ?>
  </main>
</body>

</html>