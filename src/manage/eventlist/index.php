<?php
include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");
include($_SERVER['DOCUMENT_ROOT'] . "/session/admin_session_check.php");
$stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE start_at >= CURRENT_DATE() AND user_id=1 AND status_id=1 GROUP BY events.id ORDER BY start_at;');
$events = $stmt->fetchAll();

function get_day_of_week($w)
{
  $day_of_week_list = ['日', '月', '火', '水', '木', '金', '土'];
  return $day_of_week_list["$w"];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Schedule | POSSE</title>
  <link rel="stylesheet" href="style.css">
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
        <div class="box py-2 header__add_user__button text-md font-bold mr-2 rounded-md shadow-md bg-white"><a href="/auth/signup">ユーザー追加</a></div>
      </div>
    </div>
  </header>

  <main class="bg-gray-100 px-5 h-screen">
    <div class="w-full mx-auto py-5">
      <div id="events-list">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-sm font-bold">一覧</h2>
        </div>

        <!-- ページング実装 -->
        <?php

        define('MAX', '10'); // 1ページの記事の表示数定義

        $All_events_number_sql = 'SELECT count(*)FROM events'; // トータルデータ件数
        $All_events_number = $db->query($All_events_number_sql)->fetch(PDO::FETCH_COLUMN); // イベントデータを配列に入れる

        $All_events = "SELECT*FROM events WHERE events.start_at >= CURDATE() ORDER BY events.start_at"; // イベントデータを引っ張る
        $event_contents = $db->query($All_events)->fetchAll(); // イベントデータを配列に入れる

        $participants_number_sql = "SELECT events.id , COUNT(event_attendance.user_id) as number, GROUP_CONCAT( ' \n', users.name ) as user_names FROM events
        INNER JOIN event_attendance ON events.id = event_attendance.event_id
        INNER JOIN status ON event_attendance.status_id = status.id
        INNER JOIN users ON event_attendance.user_id =  users.id
        WHERE  event_attendance.status_id = 1 AND CURDATE() <= events.start_at 
        GROUP BY events.id
        ORDER BY events.start_at"; // 選ばれたイベントデータを引っ張る
        $participants_number = $db->query($participants_number_sql)->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE); // イベントデータを配列に入れる

        // $events_num = count($All_events); // トータルデータ件数
        $max_page = ceil($All_events_number / MAX); // トータルページ数※floorは小数点をあげる関数

        if (!isset($_GET['page_id'])) { // $_GET['page_id'] はURLに渡された現在のページ数
          $now = 1; // 設定されてない場合は1ページ目にする
        } else {
          $now = $_GET['page_id'];
        }

        $start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか

        // array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
        $disp_data = array_slice($event_contents, $start_no, MAX, true);
        ?>
        <?php foreach ($disp_data as $event) : ?>
          <?php
          $start_date = strtotime($event['start_at']);
          $end_date = strtotime($event['end_at']);
          $day_of_week = get_day_of_week(date("w", $start_date));
          ?>
          <div class="bg-white mb-3 p-4 flex justify-between rounded-md shadow-md" id="event-<?php echo $event['id']; ?>">
            <div>
              <h3 class="font-bold text-lg mb-2"><?php echo $event['name'] ?></h3>
              <p><?php echo date("Y年m月d日（${day_of_week}）", $start_date); ?></p>
              <p class="text-xs text-gray-600">
                <?php echo date("H:i", $start_date) . "~" . date("H:i", $end_date); ?>
              </p>
            </div>
            <div class="flex flex-col justify-between text-right">
              <div>
                <?php if ($event['id'] % 3 === 1) : ?>
                  <?php echo date("m月d日", strtotime('-3 day', $end_date)); ?>
                <?php endif; ?>
              </div>

              <!-- コンマでユーザー名を１つ１つの文字列に変換して、それぞれをhtmlタグに挿入 -->
              <?php $participants_users = explode(",", $participants_number[$event['id']]["user_names"]); ?>
              <ul class="menu">
                <li class="menu__item">
                  <a class="text-sm menu__item__link js-menu__item__link"><span class="text-xl"><?= $participants_number[$event['id']]["number"] ?? 0; ?></span>人参加 ></a>
                  <ul class="submenu">
                    <?php foreach ($participants_users as  $participants_user) : ?>
                      <li class="submenu__item"><a><?php echo $participants_user; ?></a></li>
                    <?php endforeach ?>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
          <div class="edit__buttons">
            <form class="edit__buttons__form" action="/manage/eventadd/eventform.php?event_id=<?= $event["id"] ?>" method="post">
              <input type='submit' value="変更する" class="flex-1 bg-blue-500 py-2 rounded-3xl text-white text-lg font-bold edit__buttons__form__input">
              <input type="hidden" name="id" value="<?= $event['id'] ?>">
            </form>
            <form class="edit__buttons__form" action="/manage/eventlist/delete.php">
              <input type='submit' value="削除する" class="flex-1 bg-blue-500 py-2 rounded-3xl text-white text-lg font-bold edit__buttons__form__input">
              <input type="hidden" name="delete"  value="<?= $event['id'] ?>">
            </form>
          </div>
      </div>
    <?php endforeach; ?>

    <div class="pager-num">
      <?php
      for ($i = 1; $i <= $max_page; $i++) { // 最大ページ数分リンクを作成
        if ($i == $now) { ?>
          <span class="blue-word page-number"> <?php echo $now . '　'; ?> </span>
        <?php  } else {
          echo '<a class = "page-number" href="/manage/eventlist/index.php?page_id=' . $i . '")>' . $i . '</a>'; ?>
      <?php }
      } ?>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="/js/manage.js"></script>
</body>

</html>