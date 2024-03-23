<!DOCTYPE html>
<html lang="en">
<header>
  	<h1>Natural Museum of Fine Arts - Gift Shop Item Search</h1>
</header>
<head>
    <meta charset="UTF-8">
    <title>Employee Search</title>
	<link rel="stylesheet" href="style/datareport_styles.css" />
</head>
<body>
	<br><br>
    <div class="datareportinput">
		<h2>Gift Shop Item Search</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label for="searchName">Name: </label><input type="text" id="searchName" name="searchName"><br>
			<label for="searchMinPrice">Minimum Price: </label><input type="text" id="searchMinPrice" name="searchMinPrice"><br>
			<label for="searchMaxPrice">Maximum Price: </label><input type="text" id="searchMaxPrice" name="searchMaxPrice"><br>
			<label for="searchShop">Gift Shop: </label><input type="text" id="searchShop" name="searchShop"><br>
			<label for="searchType">Item Type: </label><input type="text" id="searchType" name="searchType"><br>
			<br>
			<button type="submit" name="search">Search</button>
			<br>
			<button type="submit" name="download" value="download">Download as CSV</button><br><br>
    	</form>
	</div><br><br>

	<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchName = isset($_POST['searchName']) ? $_POST['searchName'] : "";
		$searchMinPrice = isset($_POST['searchMinPrice']) ? $_POST['searchMinPrice'] : "";
        $searchMaxPrice = isset($_POST['searchMaxPrice']) ? $_POST['searchMaxPrice'] : "";
		$searchShop = isset($_POST['searchShop']) ? $_POST['searchShop'] : "";
		$searchType = isset($_POST['searchType']) ? $_POST['searchType'] : "";

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
        $sql = "SELECT itemName, Price, GiftShopName, ItemType, Stock, ItemID FROM giftshop, giftshopitem WHERE giftshop.giftshopid=giftshopitem.giftshopid AND itemName LIKE ? AND Price>=? AND Price<=? AND GiftShopName LIKE ? AND ItemType LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTermName = "%" . $searchName . "%";
		$searchTermMinPrice = $searchMinPrice; if($searchMinPrice == ""){$searchMinPrice = "-1";}
		$searchTermMaxPrice = $searchMaxPrice; if($searchMaxPrice == ""){$searchTermMaxPrice = "9999";}
        $searchTermShop = "%" . $searchShop . "%";
		$searchTermType = "%" . $searchType . "%";

        $stmt->bind_param("sddss", $searchTermName, $searchTermMinPrice, $searchTermMaxPrice, $searchTermShop, $searchTermType);
        $stmt->execute();
        $result = $stmt->get_result();
		
		if (isset($_POST['search'])) {
			if ($result->num_rows > 0) {
				echo "<table border='1'><tr><th>Item Name</th><th>Price</th><th>Gift Shop Name</th><th>Item Type</th><th>Stock</th></tr>";
				// output data of each row
				//itemName, Price, GiftShopName, ItemType, Stock, ItemID
				while($row = $result->fetch_assoc()) {
					echo "<tr><td>".$row["itemName"]."</td><td>".$row["Price"]."</td><td>".$row["GiftShopName"]."</td><td>".$row["ItemType"]."</td><td>".$row["Stock"]."</td></tr>";
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
			fputcsv($output, ['Item Name', 'Price', 'Gift Shop Name', 'Item Type', 'Stock']); // column headers

            while($row = $result->fetch_assoc()) {
                fputcsv($output, [$row['itemName'], $row['Price'], $row['GiftShopName'], $row['ItemType'], $row['Stock']]); // data rows
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
