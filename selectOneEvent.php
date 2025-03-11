<?php
include 'dbConnect.php';

$eventId = 1;

try {
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = :eventId");
    $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);


    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $event = null;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Event Details</h1>
    <?php if ($event): ?>
        <table>
            <tr>
                <th>Event ID</th>
                echo htmlspecialchars($event['event_id']); ?></td>
            </tr>
            <tr>
                <th>Event Name</th>
                <td><?php echo htmlspecialchars($event['event_name']); ?></td>
            </tr>
            <tr>
                <th>Event Date</th>
                <td><?php echo htmlspecialchars($event['event_date']); ?></td>
            </tr>
            <tr>
                <th>Event Description</th>
                <td><?php echo htmlspecialchars($event['event_description']); ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>No event found with the specified ID.</p>
    <?php endif; ?>
</body>
</html>
