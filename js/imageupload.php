
if ($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    echo "File is not an image";
    $uploadOk = 0;
}
}
if (file_exists($target_file)) {
    echo "Sorry, file already exists";
    $uploadOk = 0;
}
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorrym your file is too large";
    $uploadOk = 0;
}
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG, GIF files are allowed!";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded";

} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$target_file)) {
        echo "The file" . basename($_FILES["fileToUpload"]["name"]) . "has been uploaded.";
        echo "<br>No inserrtion into database.";
    }
    $conn->close();
}
}
?>