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

    $conn = new PDO('mysql:host=localhost;dbname=tcc', "root", "");

    $rs = $conn->prepare("SELECT * FROM users where username = :username");
    $rs->bindParam(":username", $_POST["username"]);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);

    if (count($obj) <= 0){
        http_response_code(300);
        echo json_encode(["ERROR" => "Username not exists"]);
        exit;
    }

    if (!password_verify($_POST["password"], $obj[0]["password"])){
        http_response_code(300);
        echo json_encode(["ERROR" => "Incorret password"]);
        exit;
    }

    $_SESSION["id"] = $obj[0]["id"];
    $_SESSION["username"] = $obj[0]["username"];
    $_SESSION["image"] = $obj[0]["image"];

    http_response_code(200);
    echo json_encode(["SUCCESS" => "User loggined"]);
?>