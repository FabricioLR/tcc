<?php
    session_start();

    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        http_response_code(300);
        echo "Only accept POST request";
        exit;
    }

    if (empty($_POST["old-password"]) || empty($_POST["new-password"])){
        http_response_code(300);
        echo json_encode(["ERROR" => "Password's are required"]);
        exit;
    }

    if (strlen($_POST["new-password"]) < 6){
        http_response_code(300);
        echo json_encode(["ERROR" => "New password must contain at least 6 characters"]);
        exit;
    }

    $conn = new PDO('mysql:host=localhost;dbname=tcc', "root", "");

    $rs = $conn->prepare("SELECT * FROM users where id = :id");
    $rs->bindParam(":id", $_SESSION["id"]);
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);

    if (count($obj) <= 0){
        http_response_code(300);
        echo json_encode(["ERROR" => "User not exists"]);
        exit;
    }

    if (!password_verify($_POST["old-password"], $obj[0]["password"])){
        http_response_code(300);
        echo json_encode(["ERROR" => "Incorret old password"]);
        exit;
    }

    $password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);

    $rs = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
    $rs->bindParam(":password", $password);
    $rs->bindParam(":id", $_SESSION["id"]);
    $obj = $rs->execute();
    
    if (!$obj){
        http_response_code(300);
        echo json_encode(["ERROR" => "Upade user password error"]);
        exit;
    }

    http_response_code(200);
    echo json_encode(["SUCCESS" => "Password changed"]);
?>