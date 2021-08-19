<?php

SESSION_START();

$connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

if ($connection->connect_error) {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

$userId = $_SESSION["user"]["userId"];
$friendId = $connection->real_escape_string($_POST["sendNotefriendId"]);
$noteTitel = $connection->real_escape_string($_POST["sendNoteTitle"]);
$noteContent = $connection->real_escape_string($_POST["sendNoteContent"]);

$select = "SELECT friend.friendId
           FROM friend
              JOIN user_friend ON friend.friendId = user_friend.friendId
           WHERE friend.userId = '$userId'
           AND user_friend.userId = '$friendId';";

if ($result = $connection->query($select)) {

    $friendIdOfUser = $result->fetch_assoc()["friendId"];

    $insert = "INSERT INTO note(userId, friendId, noteTitel, noteContent) 
           VALUES('$friendId', '$friendIdOfUser', '$noteTitel', '$noteContent')";

    if ($connection->query($insert)) {
        header('Location: ../nota.php');
    } else {
        header('Location: ../nota.php?errorSendingNote');
    }
} else {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}



$connection->close();