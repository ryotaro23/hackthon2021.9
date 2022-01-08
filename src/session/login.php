<?php
session_start();
//DB内でPOSTされたメールアドレスを検索
try {
    include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");
    $email=$_POST['email'];
    $user_sql = "SELECT* FROM users WHERE email = \"$email\"";
    $user = $db->query($user_sql)->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
//emailがDB内に存在しているか確認
if (!isset($user['email'])) {
    echo 'メールアドレス又はパスワードが間違っています。';
    echo '<a href="/auth/login";>loginページへ戻る</a>';
    return false;
}
//パスワード確認後sessionにメールアドレスを渡す
if (password_verify($_POST['password'], $user['password'])) {
    session_regenerate_id(true); //session_idを新しく生成し、置き換える
    $_SESSION['ID'] = $user['id'];
    $_SESSION['NAME'] = $user['name'];
    $_SESSION['ADMIN'] = $user['admin'];
    $url = "http://".$_SERVER['HTTP_HOST'];
    header("Location: $url");
    exit;
} else {
    echo 'メールアドレス又はパスワードが間違っています。';
    echo '<a href="/auth/login";>loginページへ戻る</a>';
    return false;
}
