<?php
    session_start();
    
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        http_response_code(300);
        echo "Only accept POST request";
        exit;
    }

    if (empty($_POST["username"]) || empty($_POST["password"])){
        http_response_code(300);
        echo json_encode(["ERROR" => "Username and password is required"]);
        exit;
    }

    if (strlen($_POST["password"]) < 6){
        http_response_code(300);
        echo json_encode(["ERROR" => "Password must contain at least 6 characters"]);
        exit;
    }

    $conn = new PDO('mysql:host=localhost;dbname=tcc', "root", "");

    $rs = $conn->prepare("SELECT * FROM users where username = :username");
    $rs->bindParam(":username", $_POST['username']);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);

    if (count($obj) > 0){
        http_response_code(300);
        echo json_encode(["ERROR" => "Username already exists"]);
        exit;
    }

    $id = rand(100000, 999999);
    $image = "default.png";
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $rs = $conn->prepare("INSERT INTO users (id, username, password, image) VALUES (:id, :username, :password, :image)");
    $rs->bindParam(":id", $id);
    $rs->bindParam(":username", $_POST['username']);
    $rs->bindParam(":password", $password);
    $rs->bindParam(":image", $image);
    $obj = $rs->execute();

    if (!$obj){
        http_response_code(300);
        echo json_encode(["ERROR" => "Register error"]);
        exit;
    }

    $_SESSION["id"] = $id;
    $_SESSION["username"] = $_POST['username'];
    $_SESSION["image"] = $image;

    http_response_code(200);
    echo json_encode(["SUCCESS" => "User registered"]);
?>