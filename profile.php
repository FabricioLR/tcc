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
    <title>Profile</title>
    <style>
        *{
            margin: 0px;
            padding: 0px;
            overflow-x: hidden;
        }
        body{
            display: flex;
        }
        #user{
            width: 40%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-right: 1px solid #bbb9b9;
            background-color: rgb(230, 230, 230);
        }
        #user-img{
            aspect-ratio: 1 / 1;
            width: 50%;
            background-color: rgb(207, 207, 207);
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 100%;
            cursor: pointer;
        }
        #user-img img{
            width: 100%;
            height: 100%;
            border-radius: 100%;
        }
        #user p{
            margin-top: 15px;
            font-size: 20px;
        }

        #user-functions{
            width: calc(60% - 1px);
            height: 100vh;
        }
        #profile-image{
            width: 80%;
            margin: 20px 0px 0px 20px;
        }
        #profile-image fieldset{
            display: flex;
            flex-direction: column;
        }

        #profile-image fieldset input{
            margin: 10px 0px 0px 10px;
            cursor: pointer;
        }

        #profile-image fieldset input:nth-child(3){
            width: 80px;
            margin-bottom: 10px;
        }
        #logout{
            width: 135px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgb(207, 207, 207);
            cursor: pointer;
            margin: 20px 0px 0px 20px;
            color: black;
            text-decoration: none;
        }

        #profile-password{
            width: 80%;
            margin: 20px 0px 0px 20px;
        }
        #profile-password fieldset{
            display: flex;
            flex-direction: column;
        }

        #profile-password fieldset input{
            margin: 10px 0px 0px 10px;
            outline: none;
        }

        #profile-password fieldset input:nth-child(4){
            width: 80px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        #return{
            position: absolute;
            margin: 20px 0px 0px 20px;
            font-size: 20px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }
        #return a{
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
    <div id="return">
        <a href="index.php"><--</a>
    </div>
    <div id="user">
        <div id="user-img">
        <img src="<?php if(empty($_SESSION['image'])){echo 'uploads/default.png';} else {echo 'uploads/'.$_SESSION['image'];} ?>" alt="">
        </div>
        <p><?php echo $_SESSION["username"];?></p>
    </div>
    <div id="user-functions">
        <span id="error"></span>
        <span id="success"></span>
        <form action="upload-image.php" method="POST" enctype="multipart/form-data" id="profile-image">
            <fieldset>
                <legend>Change user profile image</legend>
                <input type="file" name="file" id="file" required>
                <input type="submit" value="Change">
            </fieldset>
        </form>
        <form action="change-password.php" method="POST" id="profile-password">
            <fieldset>
                <legend>Change user password</legend>
                <input type="text" name="old-password" placeholder="Old password" id="oldpassword" required>
                <input type="text" name="new-password" placeholder="New password" id="newpassword" required>
                <input type="submit" value="Change">
            </fieldset>
        </form>

        <form action="upload-video.php" method="POST" enctype="multipart/form-data" id="video-upload">
            <fieldset>
                <legend>Upload video</legend>
                <input type="file" name="file" id="video" required>
                <input type="text" name="title" placeholder="Title" id="title" required>
                <input type="submit" value="Upload">
            </fieldset>
        </form>
        <a href="logout.php" id="logout">Logout</a>
    </div>
    <script>
        document.getElementById("profile-image").addEventListener("submit", async (e) => {
            document.getElementById("error").innerText = ""
            document.getElementById("success").innerText = ""
            e.preventDefault()

            const file = document.getElementById("file").files[0]

            if (!file){
                return;
            }

            var formdata = new FormData()
            formdata.append('file', file)

            const response = await fetch("upload-image.php", {
                method: "POST",
                body: formdata
            })

            const data = await response.json()

            if (response.status === 200){
                location.reload()
            } else if (response.status === 300){
                document.getElementById("error").innerText = data.ERROR
            }
        })

        document.getElementById("profile-password").addEventListener("submit", async (e) => {
            document.getElementById("error").innerText = ""
            e.preventDefault()

            const oldpassword = document.getElementById("oldpassword").value
            const newpassword = document.getElementById("newpassword").value

            if (!oldpassword || !newpassword){
                return;
            }

            const response = await fetch("change-password.php", {
                method: "POST",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({ "old-password": oldpassword, "new-password": newpassword })
            })

            const data = await response.json()

            if (response.status === 200){
                document.getElementById("success").innerText = data.SUCCESS
            } else if (response.status === 300){
                document.getElementById("error").innerText = data.ERROR
            }
        })
    </script>
</body>
</html>