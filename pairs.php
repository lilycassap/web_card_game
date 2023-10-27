<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Play Pairs</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	</head>
	<body>
		<style>
			body {
				background-image: url("arcade-unsplash.jpg");
				background-size: cover;
  				background-repeat: no-repeat;
			}

			#game-container {
				background-color: grey;
                margin-left: auto;
                margin-right: auto;
                width: 75%;
                padding: 1%;
                margin-top: 1%;
				box-shadow: 5px 10px #222529;
				border-radius: 10px;
			}

			.container {
				display: flex;
				height: 320px;
				margin: auto;
				flex-wrap: wrap;
				perspective: 1000px;
			}

			.card {
				flex-basis: calc(20% - 20px);
				max-height: 150px;
				max-width: 100px;
				margin: 5px;
				position: relative;
				transform-style: preserve-3d;
				transition: transform .5s ease-out;
			}

			.card.flip {
				transform: rotateY(180deg);
			}

			.front-face,
			.back-face {
				width: 100%;
				height: 100%;
				padding: 20px;
				position: absolute;
				border-radius: 5px;
				background-color: #5d6570;
				backface-visibility: hidden;
				align-items: center;
				justify-content: center;
				border: 4px solid black;
				display: flex;
			}

			.front-face {
				transform: rotateY(180deg);
			}

			#start-button {
				text-align: center;
				padding-top: 17%;
			}

			#start-button p {
				font-weight: bold;
				margin: 0px 20px;
			}

			#submission {
				text-align: center;
				margin-top: 10%;
				font-weight: bold;
				background-color: #5d6570;
				margin-left: auto;
				margin-right: auto;
				width: 35%;
				border-radius: 40px;
				padding: 10px;
				box-shadow: 5px 10px;
			}

			#submission h1 {
				color: white;
			}

			#level, #attempts, #time, #score, #level-reached {
				display: inline-block;
			}

			#stats-box {
				margin-left: auto;
                margin-right: auto;
                width: 75%;
                margin-top: 5%;
			}

			#level-box, #attempts-box, #time-box, #give-up {
  				display: inline-block;
				color: white;
				background-color: #5d6570;
				border-radius: 10px;
				padding: 10px;
				margin: 10px;
				font-weight: bold;
				text-align: center;
				border: 2px solid black;
			}

			#give-up {
				height: 45px;
				float: right;
			}

			#button-container p {
				font-weight: bold;
				margin: 0px 20px;
			}

			#button-container button {
				margin: 15px;
			}	

			img {
				width: 50px;
				height: 50px;
				position: absolute;
			}

			#submit-container {
				display: inline-block;
			}

			.hide, #submit-container.hide {
				display: none; 
			}

        </style>

		<?php
		include "navigation_bar.php";
		?>

		<div class="main">
			<div id="start-button" class="button">
				<button type="button" class="btn btn-light" onclick="playAudio()">
					<p>Start game</p>
				</button>
			</div>
			<div id="stats-box" class="hide">
				<div id="level-box">Level: <div id="level">1</div></div>
				<div id="attempts-box">Attempts: <div id="attempts">0</div></div>
				<div id="time-box">Time: <div id="time">0s</div></div>
				<button type="button" id="give-up" class="btn btn-light">
					<p>Give up</p>
				</button>
			</div>
			<div id="game-container" class="hide">
				<div class="container"></div>
			</div>
			<div id="submission" class="hide">
				<h1 id="failure" class="hide"></h1>
				<h1>You reached level: <div id="level-reached"></div></h1>
				<h1>Your score was: <div id="score"></div></h1>
				<div id="button-container">
					<div id="submit-container">
						<button type="button" id="submit" class="btn btn-light">
							<p>Submit Score</p>
						</button>
					</div>
					<button type="button" id="play-again" class="btn btn-light">
						<p>Play Again</p>
					</button>
				</div>
			</div>
		</div>

		<script>
			function startGame() {
				startButton.classList.add("hide");
				playButton.classList.add("hide");
				gameContainer.classList.remove("hide");
				statsContainer.classList.remove("hide");
				submission.classList.add("hide");

				solvedCards = [];
				flippedCards = [];
				startTime = Date.now();
				attempts = 0;
				failureMessage = '';
				isFlipping=false;
				
				updateAttempts(0);
				timeInterval = setInterval(updateTime, 1000);
				clearCards();
				createCards(numOfCards);
				var cards = document.querySelectorAll(".card");
				cards.forEach(card => card.addEventListener('click', flipCard));
				shuffle();
			}

			function createCards(num) {
				let skin = ['red.png', 'green.png', 'yellow.png'];
                let eyes = ['closed.png','laughing.png', 'long.png', 'normal.png', 'rolling.png', 'winking.png'];
                let mouth = ['open.png', 'sad.png', 'smiling.png', 'straight.png', 'surprise.png', 'teeth.png'];
				
				// Ensure the same combination is not used multiple times
				let usedCombos = [];
				for(let i=1; i <= num/cardsInMatch; i++) {
					let randomSkin, randomEyes, randomMouth;
					do {
						randomSkin = skin[Math.floor(Math.random() * skin.length)];
						randomEyes = eyes[Math.floor(Math.random() * eyes.length)];
						randomMouth = mouth[Math.floor(Math.random() * mouth.length)];
					} while (usedCombos.includes(randomSkin + randomEyes + randomMouth));
					usedCombos.push(randomSkin + randomEyes + randomMouth);

					let imgHTML = '<img src="emoji_assets/skin/' + randomSkin + '"><img src="emoji_assets/eyes/' + randomEyes + '"><img src="emoji_assets/mouth/' + randomMouth + '">';

					let container = document.querySelector('.container');
					for(let j=0; j < cardsInMatch; j++) {
						let newCard = document.createElement('div');
						newCard.className = "card pair" + i;
						newCard.innerHTML = '<div class="front-face">' + imgHTML + '</div><div class="back-face"></div>';
						container.append(newCard);
					}
				}
			}

			function clearCards() {
				let container = document.querySelector('.container');
				let cards = document.querySelectorAll(".card");
				for (card of cards) {
					card.remove();
				}
			}

			function shuffle() {
				let cards = document.querySelectorAll(".card");
				for (card of cards) {
					let randomOrder = Math.floor(Math.random() * cards.length);
					card.style.order = randomOrder;
				}
			}

			function flipCard() {
				if (isFlipping) {
					return;
				}
				if(!solvedCards.includes(this)) {
					this.classList.add('flip');

					flippedCards.push(this);
					if(flippedCards.length === cardsInMatch) {
						attempts += 1;
						updateAttempts(attempts);
						let classes = flippedCards.map(card => card.className);
						if(classes.every(className => className === classes[0])) {
							solvedCards.push(...flippedCards);
						} else {
							flippedCards.forEach(card => setTimeout(()=>card.classList.remove('flip'), 1000));
							isFlipping = true;
							setTimeout(()=>isFlipping=false, 1000)
						}
						flippedCards = [];
					}
				}

				if(solvedCards.length === numOfCards) {
					clearInterval(timeInterval);
					score = calculateScore();
					scorePerLevel.push(score);

					// Next level
					setTimeout(()=>nextLevel(), 1500);
				} 
			}

			function nextLevel() {
				if(level === 9) {
					setTimeout(()=>submissionScreen(), 1500);
				} else {
					
					// level 1: 6 cards, 2 per match
					// level 2: 8 cards, 2 per match
					// level 3: 10 cards, 2 per match
					// level 4: 9 cards, 3 per match
					// level 5: 12 cards, 3 per match
					// level 6: 15 cards, 3 per match
					// level 7: 12 cards, 4 per match
					// level 8: 16 cards, 4 per match
					// level 9: 20 cards, 4 per match

					if(level === 3) {
						cardsInMatch += 1;
						numOfCards = 9;
					} else if(level === 6) {
						cardsInMatch += 1;
						numOfCards = 12;
					} else {
						numOfCards += cardsInMatch;
					}
					level += 1;
					let levelLine = document.getElementById('level');
					levelLine.innerText = level;
					startGame();
				}
			}

			function updateAttempts(newAttempts) {
				let attemptsLine = document.getElementById('attempts');
				attemptsLine.innerText = newAttempts;
				if(newAttempts >= maxAttempts) {
					clearInterval(timeInterval);
					failureMessage = "You ran out of attempts.";
					setTimeout(()=>submissionScreen(), 1500);
				}
			}

			function updateTime() {
				timeTaken = Math.floor((Date.now() - startTime) / 1000); // Converts to seconds
				let timeLine = document.getElementById('time');
				timeLine.innerText = timeTaken + 's';
				if(timeTaken >= maxTime) {
					clearInterval(timeInterval);
					failureMessage = "You ran out of time.";
					setTimeout(()=>submissionScreen(), 1000);
				}
			}

			function submissionScreen() {
				gameContainer.classList.add("hide");
				statsContainer.classList.add("hide");
				submission.classList.remove("hide");

				// If user not in registered session remove submit button
				let submitContainer = document.getElementById('submit-container');
				if(!registered) {
					submitContainer.classList.add("hide");
				} else {
					submitContainer.classList.remove("hide");
				}

				let scoreLine = document.getElementById('score');
				let levelLine = document.getElementById('level-reached');

				if(failureMessage) {
					let failureLine = document.getElementById('failure');
					failureLine.classList.remove("hide");
					failureLine.innerText = failureMessage;
					scorePerLevel.push(10 * solvedCards.length/cardsInMatch); // Add 10 additional points for each pair matched even if user fails to complete level
				}

				let totalScore = 0;
				for(let i=0; i < scorePerLevel.length; i++) {
					totalScore += scorePerLevel[i];
				}
				scoreLine.innerText = totalScore;
				levelLine.innerText = level;
			}

			function updateSession(scores) {
				let totalScore = 0;
				for(let i=0; i < scores.length; i++) {
					totalScore += scores[i];
				}
				// Create an AJAX request to update the session variable
				var xhttp = new XMLHttpRequest();
				xhttp.open('POST', 'pairs.php');
				xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhttp.send('score=' + JSON.stringify(scores));
			}

			function calculateScore() {
				let finalScore = Math.floor((1 - (0.7 * timeTaken) / maxTime) * 100 + (1 - (0.3 * attempts) / maxAttempts) * 100); ///// myScore???
				return finalScore;
			}

			function resetGame() {
				numOfCards = 6;
				cardsInMatch = 2;
				scorePerLevel = [];
				level = 1;
			}

			function playAudio() {
				let audio = new Audio('tetris_music.mp3');
				audio.loop = true;
				audio.play();
			}

			let solvedCards, flippedCards, startTime, timeTaken, timeInterval, score, attempts, failureMessage, numOfCards, cardsInMatch, scorePerLevel, level, registered, isFlipping;
			let maxTime = 60;
			let maxAttempts = 20;
			resetGame();
			
			let gameContainer = document.getElementById('game-container');
			let statsContainer = document.getElementById('stats-box');
			let submission = document.getElementById('submission');
			let startButton = document.getElementById('start-button');
			let submitButton = document.getElementById('submit');
			let playButton = document.getElementById('play-again');
			let surrenderButton = document.getElementById('give-up');

			startButton.addEventListener("click", startGame);
			submitButton.addEventListener("click", 
				function() { 
					updateSession(scorePerLevel); 
					window.location.href = 'leaderboard.php';
				});
			playButton.addEventListener("click", 
				function() {
					resetGame();
					startGame();
				});
			surrenderButton.addEventListener("click",
				function() {
					clearInterval(timeInterval);
					failureMessage = "You gave up.";
					setTimeout(()=>submissionScreen(), 1000);
				});

		</script>

		<?php
			if(isset($_POST['score'])) {
				if(isset($_COOKIE["username"])) {
					$scores = json_decode($_POST['score']);
					$data = array($_COOKIE["username"]);
					$data[] = array_sum($scores);
					$levels = count($scores);
				
					$fp = fopen('data.csv', 'a');
					fputcsv($fp, array_merge($data, $scores));
					fclose($fp);
				}
			}
		
			// Check if they are playing using a registered session
			if(isset($_COOKIE["username"])) {
				echo "<script>registered = true;</script>";
			} else {
				echo "<script>registered = false;</script>";
			}
		?>

    </body>
</html>