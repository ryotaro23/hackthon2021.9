<?php
require('dbconnect.php');
include($_SERVER['DOCUMENT_ROOT'] . "/session/session_check.php");
function get_day_of_week($w)
{
  $day_of_week_list = ['日', '月', '火', '水', '木', '金', '土'];
  return $day_of_week_list["$w"];
}
$user_id = $_SESSION["ID"];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/style.css">
  <title>Schedule | POSSE</title>
</head>

<body>
  <header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
        <a href="/"><img src="/img/header-logo.png" alt="" class="h-full"></a>
      </div>
      <div>
        <?php if ($_SESSION["ADMIN"] == 1) : ?>
          <a href="/manage/eventlist" class="text-white bg-blue-400 px-4 py-2 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-200">管理画面へ</a>
        <?php endif ?>
        <a href="/session/logout.php" class="text-white bg-blue-400 px-4 py-2 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-200">ログアウト</a>
      </div>
    </div>
  </header>

  <main class="bg-gray-100 h-screen">
    <div class="w-full mx-auto p-5">
      <div id="filter" class="mb-8">
        <h2 class="text-sm font-bold mb-3">フィルター</h2>
        <?php ?>
        <div class="flex">
          <a href="/index.php/?page_id=1" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md <?= !$_GET['status_id'] && $_GET['status_id'] != "0" ? "bg-blue-600 text-white" : "bg-white" ?> ">全て</a>
          <a href="/index.php/?page_id=1&status_id=1" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md <?= $_GET['status_id'] == 1 ? "bg-blue-600 text-white" : "bg-white" ?> ">参加</a>
          <a href="/index.php/?page_id=1&status_id=2" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md <?= $_GET['status_id'] == 2 ? "bg-blue-600 text-white" : "bg-white" ?>">不参加</a>
          <a href="/index.php/?page_id=1&status_id=0" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md <?= $_GET['status_id'] == "0" ? "bg-blue-600 text-white" : "bg-white" ?>">未回答</a>
        </div>
      </div>

      <?php
      //全てをstatus_id=Null,参加status_id=1,不参加status_id=2,未回答status_id=0,未選択status_id=noneで場合分け（ステータスによって表示変更）
      $get_status_id = $_GET['status_id'];
      ?>
      <div id="events-list">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-sm font-bold">一覧</h2>
        </div>
        <!-- ページング実装 -->
        <?php
        define('MAX', '10'); // 1ページの記事の表示数定義
        $Selected_events_number = $db->query("SELECT  count(*) FROM events WHERE CURDATE() <= start_at")->fetch(PDO::FETCH_COLUMN); // 選ばれたイベントデータを配列の数
        if ($get_status_id == "none" || $get_status_id == null) {
          $status_sql = "";
          $get_status_id == "none";
        } else {
          $status_sql = "AND event_attendance.status_id = $get_status_id";
        }

        // イベントデータおよび、ユーザーのステータス取得
        $Selected_All_events = "SELECT events.id, events.name, events.start_at, events.end_at , status.id as status_id , status.name as status_name FROM events
        INNER JOIN event_attendance ON events.id = event_attendance.event_id
        INNER JOIN status ON event_attendance.status_id = status.id
        WHERE  event_attendance.user_id = $user_id AND CURDATE() <= events.start_at $status_sql
        ORDER BY events.start_at"; // 選ばれたイベントデータを引っ張る
        $event_contents = $db->query($Selected_All_events)->fetchAll(); // イベントデータを配列に入れる

        // 参加人数取得
        $participants_number_sql = "SELECT events.id , COUNT(event_attendance.user_id) as number, GROUP_CONCAT( ' \n', users.name ) as user_names FROM events
        INNER JOIN event_attendance ON events.id = event_attendance.event_id
        INNER JOIN status ON event_attendance.status_id = status.id
        INNER JOIN users ON event_attendance.user_id =  users.id
        WHERE  event_attendance.status_id = 1 AND CURDATE() <= events.start_at 
        GROUP BY events.id
        ORDER BY events.start_at"; // 選ばれたイベントデータを引っ張る
        $participants_number = $db->query($participants_number_sql)->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE); // イベントデータを配列に入れる


        // $events_num = count($All_events); // トータルデータ件数
        $max_page = ceil($Selected_events_number / MAX); // トータルページ数※ceilは小数点をあげる関数

        if (!isset($_GET['page_id'])) { // $_GET['page_id'] はURLに渡された現在のページ数
          $now = 1; // 設定されてない場合は1ページ目にする
        } else {
          $now = $_GET['page_id'];
        }

        $start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか

        // array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
        $display_data = array_slice($event_contents, $start_no, MAX, true);
        ?>
        <?php foreach ($display_data as $event) : ?>
          <?php
          $start_date = strtotime($event['start_at']);
          $end_date = strtotime($event['end_at']);
          $day_of_week = get_day_of_week(date("w", $start_date));
          ?>
          <div class="modal-open bg-white mb-3 p-4 flex justify-between rounded-md shadow-md cursor-pointer" id="event-<?= $event['id']; ?>">
            <div>
              <h3 class="font-bold text-lg mb-2"><?php echo $event['name'] ?></h3>
              <p><?php echo date("Y年m月d日（${day_of_week}）", $start_date); ?></p>
              <p class="text-xs text-gray-600">
                <?php echo date("H:i", $start_date) . "~" . date("H:i", $end_date); ?>
              </p>
            </div>
            <div class="flex flex-col justify-between text-right">
              <div>
                <?php switch ($event["status_id"]):
                  case 0: ?>
                    <p class="text-sm font-bold text-yellow-400"><?= $event["status_name"] ?></p>
                    <p class="text-xs text-yellow-400">期限 <?php echo date("m月d日", strtotime('-3 day', $end_date)); ?></p>
                    <?php break; ?>
                  <?php
                  case 1: ?>
                    <p class="text-sm font-bold text-green-400"><?= $event["status_name"] ?></p>
                    <?php break; ?>
                  <?php
                  case 2: ?>
                    <p class="text-sm font-bold text-gray-400"><?= $event["status_name"] ?></p>
                    <?php break; ?>
                <?php endswitch; ?>
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
        <?php endforeach; ?>

        <div class="pager-num">
          <?php for ($i = 1; $i <= $max_page; $i++) { ?>
            <!--  最大ページ数分リンクを作成 -->
            <?php if ($i == $now) { ?>
              <!-- 現在表示中のページ数の場合はリンクを貼らない -->
              <span class="bule_word page-number"><?php echo $now; ?></span>
            <?php
            } else { ?>
          <?php echo '<a class="page-number"  href="/?page_id=' . $i . '&status_id=' . $get_status_id . '">' . $i . '</a>';
            }
          } ?>
        </div>
      </div>
  </main>

  <div class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="modal-overlay absolute w-full h-full bg-black opacity-80"></div>

    <div class="modal-container absolute bottom-0 bg-white w-screen h-4/5 rounded-t-3xl shadow-lg z-50">
      <div class="modal-content text-left py-6 pl-10 pr-6">
        <div class="z-50 text-right mb-5">
          <svg class="modal-close cursor-pointer inline bg-gray-100 p-1 rounded-full" xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 18 18">
            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
          </svg>
        </div>

        <div id="modalInner"></div>

      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="/js/main.js"></script>
</body>

</html>