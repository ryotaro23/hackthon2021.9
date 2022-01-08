<?php
//データベースへ接続、テーブルがない場合は作成
try {
    include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
//パスワードの正規表現
if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {
    echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
    echo '<a href="/auth/ressetpassword/send_resetmail/">再設定ページへ戻る</a>';
    return false;
}
//登録処理
try {
    $sql = "INSERT INTO users ( name , password , email , github_id ,slack_id) VALUES (:name , :password , :email , :github_id ,:slack_id) ON DUPLICATE KEY 
    UPDATE password = :password , reset_pass = null";
    $stmt = $db->prepare($sql);
    $params = array(':name' => $_POST["name"] , ':password' => $password , ':email' => $_POST["email"] , ':github_id'=> $_POST["github_id"] ,'slack_id' => $_POST["slack_id"]);
    $stmt->execute($params);
    // echo '登録完了';
    // echo '<a href="/auth/login">ログインページへ戻る</a>';
} catch (\Exception $e) {
    echo 'エラーまたは登録済みのemailです。';
    echo '<a href="/auth/login">ログインページへ戻る</a>';
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
  <link rel="stylesheet" href="logout.css">
</head>

<body>
  <header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
      <a href="/manage/eventlist/index.php"><img src="/img/header-logo.png" alt="posseロゴ" class="h-full"></a> 
      </div>
      <div class="box1">
      <a href="/auth/login/index.php" class="text-white bg-blue-400 px-4 py-2 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-200">ログイン</a>
      </div>
    </div>
  </header>
  <main class="bg-gray-100 h-screen">
    <h1>登録完了しました。</h1>
    <img class="checked" src="/img/checked.png" alt="">
  </main>
</body>

</html>
