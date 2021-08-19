<?php
SESSION_START();

$connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

if ($connection->connect_error) {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}
$taskId = $_GET["taskId"];
$userId = $connection->real_escape_string($_SESSION["user"]["userId"]);

$delete = "DELETE FROM task 
           WHERE taskId = '$taskId' AND userId = '$userId'";

if ($connection->query($delete)) {
    header('Location: ../nota.php');
} else {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

$connection->close();
