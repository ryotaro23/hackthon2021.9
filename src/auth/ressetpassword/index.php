<?php
include($_SERVER['DOCUMENT_ROOT'] . "/dbconnect.php");
$pass = $_GET["pass"];
$sql = "SELECT * FROM users WHERE  reset_pass = \"$pass\" ";
$user = $db->query($sql)->fetch();
if (!isset($user["email"])) {
    echo "無効なurlです";
    echo'<a href="/auth/login">loginページへ戻る</a>';
    exit;
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
</head>

<body>
    <header class="h-16">
        <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
            <div class="h-full">
                <img src="/img/header-logo.png" alt="" class="h-full">
            </div>
        </div>
    </header>

    <main class="bg-gray-100 h-screen">
        <div class="w-full mx-auto py-10 px-5">
            <h2 class="text-md font-bold mb-5">パスワード再設定</h2>
            <form action="/session/signup.php" method="POST">
                <input name="name" type="hidden" class="w-full p-4 text-sm mb-3" value="<?=$user['name']?>">
                <input name="email" type="hidden" placeholder="メールアドレス" class="w-full p-4 text-sm mb-3" value="<?=$user['email']?>">
                <label for="password">password</label>
                <input name="password" type="password" placeholder="パスワード" class="w-full p-4 text-sm mb-3">
                <label>パスワードは英数字8文字以上、アルファベットと数字を含んでください</label>
                <input type="submit" value="登録する" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300">
            </form>
        </div>
    </main>
</body>

</html>