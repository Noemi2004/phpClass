<?php
// Include the database connection file
require 'db-connect.php';

// Initialize variables
$name = $description = $presenter = $date = $time = "";
$nameErr = $descriptionErr = $presenterErr = $dateErr = $timeErr = "";

// Honeypot field
$honeypot = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Honeypot validation
    if (!empty($_POST['honeypot'])) {
        die("Spam detected!");
    }

    // Validate and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $presenter = filter_input(INPUT_POST, 'presenter', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);

    // Check for errors
    if (empty($name)) {
        $nameErr = "Name is required";
    }
    if (empty($description)) {
        $descriptionErr = "Description is required";
    }
    if (empty($presenter)) {
        $presenterErr = "Presenter is required";
    }
    if (empty($date)) {
        $dateErr = "Date is required";
    }
    if (empty($time)) {
        $timeErr = "Time is required";
    }

    // If no errors, insert data into the database
    if (empty($nameErr) && empty($descriptionErr) && empty($presenterErr) && empty($dateErr) && empty($timeErr)) {
        try {
            $date_inserted = date("Y-m-d H:i:s");
            $date_updated = date("Y-m-d H:i:s");

            // Prepare SQL statement
            $sql = "INSERT INTO wdv341_event (name, description, presenter, date, time, date_inserted, date_updated) VALUES (:name, :description, :presenter, :date, :time, :date_inserted, :date_updated)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':presenter', $presenter);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':date_inserted', $date_inserted);
            $stmt->bindParam(':date_updated', $date_updated);

            // Execute statement
            $stmt->execute();

            // Confirmation message
            echo "Event successfully added!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Form</title>
</head>
<body>
    <h1>Event Form</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <span><?php echo $nameErr; ?></span><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($description); ?>">
        <span><?php echo $descriptionErr; ?></span><br>

        <label for="presenter">Presenter:</label>
        <input type="text" id="presenter" name="presenter" value="<?php echo htmlspecialchars($presenter); ?>">
        <span><?php echo $presenterErr; ?></span><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
        <span><?php echo $dateErr; ?></span><br>

        <label for="time">Time:</label>
        <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($time); ?>">
        <span><?php echo $timeErr; ?></span><br>

        <!-- Honeypot field -->
        <input type="text" name="honeypot" style="display:none">

        <input type="submit" value="Submit">
    </form>
</body>
</html>