<?php
SESSION_START();

$connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

if ($connection->connect_error) {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

$_SESSION["login"] = 0;
if (!empty($_POST["submitLogin"])) {

    $username = $connection->real_escape_string($_POST["login-username"]);
    $password = $connection->real_escape_string($_POST["login-password"]);

    $password = "notasec" . $password;

    $select = "SELECT * 
               FROM user
               WHERE username='$username' AND password=md5('$password')
               LIMIT 1";

    if ($result = $connection->query($select)) {

        if ($result->num_rows > 0) {
            $_SESSION["login"] = 1;
            $_SESSION["user"] = $result->fetch_assoc();
            header('Location: ../nota.php');
        } else {
            header('Location: ../index.php?loginError=true');
            exit;
        }
        $result->close();

    } else {
        header('Location: ../index.php?errorDB');
        $_SESSION["login"] = 0;
        exit;
    }
}

$connection->close();

if ($_SESSION["login"] != 1) {
    header('Location: ../index.php');
    exit;
}
