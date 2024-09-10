<?php
    session_start();

    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        http_response_code(300);
        echo "Only accept POST request";
        exit;
    }

    if (empty($_FILES["file"])){
        http_response_code(300);
        echo json_encode(["ERROR" => "File is required"]);
        exit;
    }

    if ($_FILES["file"]["size"] > 500000) {
        http_response_code(300);
        echo json_encode(["ERROR" => "File is too large"]);
        exit;
    }

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
        $target_file = "uploads/" . $file;

        if (!file_exists($target_file)){
            break;
        }
    }

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        http_response_code(300);
        echo json_encode(["ERROR" => "Only JPG, JPEG, PNG & GIF files are allowed"]);
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

    if (strcmp($obj[0]["image"], "default.png") !== 0){
        unlink("uploads/" . $obj[0]["image"]);
    }

    $rs = $conn->prepare("UPDATE users SET image = :image WHERE id = :id");
    $rs->bindParam(":image", $file);
    $rs->bindParam(":id", $_SESSION["id"]);
    $obj = $rs->execute();
    
    if (!$obj){
        http_response_code(300);
        echo json_encode(["ERROR" => "Upade user profile image error"]);
        exit;
    }

    $_SESSION["image"] = $file;

    http_response_code(200);
    echo json_encode(["SUCCESS" => "Image uploaded"]);
?>