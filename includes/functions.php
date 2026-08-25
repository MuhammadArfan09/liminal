<?php
/**
 * Shared helper functions.
 */

declare(strict_types=1);

const STUDIO_RATE_PER_HOUR = 75000;
const STUDIO_OPEN_HOUR     = 10; // 10:00
const STUDIO_CLOSE_HOUR    = 23; // 23:00
const UPLOAD_DIR           = __DIR__ . '/../uploads/proofs/';
const UPLOAD_MAX_BYTES     = 5 * 1024 * 1024; // 5MB
const ALLOWED_MIME_TYPES   = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'application/pdf' => 'pdf',
];

/** Escape output safely. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Start a session if one isn't already active. */
function ensureSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

/** Generate (or reuse) a CSRF token for the current session. */
function csrfToken(): string
{
    ensureSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Verify a submitted CSRF token. */
function verifyCsrfToken(?string $token): bool
{
    ensureSession();
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/** Format an integer rupiah amount, e.g. 150000 -> "Rp150.000". */
function formatRupiah(int $amount): string
{
    return 'Rp' . number_format($amount, 0, ',', '.');
}

/** Format a Y-m-d date string as "25 August 2026". */
function formatDateLong(string $date): string
{
    $ts = strtotime($date);
    return $ts ? date('d F Y', $ts) : e($date);
}

/** Calculate total price for a given duration in hours. */
function calculateTotal(int $hours): int
{
    return max(0, $hours) * STUDIO_RATE_PER_HOUR;
}

/** Generate a unique, human-readable booking code: LMN-YYYYMMDD-XXX */
function generateBookingCode(PDO $pdo, string $bookingDate): string
{
    $prefix = 'LMN-' . date('Ymd', strtotime($bookingDate)) . '-';

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM bookings WHERE booking_code LIKE :prefix"
    );
    $stmt->execute(['prefix' => $prefix . '%']);
    $count = (int) $stmt->fetchColumn();

    return $prefix . str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);
}

/**
 * Check whether a requested time range overlaps any existing active
 * booking for the same date. Returns true if the slot is available.
 *
 * Overlap rule: NOT (new_end <= existing_start OR new_start >= existing_end)
 */
function isSlotAvailable(PDO $pdo, string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
{
    $sql = "SELECT COUNT(*) FROM bookings
            WHERE booking_date = :date
              AND status IN ('pending', 'waiting_payment', 'confirmed')
              AND NOT (:end_time <= start_time OR :start_time >= end_time)";

    $params = [
        'date'       => $date,
        'start_time' => $startTime,
        'end_time'   => $endTime,
    ];

    if ($excludeBookingId !== null) {
        $sql .= ' AND id != :exclude_id';
        $params['exclude_id'] = $excludeBookingId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return ((int) $stmt->fetchColumn()) === 0;
}

/** Fetch all active booked slots for a given date (for availability display). */
function getBookedSlotsForDate(PDO $pdo, string $date): array
{
    $stmt = $pdo->prepare(
        "SELECT start_time, end_time FROM bookings
         WHERE booking_date = :date
           AND status IN ('pending', 'waiting_payment', 'confirmed')
         ORDER BY start_time ASC"
    );
    $stmt->execute(['date' => $date]);
    return $stmt->fetchAll();
}

/** Validate a WhatsApp/phone number (digits, spaces, +, -, 8-15 digits). */
function isValidPhone(string $phone): bool
{
    $digits = preg_replace('/\D/', '', $phone);
    return strlen($digits) >= 8 && strlen($digits) <= 15;
}

/** Safely handle a payment-proof upload. Returns the stored filename or null on failure. */
function handleProofUpload(array $file): ?string
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    if ($file['size'] > UPLOAD_MAX_BYTES) {
        return null;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset(ALLOWED_MIME_TYPES[$mime])) {
        return null;
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $extension = ALLOWED_MIME_TYPES[$mime];
    $filename  = bin2hex(random_bytes(16)) . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return $filename;
}

/** Redirect with a flash message stored in session. */
function redirectWithError(string $location, string $message): never
{
    ensureSession();
    $_SESSION['flash_error'] = $message;
    header('Location: ' . $location);
    exit;
}

function getFlashError(): ?string
{
    ensureSession();
    $message = $_SESSION['flash_error'] ?? null;
    unset($_SESSION['flash_error']);
    return $message;
}

/** Fetch a single booking by id. */
function getBookingById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/** Fetch the latest payment for a booking. */
function getLatestPaymentForBooking(PDO $pdo, int $bookingId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT * FROM payments WHERE booking_id = :id ORDER BY created_at DESC LIMIT 1'
    );
    $stmt->execute(['id' => $bookingId]);
    $row = $stmt->fetch();
    return $row ?: null;
}
