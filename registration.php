<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registration Page</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	</head>
	<body>
		<style>
            body {
				background-image: url("arcade-unsplash.jpg");
				background-size: cover;
  				background-repeat: no-repeat;
			}

            .main {
                background-color: white;
                margin-left: auto;
                margin-right: auto;
                width: 50%;
                padding: 1%;
                margin-top: 5%;
                box-shadow: 5px 10px #222529;
				border-radius: 10px;
            }

            form {
                text-align: center;
            }

            label {
                font-weight: bold;
            }

            table {
                margin-right: auto;
                margin-left: auto;
                background-color: #E8EEF5;
                border: 10px solid #E8EEF5;
            }

            [type=radio] { 
                position: absolute;
                opacity: 0;
                width: 0;
                height: 0;
            }

            [type=radio]:checked + img {
                outline: 3px solid #023f96;
            }

            [type=radio] + img {
                margin-right: 10px;
                width: 50px;
                height: 50px;
            }

        </style>

        <script>
            function errorMessage() {
                var errorMessage = document.createElement('p');
                errorMessage.innerText = 'Your username cannot contain any of the following characters: !@#%&*()+={}-;:<>?/]"';
                errorMessage.style.color = 'red';

                let usernameInput = document.getElementById('usernameInput');
                usernameInput.insertAdjacentElement('afterend', errorMessage);

            }
        </script>

        <?php

        function checkValid($username) {
            return !preg_match('/["!@#%&^*()\+=\[\]\-;:\'<>\?\/]/', $username); // check for any of the following characters: "!@#%&*()+=[]-;:'<>?/
        }

        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['skin']) && isset($_POST['eyes']) && isset($_POST['mouth'])) {
            $username = $_POST['username'];
            $valid = checkValid($username);
            if($valid == true) {

                // Set username and avatar
                setcookie('username', $username, time() + 60*60*24*30, '/');
                $avatar = array($_POST['skin'], $_POST['eyes'], $_POST['mouth']);
                setcookie('avatar', json_encode($avatar), time() + 60*60*24*30, '/');

                // Create the list of game attempts
			    $_SESSION["attempts"] = array();

                header('Location: index.php');
                exit();
            } 
        }

		include "navigation_bar.php";

        ?>

        <div class="main">
            <form action='registration.php' method='POST'>
                <div class="mb-3">
                    <label for="usernameInput" class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control" id="usernameInput">
                </div>
                <label for="avatarInput" class="form-label">Avatar:</label>

                <table>
                    <tr id='skin'>
                        <td>Skin:</td>
                    </tr>
                    <tr id='eyes'>
                        <td>Eyes:</td>
                    </tr>
                    <tr id='mouth'>
                        <td>Mouth:</td>
                    </tr>
                </table>
                <button type="submit" class="btn btn-dark" value="submit">Submit</button>
            </form>
        </div>

        <script>
            function avatarImages() {
                skin = ['red.png', 'green.png', 'yellow.png'];
                eyes = ['closed.png','laughing.png', 'long.png', 'normal.png', 'rolling.png', 'winking.png'];
                mouth = ['open.png', 'sad.png', 'smiling.png', 'straight.png', 'surprise.png', 'teeth.png'];

                for (let i=0; i < skin.length; i++) {
                    addImage(skin[i], 'skin');
                }
                for (let i=0; i < eyes.length; i++) {
                    addImage(eyes[i], 'eyes');
                }
                for (let i=0; i < mouth.length; i++) {
                    addImage(mouth[i], 'mouth');
                }
            }

            function addImage(image, location) {
                let newImage = document.createElement("td");
                let imageLocation = "emoji_assets/" + location + "/" + image;
                newImage.innerHTML = '<label><input type="radio" name="' + location + '" ' + 'value="' + imageLocation + '"><img src="' + imageLocation + '"></label>';
                let row = document.getElementById(location);
                row.append(newImage);
            }

            avatarImages();
        </script>

        <?php
        if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['skin']) && isset($_POST['eyes']) && isset($_POST['mouth'])) {
            if($valid == false) {
                // Display error
                echo "<script>errorMessage();</script>";
            }
        }
		?>
        
    </body>
</html>