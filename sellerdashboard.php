<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: realEstateLogin.php");
    exit;
}

// Database configuration
$servername = "localhost";
$db_username = "root";
$db_password = "root";
$dbname = "gsu_real_estate";

$error = "";

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch user data by username
    $query = "SELECT * FROM signup WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $_SESSION["username"]);
    $stmt->execute();
    $user = $stmt->fetch();

    // Query to fetch properties for the seller
    $propertiesQuery = "SELECT * FROM properties WHERE user = :username";
    $propertiesStmt = $conn->prepare($propertiesQuery);
    $propertiesStmt->bindParam(":username", $_SESSION["username"]);
    $propertiesStmt->execute();
    $properties = $propertiesStmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <style>
        /* Add CSS for background image and centering */
        body {
            background-image: url('body3.jpg'); /* Replace with your image path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .header-title {
            text-align: center;
            flex-grow: 1; /* Allows the title to take up available space */
        }

        .logout-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 20px; /* Move the Logout button to the right */
        }
        .button-row {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        .button-row a {
            text-decoration: none;
            color: white;
            margin: 0 20px;
        }

        .button-row a:hover {
            text-decoration: underline;
        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: calc(100vh - 120px);
        }

        .property-table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px;
        }

        .property-table th, .property-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <header>
       
        <h1 class="header-title"><br>Seller Dashboard</h1>
        <form method="post" action="realEstateLogin.php"> <!-- Updated action attribute -->
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </header>
     <div class="button-row">
        <a href="index.html">Home Page</a>
        <a href="sellerprofile.php">My Profile</a>
    </div>
    <section class="content-wrapper">
        <h2>My Property Listings</h2> <!-- Added title here -->
        <table class="property-table">
            <tr>
                <th>Property ID</th>
                <th>Seller ID</th>
                <th>Street Name</th>
                <th>City</th>
                <th>Zip Code</th>
                <th>School Rating</th>
                <th>Property Price</th>
                <th>Environmental Rating</th>
                <th>Energy Efficiency Rating</th>
            </tr>
            <?php foreach ($properties as $property) { ?>
                <tr>
                    <td><?php echo $property['property_id']; ?></td>
                    <td><?php echo $property['seller_id']; ?></td>
                    <td><?php echo $property['street_name']; ?></td>
                    <td><?php echo $property['city']; ?></td>
                    <td><?php echo $property['zip_code']; ?></td>
                    <td><?php echo $property['school_rating']; ?></td>
                    <td><?php echo $property['property_price']; ?></td>
                    <td><?php echo $property['environmental_rating']; ?></td>
                    <td><?php echo $property['energy_efficiency_rating']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </section>
    <footer class="bottom-box">
        &copy; 2023 Skyline Haven
    </footer>
</body>
</html>
