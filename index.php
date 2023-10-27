<?php
/// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Landing Page</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	</head>
	<body>
		<style>
			body {
				background-image: url("arcade-unsplash.jpg");
				background-size: cover;
  				background-repeat: no-repeat;
			}

			h1 {
				text-align: center;
				padding-top: 15%;
				color: white;

			}

			.button {
				text-align: center;
				padding-top: 20px;
			}

			.button a {
				color: black;
				font-weight: bold;
			}
        </style>

		<script>
			function registerButton() {
				// Add Registration button under welcome message
				var newButton = document.createElement("p");
				newButton.innerHTML = "You're not using a registered session? <a href='registration.php'>Register now</a>";
				let button = document.querySelector("#button");
				button.append(newButton);
			}

			function playButton() {
				// Add Play button under welcome message
				var newButton = document.createElement("a");
				newButton.innerHTML = "Click here to play";
				newButton.href = "pairs.php";
				let button = document.querySelector("#button");
				button.append(newButton);
			}
		</script>

		<?php
		include "navigation_bar.php";
		?>
				
		<div class="main">
			<h1 style="color: white;">Welcome to Pairs</h1>
			<div class="button">
				<button type="button" id="button" class="btn btn-light">
					<a href="#" id="hyperlink"></a>
				</button>
			</div>
		</div>

		<?php
		// If there is not a registered session, create registration button, otherwise create play button
		if(!isset($_COOKIE["username"])) {
			echo "<script>registerButton();</script>";
		} else {
			echo "<script>playButton();</script>";
		}
		?>

    </body>
</html>