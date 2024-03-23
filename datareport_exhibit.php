<!DOCTYPE html>
<html lang="en">
<header>
  	<h1>Natural Museum of Fine Arts - Exhibit Search</h1>
</header>
<head>
    <meta charset="UTF-8">
    <title>Employee Search</title>
	<link rel="stylesheet" href="style/datareport_styles.css" />
</head>
<body>
	<br><br>
    <div class="datareportinput">
		<h2>Exhibit Search</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label for="searchTitle">Title: </label><input type="text" id="searchTitle" name="searchTitle"><br>
			<label for="searchType">Exhibit Type: </label><input type="text" id="searchType" name="searchType"><br>
			<label for="searchBuilding">Building: </label><input type="text" id="searchBuilding" name="searchBuilding"><br>
			<label for="searchFloor">Floor: </label><input type="text" id="searchFloor" name="searchFloor"><br>
			<label for="searchDescription">Description:</label><input type="text" id="searchDescription" name="searchDescription"><br>
			<br>
			<button type="submit" name="search">Search</button>
			<br>
			<button type="submit" name="download" value="download">Download as CSV</button><br><br>
    	</form>
	</div><br><br>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchTitle = isset($_POST['searchTitle']) ? $_POST['searchTitle'] : "";
		$searchType = isset($_POST['searchType']) ? $_POST['searchType'] : "";
        $searchBuilding = isset($_POST['searchBuilding']) ? $_POST['searchBuilding'] : "";
		$searchFloor = isset($_POST['searchFloor']) ? $_POST['searchFloor'] : "";
		$searchDescription = isset($_POST['searchDescription']) ? $_POST['searchDescription'] : "";
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
        $sql = "SELECT ExhibitType, ExhibitTitle, ExhibitDescription, Building, RoomFloor FROM exhibit, room WHERE room.RoomID=exhibit.RoomID AND ExhibitType LIKE ? AND ExhibitTitle LIKE ? AND ExhibitDescription LIKE ? AND (Building=? OR ?=0) AND (RoomFloor=? OR ?=0);";
        $stmt = $conn->prepare($sql);
        $searchTermTitle = "%" . $searchTitle . "%";
		$searchTermType = "%" . $searchType . "%";
        $noBuilding="1"; $searchTermBuilding = $searchBuilding; if($searchBuilding == ""){$searchTermBuilding = "-1"; $noBuilding = 0;};
		$noFloor="1"; $searchTermFloor = $searchFloor; if($searchFloor == ""){$searchTermFloor = "-1"; $noFloor="0";}
		$searchTermDescription = "%" . $searchDescription . "%";
        $stmt->bind_param("sssiiii", $searchTermType, $searchTermTitle, $searchTermDescription, $searchTermBuilding, $noBuilding, $searchTermFloor, $searchFloor);
        $stmt->execute();
        $result = $stmt->get_result();
		
		if (isset($_POST['search'])) {
			if ($result->num_rows > 0) {
				echo "<table border='1'><tr><th>Title</th><th>Type</th><th>Description</th><th>Building#</th><th>Floor</th></tr>";
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "<tr><td>".$row["ExhibitTitle"]."</td><td>".$row["ExhibitType"]."</td><td>".$row["ExhibitDescription"]."</td><td>".$row["Building"]."</td><td>".$row["RoomFloor"]."</td></tr>";
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
