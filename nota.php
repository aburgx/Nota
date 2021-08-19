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
    <script src="js/jquery.ns-autogrow.js"></script> <!-- AutoGrow Jquery TextArea -->
    <script src="js/clamp.min.js"></script>

    <?php
    SESSION_START();

    if (isset($_SESSION["login"])) {
        if ($_SESSION["login"] == 0) {
            header('Location: index.php');
            exit;
        }
    } else {
        header('Location: index.php');
        exit;
    }

    if (isset($_GET["logout"])) {
        $_SESSION["login"] = 0;
        header('Location: index.php');
        exit;
    }

    if (isset($_GET["errorDB"])) {
        $_SESSION["login"] = 0;
        header('Location: index.php?errorDB');
        exit;
    }

    if (isset($_GET["errorUserAlreadyInFriendList"])) {
        echo "<script>
                $(document).ready(function () {
                      $('#me-addFriend-button').click();
                      $('#me-addFriend-alert').text('Der Benutzer ist bereits in der Freundesliste');
                });
            </script>";
    } else if (isset($_GET["errorAddedSelfasFriend"])) {
        echo "<script>
                $(document).ready(function () {
                      $('#me-addFriend-button').click();
                      $('#me-addFriend-alert').text('Sie können sich nicht selbst als Freund hinzufügen');
                });
            </script>";
    } else if (isset($_GET["errorUserNotFound"])) {
        echo "<script>
                $(document).ready(function () {
                      $('#me-addFriend-button').click();
                      $('#me-addFriend-alert').text('Der Benutzer wurde nicht gefunden');
                });
            </script>";
    }

    ?>
    <script>
        function reloadAutoGrow() {
            $(function () {
                $('.note-text textarea').autogrow({vertical: true, horizontal: false});
            });
        }

        function submitRemoveFriend() {
            console.log("submitRemoveFriend");
            var $friendlist = $('#me-form-friendlist');
            $friendlist.attr("action", "php/removeFriend.php");
            $friendlist.submit();
        }
        function closeSendNoteError() {
            $(document).ready(function () {
                document.getElementById("me-sendNote-error").remove();
            });
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary" id="me-navbar">
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
            <li class="nav-item mr-2">
                <a class="nav-link" style="color: white;">
                    Eingeloggt als
                    <?php
                    echo $_SESSION["user"]["username"];
                    ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-primary btn-lg me-login-button px-4"
                   href="nota.php?logout=true"
                   role="button">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-4 mt-4">
            <h3>To-do</h3>
            <div class="card mb-4">
                <ul class="list-group">
                    <?php
                    $connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
                        $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

                    if ($connection->connect_error) {
                        header('Location: nota.php?connectError=true');
                        exit;
                    }

                    $userId = $_SESSION["user"]["userId"];

                    $selectNote = "SELECT taskId,taskContent
                                   FROM task 
                                   JOIN user ON task.userId = user.userId 
                                   WHERE task.userId = '$userId';";

                    if ($result = $connection->query($selectNote)) {

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_row()) {
                                echo "<li class='list-group-item me-todo-item'>$row[1]
                                       <div class='float-right'>
                                            <a href='php/removeTask.php?taskId=$row[0]'>
                                                <img src='images/check-white.svg' width='30' height='30'>
                                            </a>
                                       </div>
                                     </li>";
                            }
                        }
                        $result->close();
                    }
                    $connection->close();
                    ?>
                    <li class="list-group-item me-todo-item">
                        <form method="post" action="php/addTask.php">
                            <div class="form-row mt-4">
                                <div class="form-group col-md-10">
                                    <input name="taskContent" type="text" class="form-control"
                                           placeholder="Neue Aufgabe" id="taskContent" autocomplete="off" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <input name="submitAddTask" type="submit" class="btn btn-primary float-right"
                                           value=" " id="me-submitTask-button">
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-12 col-lg-5 mt-4">
            <h3 class="mb-3">Notizen
                <a class="btn btn-dark float-right" id="me-addNote-button">
                    <img src='images/plus.svg' id="me-addNote-img" width='22' height='22'>
                </a>
            </h3>
            <div class="row" id="me-notes-row">
                <?php
                if (isset($_GET["errorSendingNote"])) {
                    echo
                    "
                    <div class=\"card alert-warning text-danger col-10 mx-auto mb-4\" id=\"me-sendNote-error\">
                    <div class=\"card-body\">
                        <div class='float-left'>
                            <img src=\"images/warning.svg\" width=\"33\" height=\"33\" class=\"align-middle\">
                        </div>
                         <div class='float-right' onclick='closeSendNoteError();'>
                            <img src=\"images/cross-black.svg\" width=\"24\" height=\"24\" class=\"align-middle\" style='cursor: pointer'>
                        </div>
                      <div class='ml-5'>
                        <span class=\"ml-3 align-middle card-title\">
                            Notiz konnte nicht gesendet werden.
                        </span>
                        <p class=\"ml-3 align-middle card-text\">
                            Der Empfänger hat Sie nicht in seiner Freundesliste.
                        </p>
                      </div>  
                    </div>
                    </div>
                   
                    ";



                   /* <div class=\"card mb-4\">
                                  <div class=\"card-body\">
                                  <div class='float-right'>
                                      <a href='php/removeNote.php?noteId=$id'>
                                          <img src='images/cross-red.svg' width='22' height='22'>
                                      </a>
                                  </div>
                                  <h5 class=\"card-title\">$title</h5>
                                  <p class=\"card-text h-25\">$content</p>";*/
                }

                $connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
                    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

                if ($connection->connect_error) {
                    header('Location: nota.php?connectError=true');
                    exit;
                }

                $userId = $_SESSION["user"]["userId"];

                $selectNote = "SELECT noteTitel,noteContent,noteId,friendId
                               FROM note 
                               WHERE note.userId = '$userId';";

                if ($result = $connection->query($selectNote)) {
                    if ($result->num_rows > 0) {

                        $oldswitcherino = 0;
                        $posLeft = 0;
                        $posRight = 0;
                        $left = array(array());
                        $right = array(array());

                        while ($row = $result->fetch_row()) {

                            if ($oldswitcherino % 2 == 0) {
                                $left[$posLeft]['title'] = $row[0];
                                $left[$posLeft]['content'] = $row[1];
                                $left[$posLeft]['id'] = $row[2];
                                $left[$posLeft]['friendId'] = $row[3];
                                $posLeft++;
                            } else {
                                $right[$posRight]['title'] = $row[0];
                                $right[$posRight]['content'] = $row[1];
                                $right[$posRight]['id'] = $row[2];
                                $right[$posRight]['friendId'] = $row[3];
                                $posRight++;
                            }

                            $oldswitcherino++;
                        }
                        echo "<div class=\"col-6\">";
                        for ($i = 0; $i < count($left); $i++) {

                            $title = $left[$i]['title'];
                            $content = $left[$i]['content'];
                            $id = $left[$i]['id'];
                            $friendId = $left[$i]['friendId'];

                            echo "<div class=\"card mb-4\">
                                  <div class=\"card-body\">
                                  <div class='float-right'>
                                      <a href='php/removeNote.php?noteId=$id'>
                                          <img src='images/cross-red.svg' width='22' height='22'>
                                      </a>
                                  </div>
                                  <h5 class=\"card-title\">$title</h5>
                                  <p class=\"card-text h-25\">$content</p>";

                            if ($friendId != null) {
                                $select = "SELECT username
                                           FROM user
                                              JOIN friend
                                                ON user.userId = friend.userId
                                           WHERE friendId = '$friendId';";

                                if ($result = $connection->query($select)) {
                                    $username = $result->fetch_assoc()["username"];
                                    echo "<span>&#8226; Notiz erhalten von " . $username . "</span>";

                                } else {
                                    header('Location: ../index.php?errorDB');
                                    $_SESSION["login"] = 0;
                                    exit;
                                }
                            }

                            echo "</div></div>";
                        }
                        echo "</div><div class=\"col-6\">";
                        if ($posRight != 0) {
                            for ($i = 0; $i < count($right); $i++) {

                                $title = $right[$i]['title'];
                                $content = $right[$i]['content'];
                                $id = $right[$i]['id'];
                                $friendId = $right[$i]['friendId'];

                                echo " <div class=\"card mb-4\">
                                            <div class=\"card-body\">
                                                <div class='float-right'>
                                                    <a href='php/removeNote.php?noteId=$id'>
                                                        <img src='images/cross-red.svg' width='22' height='22'>
                                                    </a>
                                                </div>
                                                <h5 class=\"card-title\">$title</h5>
                                                <p class=\"card-text h-25\">$content</p>";


                                if ($friendId != null) {
                                    $select = "SELECT username
                                           FROM user
                                              JOIN friend
                                                ON user.userId = friend.userId
                                           WHERE friendId = '$friendId';";

                                    if ($result = $connection->query($select)) {
                                        $username = $result->fetch_assoc()["username"];
                                        echo "<span>&#8226; Notiz erhalten von " . $username . "</span>";

                                    } else {
                                        header('Location: ../index.php?errorDB');
                                        $_SESSION["login"] = 0;
                                        exit;
                                    }
                                }

                                echo "</div></div>";
                            }
                        }
                        echo "</div>";
                    } else {
                        echo "<script>
                                $(document).ready(function () {
                                    $('#me-addNote-button').click();
                                });
                            </script>";
                    }
                    $result->close();
                }
                $connection->close();
                ?>
            </div>
        </div>
        <div class="col-12 col-lg-3" id="me-info-box">
            <h3 class="mt-4">Freundesliste
                <a class="btn btn-dark float-right" id="me-addFriend-button">
                    <img src='images/plus.svg' id="me-addFriend-img" width='22' height='22'>
                </a>
            </h3>
            <div class="pt-2" id="me-friends">
                <?php
                $connection = new mysqli($_SESSION["database"]["host"], $_SESSION["database"]["username"],
                    $_SESSION["database"]["password"], $_SESSION["database"]["dbname"]);

                if ($connection->connect_error) {
                    header('Location: nota.php?connectError=true');
                    exit;
                }

                $userId = $_SESSION["user"]["userId"];

                $selectFriendIds = "SELECT friend.userId
                                    FROM friend
                                    JOIN user_friend
                                    ON friend.friendId = user_friend.friendId
                                    WHERE user_friend.userId = '$userId';";

                if ($resultFriendIds = $connection->query($selectFriendIds)) {

                    if ($resultFriendIds->num_rows > 0) {

                        echo "<div class='card'>";
                        echo "<div class='card-body'>";
                        echo "<form method=\"post\" action=\"\" id='me-form-friendlist'>";

                        while ($row = $resultFriendIds->fetch_row()) {

                            $selectFriendName = "SELECT username
                                                 FROM user
                                                 WHERE userId = '$row[0]'";

                            if ($resultFriendName = $connection->query($selectFriendName)) {

                                $resultFriendName = $resultFriendName->fetch_row();

                                echo "<div class=\"form-check form-group\" id='$row[0]'>
                                          <input class=\"form-check-input me-checkbox\" name=\"checkbox\" 
                                          value=\"$row[0]\" type=\"checkbox\" id=\"friendCheck$row[0]\">
                                          <label class=\"form-check-label css-label pl-3\" for=\"friendCheck$row[0]\">
                                            $resultFriendName[0]
                                          </label>
                                          <div class='float-right'>
                                            <img src=\"images/account.svg\" width=\"25\" height=\"25\">
                                          </div>
                                      </div>";
                            }
                        }
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<script>
                                $(document).ready(function () {
                                    $('#me-addFriend-button').click();
                                });
                            </script>";
                    }
                    $resultFriendIds->close();
                }
                $connection->close();
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>