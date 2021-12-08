<?php

$link = mysqli_connect('192.168.0.100','root','password','project') or die(mysqli_connect_error());

// запрос к базе данных
$result = mysqli_query($link,"SELECT * FROM categories") or die(mysqli_connect_error());
while ($result_assoc = mysqli_fetch_assoc($result)) {
    print_r($result_assoc);
}




$email = "taronsarkisyan@gmail.com";
$password = "lanasark";

    if ($_POST['email']==$email and $_POST['password']==$password) {
        echo "авторизация успешна";
    } else {
        echo "вы ввели неверный емейл или пароль";
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Привет,мир!</title>
</head>
<body>

<form method="POST" action="/" accept-charset="utf-8">
    <input type="email" name="email">
    <input type="password" name="password">
    <button>OK</button>

</form>



</body>