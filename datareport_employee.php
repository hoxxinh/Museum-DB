<!DOCTYPE html>
<html lang="en">
<header>
  	<h1>Natural Museum of Fine Arts - Employee Search</h1>
</header>
<head>
    <meta charset="UTF-8">
    <title>Employee Search</title>
	<link rel="stylesheet" href="Style/datareport_styles.css" />
</head>
<body>
	<br><br>
    <div class="datareportinput">
		<h2>Employee Search</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label for="searchFirstName">First Name:</label><input type="text" id="searchFirstName" name="searchFirstName"><br>
			<label for="searchLastName">Last Name:</label><input type="text" id="searchLastName" name="searchLastName"><br>
			<label for="searchPosition">Position:</label><input type="text" id="searchPosition" name="searchPosition"><br>
			<label for="searchUsername">Username:</label><input type="text" id="searchUsername" name="searchUsername"><br>
			<label for="searchWorkDay">Work on this day:</label><input type="text" id="searchWorkDay" name="searchWorkDay"><br>
			<label for="searchMinPay">Minimum pay rate:</label><input type="text" id="searchMinPay" name="searchMinPay"><br>
			<label for="searchMaxPay">Maximum pay rate:</label><input type="text" id="searchMaxPay" name="searchMaxPay"><br>
			<br>
			<button type="submit" name="search">Search</button>
			<br>
			<button type="submit" name="download" value="download">Download as CSV</button><br><br>
    	</form>
	</div>
	<br><br>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchFirstName = isset($_POST['searchFirstName']) ? $_POST['searchFirstName'] : "";
		$searchLastName = isset($_POST['searchLastName']) ? $_POST['searchLastName'] : "";
        $searchPosition = isset($_POST['searchPosition']) ? $_POST['searchPosition'] : "";
		$searchUsername = isset($_POST['searchUsername']) ? $_POST['searchUsername'] : "";
		$searchWorkDay = isset($_POST['searchWorkDay']) ? $_POST['searchWorkDay'] : ""; //TO DO: Make it to where it changes if you do "Monday" or something like that
		$searchMinPay = isset($_POST['searchMinPay']) ? $_POST['searchMinPay'] : "";
		$searchMaxPay = isset($_POST['searchMaxPay']) ? $_POST['searchMaxPay'] : "";
		//MAYBE: Add address? Ask professor first

        // Database configuration
		$servername = "museum.cpm4eq2ycfx2.us-east-1.rds.amazonaws.com";
		$username = "admin";
		$password = "museumteam5";
		$dbname = "MfahDB";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to search employees
        $sql = "SELECT EmployeeID, firstName, lastName, position, UserName, WorkDays, payrate FROM employee WHERE firstName LIKE ? AND lastName LIKE ? AND position LIKE ? AND UserName LIKE ? AND WorkDays LIKE ? AND payrate>=? AND payrate<=? ORDER BY lastName, firstName;";
        $stmt = $conn->prepare($sql);
        $searchTermFirstName = "%" . $searchFirstName . "%";
		$searchTermLastName = "%" . $searchLastName . "%";
        $searchTermPosition = "%" . $searchPosition . "%";
		$searchTermUsername = "%" . $searchUsername . "%";
		$searchTermWorkDay = "%" . $searchWorkDay . "%";
		$searchTermMinPay = $searchMinPay;
		$searchTermMaxPay = $searchMaxPay; if($searchMaxPay == ""){$searchTermMaxPay = "9999";}
        $stmt->bind_param("sssssdd", $searchTermFirstName, $searchTermLastName, $searchTermPosition, $searchTermUsername, $searchTermWorkDay, $searchTermMinPay, $searchTermMaxPay);
        $stmt->execute();
        $result = $stmt->get_result();
		
		if (isset($_POST['search'])) {
			if ($result->num_rows > 0) {
				echo "<table border='1'><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Position</th><th>User Name</th><th>Hourly Pay</th><th>Work Days</th></tr>";
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "<tr><td>".$row["EmployeeID"]."</td><td>".$row["firstName"]."</td><td>".$row["lastName"]."</td><td>".$row["position"]."</td><td>".$row["UserName"]."</td><td>\$".number_format($row["payrate"],2,'.','')."</td><td>".$row["WorkDays"]."</td></tr>";
				}
				echo "</table>";
			} else {
				echo "0 results";
			}
		} elseif (isset($_POST['download'])) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="employee_search_results.csv"');

            // Output the results to CSV
            $output = fopen("php://output", "w");
			ob_end_clean();
			fputcsv($output, ['ID', 'First Name', 'Last Name', 'Position', 'Username','Pay Rate']); // column headers

            while($row = $result->fetch_assoc()) {
                fputcsv($output, [$row['EmployeeID'], $row['firstName'], $row['lastName'], $row['position'], $row['UserName'], $row['payrate']]); // data rows
            }

            fclose($output);
            exit;
        }



		
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
