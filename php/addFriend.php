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
$friendName = $connection->real_escape_string($_POST["friendName"]);

$selectUsername = "SELECT username
                   FROM user
                   WHERE userId='$userId'";

if ($result = $connection->query($selectUsername)) {

    if (strtolower($result->fetch_assoc()["username"]) === strtolower($friendName)) {
        header('Location: ../nota.php?errorAddedSelfasFriend');
        exit;
    }

} else {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

$selectFriendNames = "SELECT username
                      FROM user
                      WHERE userId IN (
                          SELECT friend.userId
                          FROM friend
                            JOIN user_friend ON friend.friendId = user_friend.friendId
                          WHERE user_friend.userId = '$userId'
                      );";

if ($result = $connection->query($selectFriendNames)) {

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_row()) {

            if (strtolower($row[0]) === strtolower($friendName)) {
                header('Location: ../nota.php?errorUserAlreadyInFriendList');
                $connection->close();
                exit;
            }
        }

    }

} else {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

$selectFriendId = "SELECT userId 
                   FROM user 
                   WHERE username = '$friendName'";
if ($result = $connection->query($selectFriendId)) {

    if ($result->num_rows > 0) {

        $idOfFriendArray = $result->fetch_row();

        $idOfFriend = $idOfFriendArray[0];

        $insert = "INSERT INTO friend (userId) 
                   VALUE ('$idOfFriend')";

        if ($result = $connection->query($insert)) {

            $autoGenId = $connection->insert_id;

            $insert = "INSERT INTO user_friend (userId, friendId) 
                       VALUES ('$userId','$autoGenId');";

            if ($result = $connection->query($insert)) {
                header('Location: ../nota.php');
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

    } else {
        header('Location: ../nota.php?errorUserNotFound');
    }
} else {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}
$connection->close();
