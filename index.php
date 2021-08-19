<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nota</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="shortcut icon" type="image/png" href="images/logo-blueblack-small.png"/>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style-index.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script src="js/nota.js"></script>
    <?php

    SESSION_START();

    $_SESSION["database"]["host"] = "localhost";
    //    $_SESSION["database"]["username"] = "root";
    $_SESSION["database"]["username"] = "web";
    //    $_SESSION["database"]["password"] = "";
    $_SESSION["database"]["password"] = "sqlpass27";
    $_SESSION["database"]["dbname"] = "nota";

    if (isset($_SESSION["login"])) {
        if ($_SESSION["login"] == 1) {
            header('Location: nota.php');
            exit;
        }
    }

    ?>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" id="me-navbar">
    <a class="navbar-brand ml-2" href="">
        <img src="images/logo-blueblack.png" width="40" height="40" class="d-inline-block align-middle" alt="">
        <span class="align-middle ml-2" style="font-size: 1.1em">Nota</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle btn btn-primary btn-lg me-login-button px-4" href="#"
                   id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Login
                </a>
                <div class="dropdown-menu dropdown-menu-right mt-3" id="me-dropdown" aria-labelledby="navbarDropdown">
                    <div class="dropdown-item" id="me-dropdown-item">
                        <form method="post" action="php/login-database.php">
                            <div class="form-group">
                                <label for="login-username">Benutzername</label>
                                <input name="login-username" type="text" class="form-control" id="login-username" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="login-password">Passwort</label>
                                <input name="login-password" type="password" class="form-control" id="login-password">
                            </div>
                            <input name="submitLogin" type="submit" class="btn btn-primary" value="Login">
                            <?php
                            if (isset($_GET["loginError"])) {
                                echo "
                                    <script>
                                        $('.me-login-button').click();
                                    </script>
                                    <span class='lead ml-3' style='color: red'>Benutername oder Passwort ist falsch</span>
                                ";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
<div class="container-fluid mt-2">
    <?php
    $connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
        $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

    if ($connection->connect_error) {

        if (isset($_GET["errorDB"])) {
            echo "
            <div class=\"jumbotron w-50 mx-md-auto p-5 alert-warning text-danger align-center\">
                <img src=\"images/warning.svg\" width=\"28\" height=\"28\" class=\"align-middle\">
                <span class=\"ml-3 align-middle\">
                   Die Anfrage konnte wegen eines unerwarteten Fehlers nicht durchgeführt werden.
                </span>
            </div>
        ";
        }

        echo "
            <div class=\"jumbotron w-50 mx-md-auto p-5 alert-warning text-danger align-center\">
                <img src=\"images/warning.svg\" width=\"28\" height=\"28\" class=\"align-middle\">
                <span class=\"ml-3 align-middle\">
                    Verbindung zur Datenbank fehlgeschlagen.
                </span>
            </div>
        ";


    } else {
        $connection->close();
    }
    ?>
    <div class="jumbotron w-50 mx-md-auto mt-5" id="registerJumbotron">
        <h1 class="display-4" id="registerText">Registrierung</h1>
        <hr class="my-4">
        <form method="post" action="php/register-database.php">
            <div class="form-row">
                <div class="form-group col-md-5 text-truncate">
                    <label for="reg-username">Benutzername</label>
                    <input name="reg-username" type="text" class="form-control" id="reg-username" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="reg-password1">Passwort</label>
                    <input name="reg-password1" type="password" class="form-control" id="reg-password1" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="reg-password2">Passwort wiederholen</label>
                    <input name="reg-password2" type="password" class="form-control" id="reg-password2" required>
                </div>
            </div>
            <input name="submitReg" type="submit" class="btn btn-primary mt-4" value="Registrieren">

            <?php
            if (isset($_GET['success'])) {
                if ($_GET['success'] == "true") {
                    echo "<span class='lead ml-4 align-bottom' style='color: green;'>
                           Benutzer wurde erfolgreich erstellt
                          </span>
                          <script>
                          $('.me-login-button').click();
                          </script>";
                } else if (isset($_GET['pwdShort'])) {
                    echo "<span class='lead ml-4 align-bottom' style='color: red;'>
                           Das Passwort muss mindestens 10 Zeichen lang sein
                          </span>";
                } else if (isset($_GET['pwdError'])) {
                    echo "<span class='lead ml-4 align-bottom' style='color: red;'>
                           Passwörter stimmen nicht überein
                          </span>";
                } else if (isset($_GET['nameError'])) {
                    echo "<span class='lead ml-4 align-bottom' style='color: red;'>
                           Benutzername ist bereits vergeben
                          </span>";
                }
            }
            ?>
        </form>
    </div>
    <!--    <div class="jumbotron mx-auto w-25 mr-5 p-4" id="demoJumbotron">-->
    <!--        <h4>Demo Notiz:</h4>-->
    <!--        <span class="card-text">MYSQL muss bei XAMPP aktiviert sein</span><br>-->
    <!--        <p class="card-text">Bei PHPMyAdmin muss der root user globale Rechte besitzen</p>-->
    <!--        <p class="card-text">Die Datenbank wird beim Laden der index-Seite automatisch erstellt </p>-->
    <!---->
    <!--    </div>-->
</div>
</body>
</html>