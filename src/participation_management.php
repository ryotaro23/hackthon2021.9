<?php
require('dbconnect.php');

$eventId_data = $_POST['event_id'];
$userId_data = $_POST['user_id'];
$statusId_data =  $_POST['status_id'];

if (isset($statusId_data)) {
  // INSERT文を変数に格納
  $sql = "INSERT INTO event_attendance ( event_id , user_id , status_id , created_at ) 
          VALUES (:event_id , :user_id , :status_id , now())
          ON DUPLICATE KEY UPDATE 
          status_id = :status_id , updated_at = now()";

  // 挿入する値は空のまま、SQL実行の準備をする
  $stmt = $db->prepare($sql);

  // 挿入する値を配列に格納する
  $params = array(':event_id' => $eventId_data, ':user_id' => $userId_data, ':status_id' => $statusId_data);

  // 挿入する値が入った変数をexecuteにセットしてSQLを実行
  $stmt->execute($params);
}

$url = "http://".$_SERVER['HTTP_HOST'].'/';
header("Location: $url");
exit;