<?php
    session_start();

    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        http_response_code(300);
        echo "Only accept POST request";
        exit;
    }

    if (empty($_FILES["file"]) || empty($_POST["title"])){
        http_response_code(300);
        echo json_encode(["ERROR" => "File and title is required"]);
        exit;
    }

    /* if ($_FILES["file"]["size"] > 500000) {
        http_response_code(300);
        echo json_encode(["ERROR" => "File is too large"]);
        exit;
    } */

    if (empty($_SESSION["id"])){
        http_response_code(300);
        echo json_encode(["ERROR" => "Authentication is required"]);
        exit;
    }

    $file = "";
    $target_file = "";

    while (1){
        $id = rand(100000, 999999);

        $file = $id . "-" . basename($_FILES["file"]["name"]);
        $target_file = "uploads/videos/" . $file;

        if (!file_exists($target_file)){
            break;
        }
    }

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    if($imageFileType != "mp4") {
        http_response_code(300);
        echo json_encode(["ERROR" => "Only MP4 files are allowed"]);
        exit;
    }
    
    $conn = new PDO('mysql:host=localhost;dbname=tcc', "root", "");
    
    $rs = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $rs->bindParam(":id", $_SESSION["id"]);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($obj) <= 0){
        http_response_code(300);
        echo json_encode(["ERROR" => "User not exists"]);
        exit;
    }

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        http_response_code(300);
        echo json_encode(["ERROR" => "There was an error uploading your file"]);
        exit;
    }

    $id = rand(100000, 999999);

    $rs = $conn->prepare("INSERT INTO videos (id, title, url, owner) VALUES (:id, :title, :url, :owner)");
    $rs->bindParam(":id", $id);
    $rs->bindParam(":title", $_POST['title']);
    $rs->bindParam(":url", $file);
    $rs->bindParam(":owner", $_SESSION["id"]);
    $obj = $rs->execute();
    
    if (!$obj){
        http_response_code(300);
        echo json_encode(["ERROR" => "Upload video error"]);
        exit;
    }

    http_response_code(200);
    echo json_encode(["SUCCESS" => "Video uploaded"]);
?>