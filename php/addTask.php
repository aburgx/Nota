<?php
SESSION_START();

$connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

if ($connection->connect_error) {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

if (!empty($_POST["submitAddTask"])) {

    $userid = $connection->real_escape_string($_SESSION["user"]["userId"]);
    $taskContent = $connection->real_escape_string($_POST["taskContent"]);

    $insert = "INSERT INTO task (userId, taskContent) 
               VALUES ('$userid', '$taskContent')";

    if ($connection->query($insert)) {
        header('Location: ../nota.php');
    } else {
        header('Location: ../index.php?errorDB');
        $_SESSION["login"] = 0;
        exit;
    }
} else {
    header('Location: ../nota.php');
}

$connection->close();
