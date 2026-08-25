<?php
declare(strict_types=1);
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/database.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date']);
    exit;
}

$pdo = getDbConnection();
$booked = getBookedSlotsForDate($pdo, $date);

echo json_encode([
    'date'   => $date,
    'booked' => array_map(static fn ($row) => [
        'start_time' => substr($row['start_time'], 0, 5),
        'end_time'   => substr($row['end_time'], 0, 5),
    ], $booked),
]);
