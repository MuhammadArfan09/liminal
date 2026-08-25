<?php
declare(strict_types=1);
require __DIR__ . '/includes/functions.php';
ensureSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    redirectWithError('contact.php', 'Your session expired. Please try again.');
}

$name    = trim((string) ($_POST['name'] ?? ''));
$email   = trim((string) ($_POST['email'] ?? ''));
$phone   = trim((string) ($_POST['phone'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || $subject === '' || $message === '') {
    redirectWithError('contact.php', 'Please fill in all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError('contact.php', 'Please enter a valid email address.');
}

if (!isValidPhone($phone)) {
    redirectWithError('contact.php', 'Please enter a valid phone number.');
}

// In production this would notify the studio (e.g. via email or WhatsApp API).
// Kept intentionally simple for this build stage.

header('Location: contact.php?sent=1');
exit;
