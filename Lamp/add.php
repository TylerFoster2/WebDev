<?php
    // Enable error reporting in development; set to 0 on production
    error_reporting(E_ALL);
    ini_set('display_errors', 0); // hide on screen, we'll log instead

    // Debug flag (set to true to show POST contents on the page)
    $debug = false;

    // Log function
    function app_log($msg) {
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'lamp_add_php.log';
        error_log(date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, 3, $file);
    }

    if ($debug) {
        echo '<pre>POST: ' . htmlspecialchars(print_r($_POST, true)) . '</pre>';
    }

    // Collect input using POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Safely fetch fields with fallback
        $firstname = isset($_POST['first']) ? trim($_POST['first']) : '';
        $lastname  = isset($_POST['last']) ? trim($_POST['last']) : '';
        $country   = isset($_POST['country']) ? trim($_POST['country']) : '';
        $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
        $username_post = isset($_POST['username']) ? trim($_POST['username']) : '';

        // Minimal sanitization for display; use prepared statements for DB
        $firstname_display = htmlspecialchars($firstname);
        $lastname_display  = htmlspecialchars($lastname);

        echo "<p>Adding <strong>$firstname_display $lastname_display</strong>.</p>";

        // DATABASE OPERATIONS:
        // NOTE: update these credentials for the school server
        $db_server = "localhost";
        $db_user   = "user20";   // replace with your DB user
        $db_pass   = "20muir";   // replace with your DB password
        $db_name   = "db20";     // replace with your DB name

        // Basic validation
        if ($db_user === '' || $db_name === '') {
            echo '<p>Database credentials are not configured.</p>';
            app_log('Missing DB credentials in add.php');
            exit;
        }

        try {
            // Create a PDO connection
            $conn = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            // Prepare SQL and bind parameters (insert multiple fields)
            $sql = "INSERT INTO randuser (first, last, country, email, username) VALUES (:first, :last, :country, :email, :username)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':first', $firstname);
            $stmt->bindParam(':last', $lastname);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username_post);

            if ($stmt->execute()) {
                echo '<p>New record created successfully.</p>';
            } else {
                echo '<p>Error: Unable to create a new record.</p>';
                app_log('Insert execute() returned false');
            }

            // Select and display all users from the database
            $sql = "SELECT first, last, country, email, username FROM randuser ORDER BY id DESC";
            $result = $conn->query($sql);

            echo "<div>";
            echo "<table>";
            echo "<thead><tr><th>First Name</th><th>Last Name</th><th>Country</th><th>Email</th><th>Username</th></tr></thead><tbody>";

            while ($row = $result->fetch()) {
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
            // Log the error and show a friendly message
            app_log('PDOException: ' . $e->getMessage());
            echo '<p>Database error. The administrator has been notified.</p>';
        }

        // Close the connection
        $conn = null;

    } else {
        echo "<p>No data was submitted.</p>";
    }
    } else {
        echo "<p>No data was submitted.</p>";
    }
    ?>
</body>
</html>