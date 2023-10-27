<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Leaderboard</title>
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
                background-color: grey;
                margin-left: auto;
                margin-right: auto;
                width: 75%;
                margin-top: 5%;
				box-shadow: 5px 10px;
                border-radius: 10px;
            }

            #scores-table {
                border-spacing: 2px;
                width: 100%;
                text-align: center;
            }

            #scores-table th {
                background-color: #023f96;
                color: white;
            }

            nav#level-navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-weight: bold;
            }
            #level-navbar .nav-link {
                padding: 5px 10px;
                border: 1px solid #ccc;
                text-decoration: none;
                color: white;
                width: 100%;
                text-align: center;
                background-color: #007bff;
            }
            #level-navbar .nav-link:hover {
                background-color: #eee;
            }
            #level-navbar .nav-link.active {
                background-color: #023f96;
                color: #fff;
            }
        </style>

        <?php
		include "navigation_bar.php";	
    
        // Open csv file to add to new data array
        $file = fopen('data.csv', 'r');
        $data = array();
        while (($row = fgetcsv($file)) !== FALSE) {
            // Add the row to the data array
            $data[] = $row;
        }
        fclose($file);

        // Sort the data array in descending order by given index score
        function sortByIndex($data, $index) {
            usort($data, function($a, $b) use ($index) {
                return $b[$index] - $a[$index];
            });
            return $data;
        }

        $scores = array();
        for ($i = 1; $i <= 10; $i+=1) {
            $test = sortByIndex($data, $i);
            $scores[] = $test;
          }
		?>

        <div class="main">
            <nav id="level-navbar">
                <a href="#" class="nav-link active" onclick="populateTable()">Overall</a>
            </nav>
            <table id="scores-table">
            </table>
        </div>

        <script>
            function addLevels() {
                let levelNavigation = document.getElementById("level-navbar");
                for (let i = 1; i <= 9; i++) {
                    let newLink = document.createElement("a");
                    newLink.href="#";
                    newLink.className = "nav-link";
                    newLink.innerText = i;
                    newLink.setAttribute("onclick", "populateTableLevel(" + i + ")");
                    newLink.addEventListener("click", activateNavLink);
                    levelNavigation.append(newLink);
                }
            }

            function populateTable() {
                // Populate table with best overall scores
                let table = document.getElementById("scores-table");
                table.innerHTML = "<tr><th>Rank</th><th>Username</th><th>Score</th></tr>";

                let overallScores = scores[0];
                let i = 1;
                for (let score of overallScores) {
                    if(i <= maxEntries && score[1]) { // Maximum 10 entries

                        let newRank = document.createElement("td");
                        newRank.innerHTML = "<b>" + i + "</b>";

                        let newScore = document.createElement("td");
                        newScore.innerHTML = score[1];

                        let newUsername = document.createElement("td");
                        newUsername.innerHTML = score[0];

                        // Add each column to new row and add row to table
                        let newRow=document.createElement("tr");
                        newRow.append(newRank);
                        newRow.append(newUsername);
                        newRow.append(newScore);
                        table.append(newRow);

                        i += 1;
                    }
                }
            }

            function populateTableLevel(number) {
                let table = document.getElementById("scores-table");
                table.innerHTML = "<tr><th>Rank</th><th>Username</th><th>Score</th></tr>";

                let levelScores = scores[number];
                let i = 1;
                for (let score of levelScores) {
                    if(i <= maxEntries && score[number + 1]) {

                        let newRank = document.createElement("td");
                        newRank.innerHTML = "<b>" + i + "</b>";

                        let newScore=document.createElement("td");
                        newScore.innerHTML = score[number + 1];

                        let newUsername=document.createElement("td");
                        newUsername.innerHTML = score[0]; ////     

                        //Add each column to new row and add row to table
                        let newRow=document.createElement("tr");
                        newRow.append(newRank);
                        newRow.append(newUsername);
                        newRow.append(newScore);
                        table.append(newRow);

                        i += 1;
                    }
                }
            }

            function activateNavLink() {
                let navLinks = document.querySelectorAll('.nav-link');
                // Remove active class from all links
                navLinks.forEach(link => link.classList.remove('active'));
                // Add active class to clicked link
                this.classList.add('active');
            }

            let scores = <?php echo json_encode($scores); ?>;
            let maxEntries = 10;
            
            let overallButton = document.querySelector('#level-navbar a');
            overallButton.addEventListener("click", activateNavLink);

            addLevels();
            populateTable();
        </script>

        </body>
</html>