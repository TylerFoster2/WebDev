<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Listing</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <h1>Just Added</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = isset($_POST['first']) ? htmlspecialchars($_POST['first']) : '';
        $lastname  = isset($_POST['last']) ? htmlspecialchars($_POST['last']) : '';
        $country   = isset($_POST['country']) ? htmlspecialchars($_POST['country']) : '';
        $email     = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
        $username_post = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';

        echo "<p>Adding <strong>$firstname $lastname</strong> from $country.</p>";

        $servername = "localhost";
        $username = "user20";
        $password = "20muir";
        $dbname = "db20";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("INSERT INTO randuser (first, last, country, email, username) VALUES (:firstname, :lastname, :country, :email, :username)");
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username_post);

            echo "<div>";
            if ($stmt->execute()) {
                echo "<p>New record created successfully</p>";
            } else {
                echo "<p>Error: Unable to create a new record.</p>";
            }
            echo "</div>";

            $sql = "SELECT first, last, country, email, username FROM randuser ORDER BY id DESC";
            $result = $conn->query($sql);

            echo "<div>";
            echo "<table>";
            echo "<thead><tr><th>First Name</th><th>Last Name</th><th>Country</th><th>Email</th><th>Username</th></tr></thead><tbody>";

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['first']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last']) . "</td>";
                echo "<td>" . htmlspecialchars($row['country']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "</div>";

        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }

        $conn = null;

    } else {
        echo "<p>No data was submitted.</p>";
    }
    ?>
</body>
</html>