<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
ensureSession();

$pageTitle       = $pageTitle ?? 'Liminal Studio — Your Space. Your Sound.';
$pageDescription = $pageDescription ?? 'Professional band rehearsal space built for musicians who take their sound seriously.';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">

<!-- Tailwind CSS (CDN, configured for the Liminal Studio design system) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          base: {
            DEFAULT: '#08080B',
            soft: '#0F0F14',
          },
          card: '#121218',
          ink: {
            primary: '#FFFFFF',
            secondary: '#A1A1AA',
          },
        },
        fontFamily: {
          display: ['Manrope', 'sans-serif'],
          sans: ['Inter', 'sans-serif'],
        },
        boxShadow: {
          glow: '0 0 60px -10px rgba(139, 92, 246, 0.35)',
          'glow-sm': '0 0 30px -8px rgba(139, 92, 246, 0.4)',
        },
      },
    },
  };
</script>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome Free -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link rel="stylesheet" href="/css/input.css">
</head>
<body class="bg-base text-ink-primary font-sans antialiased selection:bg-violet-500/30">
