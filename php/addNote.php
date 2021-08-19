<?php
SESSION_START();

$connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

if ($connection->connect_error) {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

$userId = $connection->real_escape_string($_SESSION["user"]["userId"]);
$noteTitel = $connection->real_escape_string($_POST["noteTitel"]);
$noteContent = $connection->real_escape_string($_POST["noteContent"]);

$insert = "INSERT INTO note (userId, noteTitel,noteContent) 
           VALUES ('$userId', '$noteTitel','$noteContent')";

if ($connection->query($insert)) {
    header('Location: ../nota.php');
} else {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

$connection->close();
