$(document).ready(function () {

    $('#me-addNote-button').click(function () {

        var $addNote_img = $('#me-addNote-img');

        if ($addNote_img.attr('src') === 'images/plus.svg') {

            $addNote_img.attr("src", "images/cross.svg");

            var noteHTML =
                "<div class='col-12 mb-4' id='me-newNote'>" +
                "<div class='card'>" +
                "<div class='card-body pb-1'>" +
                "<form method=\"post\" action=\"php/addNote.php\">" +
                "<div class='form-group'>" +
                "<input name='noteTitel' type='text' class='form-control' id='noteTitel' placeholder='Titel' autocomplete=\"off\" required>" +
                "</div>" +
                "<div class='form-group note-text'>" +
                "<textarea name='noteContent' id='noteContent' class='form-control' autocomplete=\"off\" placeholder='Text'></textarea>" +
                "</div>" +
                "<div class='row'>" +
                "<div class='col-6'>" +
                "<p class='lead'>Neue Notiz</p>" +
                "</div> " +
                "<div class='col-6'>" +
                "<input name=\"submitReg\" type=\"submit\" class=\"btn btn-primary float-right\" value=\"Hinzufügen\">" +
                "</div>" +
                "</div>" +
                "</form>" +
                "</div>" +
                "</div>" +
                "</div>";
            $('#me-notes-row').prepend(noteHTML);
        } else {
            $("#me-newNote").remove();
            $addNote_img.attr("src", "images/plus.svg");
        }

    });

    $('#me-addFriend-button').click(function () {

        var $addFriend_img = $('#me-addFriend-img');

        if ($addFriend_img.attr('src') === 'images/plus.svg') {

            $addFriend_img.attr("src", "images/cross.svg");

            var addFriendHTML =
                "<div class='mb-4' id='me-newFriend'>" +
                "<div class='card pb-2'>" +
                "<div class='card-body pb-1'>" +
                "<form method=\"post\" action=\"php/addFriend.php\">" +
                "<h5 class='card-title'> " +
                "<div class='form-group'>" +
                "<input name='friendName' type='text' class='form-control' id='friendName' placeholder='Name' autocomplete=\"off\" required>" +
                "</div>" +
                "</h5>" +
                "<div class='row'> " +
                "<span id='me-addFriend-alert' class='col-12 mb-2 text-danger'></span>" +
                "<div class='col-12'>" +
                "<input name=\"submitNewFriend\" type=\"submit\" class=\"btn btn-primary float-right\" value=\"Freund hinzufügen\">" +
                "</div>" +
                "</div>" +
                "</form>" +
                "</div>" +
                "</div>" +
                "</div>";

            $('#me-friends').prepend(addFriendHTML);
        } else {
            $("#me-newFriend").remove();
            $addFriend_img.attr("src", "images/plus.svg");
        }
    });

    $('.me-checkbox').click(function () {

        var $amountOfCheckedBoxes = $('.me-checkbox:checked').length;

        if ($amountOfCheckedBoxes > 1) {

            $('.me-checkbox').not(this).prop('checked', false);

            var sendNoteExist = $('#me-sendNote').length;
            if (sendNoteExist) {
                addSendNote();
            }

        } else if ($amountOfCheckedBoxes === 1) {

            var friendlistOptionsHTML =
                "<div class='row' id='me-friendlist-options'>" +
                "<div class='col-12 mt-2' id=''>" +
                "<p id='me-friendlist-options-info'></p>" +
                "</div>" +
                "<div class='col-12'>" +
                "<input name=\"removeFriend\" type=\"button\" onclick='submitRemoveFriend()' " +
                "class=\"me-checkbox btn btn-danger float-right ml-2\" id='me-removeFriend' value=\"Freund entfernen\">" +
                "<input name=\"sendNote\" type=\"button\" " +
                "class=\"me-checkbox btn btn-primary float-right\" id='me-friendlist-sendNote' value=\"Notiz senden\">" +
                "</div>" +
                "</div>";

            $('#me-form-friendlist').append(friendlistOptionsHTML);

        } else {

            $('#me-friendlist-options').remove();
            $('#me-sendNote').remove();

            if (!$('#me-newNote').length) {
                $('#me-addNote-button').click();
            }

        }
        setResponsive();
    });

    $('#me-friends').on("click", '#me-friendlist-sendNote', function () {

        if ($('#me-newNote').length) {
            $('#me-addNote-button').click();
        }

        var sendNoteExist = $('#me-sendNote').length;

        if (!sendNoteExist) {
           addSendNote();
        }
    });

    function addSendNote() {

        if ($('#me-newNote').length) {
            $('#me-addNote-button').click();
        }

        $('#me-sendNote').remove();

        var sendNoteHTML =
            "<div class=\"col-12 mb-4\" id='me-sendNote'>" +
            "<div class=\"card\">" +
            "<div class=\"card-body\">" +
            "<form method=\"post\" action=\"php/sendNote.php\">" +
            "<div class=\"form-group\">" +
            "<input name='sendNoteTitle' type=\"text\" class=\"form-control\" " +
            "id='sendNoteTitle' placeholder=\"Titel\" autocomplete=\"off\" required>" +
            "</div>" +
            "<div class='form-group note-text'>" +
            "<textarea name='sendNoteContent' id='sendNoteContent' class='form-control'" +
            " placeholder='Text' autocomplete=\"off\"></textarea>" +
            "</div>" +
            "<div class=\"row\">" +
            "<div class=\"col-6\">" +
            "<div class='float-left pr-2'>" +
            "<img src=\"images/account.svg\" width=\"25\" height=\"25\">" +
            "</div>";

        var $checkedBox = $('.me-checkbox:checked');
        var id = $checkedBox.attr("value");

        sendNoteHTML += "<input type='hidden' value='" + id + "' id='sendNotefriendId' name='sendNotefriendId'>";

        sendNoteHTML += "<p><span class='lead'>Notiz für: </span>";
        var label = $checkedBox.prop("labels");
        sendNoteHTML += $(label).text().trim() + "</p>";

        sendNoteHTML +=
            "</div>" +
            "<div class=\"col-6\">" +
            "<input name=\"submitSendNote\" type=\"submit\" class=\"btn btn-primary float-right\" " +
            "value=\"Senden\">" +
            "</div>" +
            "</div>" +
            "<img src=\"images/info.svg\" width=\"23\" height=\"23\" class=\"align-middle\" id='me-sendNote-info'>" +
            "<span class=\"align-middle ml-2\" style='font-size: 0.85em; color: grey'>" +
            " Eine Notiz kann nur gesendet werden, wenn die Benutzer sich jeweils als Freund haben." +
            "</span><br>" +
            "</form>" +
            "</div>" +
            "</div>" +
            "</div>";

        $('#me-notes-row').prepend(sendNoteHTML);
    }

    setResponsive();
    $(window).resize(setResponsive);

    function setResponsive() {

        var $windowWidth = $(window).width();

        if ($windowWidth < 1000) {
            $('#registerJumbotron').addClass("w-100");
            $('#registerText').css('font-size', '8vw');

            $('#demoJumbotron').addClass("w-75");
        } else if ($windowWidth >= 1000) {
            $('#registerJumbotron').removeClass("w-100");
            $('#registerText').css('font-size', '');

            $('#demoJumbotron').addClass("w-25");
        }

        if ($windowWidth < 1427) {
            $('#me-friendlist-sendNote').addClass("mt-2");
        } else if ($windowWidth >= 1427) {
            $('#me-friendlist-sendNote').removeClass("mt-2");
        }
    }

});




