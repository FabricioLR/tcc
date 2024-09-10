<?php

    if($_SERVER["REQUEST_METHOD"] !== "GET"){
        http_response_code(300);
        echo "Only accept POST request";
        exit;
    }
    
    $conn = new PDO('mysql:host=localhost;dbname=tcc', "root", "");
    $rs = $conn->prepare("SELECT videos.id, videos.title, videos.url, users.username, users.image FROM videos INNER JOIN users ON videos.owner=users.id");
    $rs->execute();
    $obj = $rs->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(["SUCCESS" => $obj]);
?>