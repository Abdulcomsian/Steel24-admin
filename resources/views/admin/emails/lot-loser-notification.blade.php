<!-- lot-loser-notification.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Lot Loser Notification</title>
</head>
<body>
    <h1>Lot Loser Notification</h1>
    <p>Dear {{ $loserName }},</p>
    <p>Unfortunately, you have lost the bid for Lot #{{ $lotId }}.</p>
    <p>Thank you for participating. Better luck next time!</p>
</body>
</html>
