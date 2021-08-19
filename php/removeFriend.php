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
$IdOfFriend = $connection->real_escape_string($_POST["checkbox"]);

$select = "SELECT user_friend.friendId
               FROM user_friend
                  JOIN friend ON user_friend.friendId = friend.friendId
               WHERE friend.userId = '$IdOfFriend'
               AND user_friend.userId = '$userId';";

if ($result = $connection->query($select)) {

    $friendId = $result->fetch_assoc()["friendId"];

    $delete = "DELETE FROM user_friend 
               WHERE userId = '$userId' 
               AND friendId = '$friendId'";

    if($connection->query($delete)) {

        $delete = "DELETE FROM friend
                   WHERE friendId = '$friendId'
                   AND userId = '$IdOfFriend'";

        if (!$connection->query($delete)) {
            header('Location: ../index.php?errorDB');
            $_SESSION["login"] = 0;
            exit;
        }

    } else {
        header('Location: ../index.php?errorDB');
        $_SESSION["login"] = 0;
        exit;
    }

} else {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}
header('Location: ../nota.php');
$connection->close();
