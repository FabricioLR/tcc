<?php
    session_start();

    if(empty($_SESSION["id"])){
        header("location:login.html");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TCC</title>
    <style>
        *{
            margin: 0px;
            padding: 0px;
            overflow-x: hidden;
        }
        header{
            width: 100%;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgb(230, 230, 230);
        }
        #logo{
            width: 150px;
            height: 45px;
            background-color: rgb(207, 207, 207);
            margin-left: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        #user{
            width: 45px;
            height: 45px;
            background-color: rgb(207, 207, 207);
            margin-right: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 100%;
            cursor: pointer;
        }
        #user img{
            width: 100%;
            height: 100%;
            border-radius: 100%;
        }
        #user-functions{
            width: 150px;
            height: 200px;
            background-color: rgb(230, 230, 230);
            position: absolute;
            z-index: 100;
            top: 60px;
            left: 100%;
            transition: all 700ms;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #user-functions a{
            width: 135px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgb(207, 207, 207);
            cursor: pointer;
            margin-top: 15px;
            color: black;
            text-decoration: none;
        }
        #user-functions.active{
            left: calc(100% - 150px);
        }
        main{
            display: flex;
            justify-content: center;
        }
        #videos{
            display: grid;
            width: 95%;
            margin-top: 20px;
            grid-gap: 10px;
            grid-template-columns: repeat(5, 250px);
        }

        .video{
            width: 250px;
            border: 1px solid black;
        }
        .video video{
            width: 100%;
            height: 120px;
            background-color: black;
        }
        .title{
            margin: 0px 5px 0px 5px;
        }
        .user{
            width: 100%;
            display: flex;
            align-items: center;
            margin: 5px;
        }
        .user img{
            width: 30px;
            height: 30px;
            border-radius: 100%;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <header>
        <div id="logo">
            LOGO
        </div>
        <div id="user">
        <img src="<?php if(empty($_SESSION['image'])){echo 'uploads/default.png';} else {echo 'uploads/'.$_SESSION['image'];} ?>" alt="">
        </div>
        <div id="user-functions">
            <?php
                if (empty($_SESSION["id"])){
                    echo "<a href='login.html'>Login</a><a href='register.html'>Register</a>";
                } else {
                    echo "<a href='profile.php'>Profile</a><a href='logout.php'>Logout</a>";
                }
            ?>
        </div>
    </header>
    <main>
        <div id="videos">

        </div>
    </main>
    <body>
        
    </body>
    <script>
        window.onload = async () => {
            const response = await fetch("videos.php", {
                method: "GET"
            })

            const data = await response.json()

            if (response.status === 200){
                data.SUCCESS.map(video => {
                    const div = document.createElement("div")
                    const video1 = document.createElement("video")
                    const p1 = document.createElement("p")
                    const div1 = document.createElement("div")
                    const img = document.createElement("img")
                    const p2 = document.createElement("p")

                    div.className = "video"
                    p1.className = "title"
                    div1.className = "user"

                    video1.src = "uploads/videos/" + video.url
                    p1.innerText = video.title
                    img.src = "uploads/" + video.image
                    p2.innerText = video.username

                    div.appendChild(video1)
                    div.appendChild(p1)
                    div1.appendChild(img)
                    div1.appendChild(p2)
                    div.appendChild(div1)

                    document.getElementById("videos").appendChild(div)
                })
            } else if (response.status === 300){

            }
        }

        document.getElementById("user").addEventListener("click", () => {
            document.getElementById("user-functions").classList.toggle("active")
        })
    </script>
</body>
</html>