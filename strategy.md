# 作戦
## 全ページデザインおよび見た目完成
* 構成を理解した上で必要ページの作成
## db設計
* 全ページの構成の理解、デザインがある状態
* 作成はmrp編集はシュート
## イベントページとか
* 基本だいきに任せる  
→何かあったら解決します  
→アサインしたものの、タスクが絡むのであくまで目安です
## イベント一覧
* db設計が終わり次第着手
* issue1.2は秒@mrp
* issue3.4 $_SESSION ["user_id"]みたいなのからid抜いて、where
* issue5.6 データを一定の数づつ取れるように、超えたらページ2...  
→技術的にできそうな方でお願い
## ログイン画面
* 練習と同じものを使用
* $_SESSION ["hoge"]で取得
## イベント参加管理
* これ練習通りだから省略
## 通知機能
* うるせえやれ。
## 管理画面
* ページ作成から？  
→練習のやつ持ってくる？
* 練習通り
## 未回答
* うるせえやれ

SELECT events.name, events.start_at,users.name , event_attendance.status_id FROM events INNER JOIN event_attendance ON events.id = event_attendance.event_id INNER JOIN users ON event_attendance.user_id = users.id 
WHERE event_attendance.status_id =0
ORDER BY events.start_at

id分のデータを入れる
INSERT INTO `event_attendance`( `event_id`, `user_id`,status_id) 
SELECT 6,users.id,0 FROM users
