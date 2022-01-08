<?php
include($_SERVER['DOCUMENT_ROOT'] . "/session/admin_session_check.php");
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="useradd.css">
    <title>Schedule | POSSE</title>
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

    <main class="bg-gray-100 h-screen">
        <div class="w-full mx-auto py-10 px-5">
            <h2 class="text-md font-bold mb-5">サインアップ</h2>
            <label>（登録済みののメールアドレスを入力すると上書きができます。）</label>
            <form action="/session/signup.php" method="POST">
                <label>name</label>
                <input name="name" class="w-full p-4 text-sm mb-3">
                <label for="email">email</label>
                <input name="email" type="email" placeholder="メールアドレス" class="w-full p-4 text-sm mb-3">
                <label for="password">password</label>
                <label>パスワードは英数字含む8文字以上 </label>
                <input name="password" type="password" placeholder="パスワード" class="w-full p-4 text-sm mb-3">
                <label for="password">githubのID(ない場合は何も書かない)</label>
                <input name="github_id" placeholder="ID" class="w-full p-4 text-sm mb-3">
                <label for="password">slackのID(ない場合は何も書かない)</label>
                <input name="slack_id" placeholder="ID" class="w-full p-4 text-sm mb-3">
                <input type="submit" value="サインアップ" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300">
            </form>
        </div>
    </main>
</body>

</html>
