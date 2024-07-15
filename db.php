<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'my_db');

$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysql->connect_errno) {
    exit("Ошибка подключения: " . $mysql->connect_error);
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$message = $_POST['message'];

//Проверка существует ли введенная почта уже в базе данных
$emailСheck = $mysql->prepare("SELECT COUNT(*) FROM feedback WHERE email = ?");
$emailСheck->bind_param("s", $email);
$emailСheck->execute();
$emailСheck->bind_result($email_count);
$emailСheck->fetch();

if ($email_count > 0) {
    echo
    '<script>
    const button = document.getElementById("myButton");
    button.style.display = "none";
    </script>';
    //"Почта уже существует в базе данных. Пожалуйста, введите другую почту.";
    exit;
}

$emailСheck->close();

if ($stmt = $mysql->prepare("INSERT INTO feedback (name, phone, email, message) VALUES (?, ?, ?, ?)")) {

$stmt->bind_param("ssss", $name, $phone, $email, $message);


if ($stmt->execute()) {
    echo "Данные успешно сохранены.";

    $to = "05062018kostroma@gmail.com";
    $subject = "Новое сообщение с формы обратной связи";
    $body = "Имя: $name\nТелефон: $phone\nEmail: $email\nСообщение: $message";
    $headers = "Content-type:text/html; charset = windows-1251 \r\n";
    $headers = "From: kotobus02@gmail.com";
    mail($to, $subject, $body, $headers);
} 
else {
    echo "Ошибка сохранения данных: " . $stmt->error;
}
$stmt->close();
} else {
    echo "Ошибка подготовки запроса: " . $mysql->error;
}
?>
