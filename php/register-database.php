<?php
SESSION_START();

$connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

if ($connection->connect_error) {
    header('Location: ../index.php?errorDB');
    $_SESSION["login"] = 0;
    exit;
}

if (!empty($_POST["submitReg"])) {

    $username = $connection->real_escape_string($_POST["reg-username"]);
    $password = $connection->real_escape_string($_POST["reg-password1"]);

    if (strcmp($password, $connection->real_escape_string($_POST["reg-password2"])) != 0) {
        $connection->close();
        header('Location: ../index.php?success=false&pwdError=true');
        exit;
    }

    if (strlen($password) < 10) {
        $connection->close();
        header('Location: ../index.php?success=false&pwdShort=true');
        exit;
    }

    $password = 'notasec' . $password;

    $insertUser = "INSERT INTO user (username, password)
                   VALUES ('$username', md5('$password'));";

    if ($result = $connection->query($insertUser)) {

        header('Location: ../index.php?success=true');
    } else {
        header('Location: ../index.php?success=false&nameError=true');
    }
} else {
    header('Location: ../index.php?success=false');
}
$connection->close();