<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Navigation Bar</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	</head>
	<body>
        <style>

            nav#navigation-bar {
				background-color: #023f96;
			}

			#navigation-bar .nav-link {
				font-family: Verdana, Geneva, Tahoma, sans-serif;
				font-size: 12px;
				font-weight: bold;
				padding: 10px;
				background-color: white;
				border-radius: 20px;
				border: 2px solid black;
				margin-right: 10px;
			}

			#navigation-bar .navbar-brand {
				padding-left: 1%;
			}

			#avatarImage {
				margin-right: 45px;
			}

			#avatarImage img {
				position: absolute;
				top: 8%;
				left: 1%;
				width: 50px;
				height: 50px;
			}

			#defaultImage {
				position: relative;
			}
        </style>

		<script>
			function registerLink() {
				// Add Register link at the top
				let registerLink = document.querySelector(".register-leaderboard-link");
				registerLink.innerHTML = "Register";
				registerLink.href = "registration.php";
				registerLink.name = "register";
			}

			function leaderboardLink() {
				// Add Leaderboard link at the top
				let leaderboardLink = document.querySelector(".register-leaderboard-link");
				leaderboardLink.innerHTML = "Leaderboard";
				leaderboardLink.href = "leaderboard.php";
				leaderboardLink.name = "leaderboard";
			}

			function setAvatar(avatar) {
				avatar = JSON.parse(avatar);
				// Set avatar to avatar chosen by user
				let avatarImage = document.getElementById("defaultImage");
				let parent = document.querySelector('.navbar-brand');
				
				let skinImage = document.createElement("img");
				skinImage.src = avatar[0];
				skinImage.classList.add("skin");
				let eyesImage = document.createElement("img");
				eyesImage.src = avatar[1];
				eyesImage.classList.add("eyes");
				let mouthImage = document.createElement("img");
				mouthImage.src = avatar[2];
				mouthImage.classList.add("mouth");

				avatarImage.remove();

				let newDiv = document.createElement("div");
				newDiv.id = 'avatarImage';
				newDiv.appendChild(skinImage);
				newDiv.appendChild(eyesImage);
				newDiv.appendChild(mouthImage);

				newDiv.onclick = function() {
					window.location.href = "index.php";
				};

				parent.appendChild(newDiv);
			}
		</script>

        <nav class="navbar" id="navigation-bar">
			<a class="navbar-brand" href="#">
				<img src="default_avatar.png" id="defaultImage" width="50" height="50" onclick="window.location='index.php';">
  			</a>
			<a class="nav-link" href="index.php" name="home">Home</a>
			<a class="nav-link ms-auto" href="pairs.php" name="memory">Play Pairs</a>
			<a class="nav-link register-leaderboard-link" href="#"></a>
		</nav>
		
		<?php

		// If there is not a registered session, create register link at the top, otherwise create leaderboard link
		if(!isset($_COOKIE["username"])) {
			echo "<script>registerLink();</script>";
		} else {
			echo "<script>leaderboardLink();</script>";
		}

		// If the user has chosen an avatar, change the avatar in the nav bar
		if(isset($_COOKIE["avatar"])) {
			$avatar = $_COOKIE["avatar"];
			echo "<script>setAvatar('$avatar');</script>";
		}
		?>

    </body>
</html>