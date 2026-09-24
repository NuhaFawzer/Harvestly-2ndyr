<?php
require_once __DIR__ . '/../../config/app.php';
redirect('index.php');
exit;
?>

<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Harvestly - Fresh Vegetables Direct from Sri Lankan Farmers</title>
    <link rel="stylesheet" href="/Harvestly/css/Buyer/landing-page.css">

    <script src="/Harvestly/js/icon-fallback.js" defer></script>
</head>
<body class="font-body-md text-body-md antialiased selection:bg-primary-container selection:text-on-primary-container">
<!-- TopNavBar -->
<nav class="w-full sticky top-0 z-50 bg-surface/80 dark:bg-surface/80 backdrop-blur-md shadow-sm">
<div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-4 max-w-[1280px] mx-auto">
<a class="flex items-center gap-2" href="/Harvestly/Controller/Buyer/LandingController.php">
<img alt="Harvestly Logo" class="h-8 md:h-10 object-contain" src="/Harvestly/assets/harvestly-logo.jpeg"/>
</a>
<div class="hidden md:flex items-center gap-lg font-body-md text-body-md">
<a class="text-primary dark:text-secondary-fixed border-b-2 border-primary dark:border-secondary-fixed pb-1 active:opacity-80 transition-opacity" href="/Harvestly/Controller/Buyer/LandingController.php">Home</a>
<a class="text-on-surface-variant dark:text-outline-variant hover:text-secondary dark:hover:text-secondary-fixed-dim transition-colors duration-200 active:opacity-80 transition-opacity" href="/Harvestly/Controller/Buyer/ProductController.php">Products</a>
<a class="text-on-surface-variant dark:text-outline-variant hover:text-secondary dark:hover:text-secondary-fixed-dim transition-colors duration-200 active:opacity-80 transition-opacity" href="/Harvestly/Controller/Buyer/DashboardController.php">Categories</a>
<a class="text-on-surface-variant dark:text-outline-variant hover:text-secondary dark:hover:text-secondary-fixed-dim transition-colors duration-200 active:opacity-80 transition-opacity" href="/Harvestly/Controller/Buyer/DashboardController.php">Farmers</a>
<a class="text-on-surface-variant dark:text-outline-variant hover:text-secondary dark:hover:text-secondary-fixed-dim transition-colors duration-200 active:opacity-80 transition-opacity" href="/Harvestly/Controller/Buyer/DashboardController.php">About</a>
</div>
<div class="flex items-center gap-sm">
<a class="hidden md:block font-label-bold text-label-bold text-primary px-4 py-2 hover:bg-surface-container rounded-full transition-colors" href="/Harvestly/Controller/Buyer/AuthController.php">Login</a>
<a class="font-label-bold text-label-bold bg-primary text-on-primary px-6 py-2 rounded-full hover:bg-primary-container transition-colors shadow-sm" href="/Harvestly/Controller/Buyer/RegistrationController.php">Register</a>
<button id="mobileMenuBtn" class="md:hidden text-primary" type="button" aria-label="Open menu">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">menu</span>
</button>
</div>
</div>
</nav>
<div id="mobileMenu" class="mobile-menu" aria-hidden="true">
    <a href="/Harvestly/Controller/Buyer/LandingController.php">Home</a>
    <a href="/Harvestly/Controller/Buyer/ProductController.php">Products</a>
    <a href="/Harvestly/Controller/Buyer/DashboardController.php#categories">Categories</a>
    <a href="/Harvestly/Controller/Buyer/DashboardController.php#farmers">Farmers</a>
    <a href="/Harvestly/Controller/Buyer/DashboardController.php#about">About</a>
    <a href="/Harvestly/Controller/Buyer/AuthController.php">Login</a>
    <a href="/Harvestly/Controller/Buyer/RegistrationController.php">Register</a>
</div>

<main>
<!-- Hero Section -->
<section class="relative w-full h-[600px] md:h-[700px] flex items-center justify-center overflow-hidden">
<div class="absolute inset-0 z-0">
<div class="bg-cover bg-center w-full h-full" data-alt="A cinematic, wide-angle shot of a vibrant, lush vegetable farm in Sri Lanka during early morning golden hour. Farmers in traditional attire are carefully harvesting fresh produce. The scene is bathed in warm, soft sunlight, highlighting the dew on the leaves and the rich, dark soil. The mood is authentic, earthy, and industrious, representing high-quality agriculture." style="background-image: url('/Harvestly/assets/coconut-sri-lanka.jpeg')"></div>
<div class="absolute inset-0 bg-gradient-to-r from-surface/95 via-surface/80 to-surface/20"></div>
</div>
<div class="relative z-10 w-full max-w-[1280px] mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-2 gap-lg">
<div class="flex flex-col justify-center items-start gap-6 max-w-xl">
<h1 class="font-display-lg text-display-lg text-on-surface">
                        Fresh Vegetables Direct from <span class="text-secondary">Sri Lankan Farmers</span>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">
                        Buy farm-fresh vegetables directly from trusted farmers across Sri Lanka at affordable prices.
                    </p>
<div class="flex flex-wrap items-center gap-sm mt-4">
<a href="/Harvestly/Controller/Buyer/ProductController.php" class="font-label-bold text-label-bold bg-primary text-on-primary px-8 py-4 rounded-full hover:bg-primary-container transition-colors shadow-md">
                            Shop Now
                        </a>
<a href="/Harvestly/Controller/Buyer/RegistrationController.php" class="font-label-bold text-label-bold bg-surface-container-lowest text-primary border border-primary px-8 py-4 rounded-full hover:bg-surface-container-low transition-colors shadow-sm">
                            Become a Farmer
                        </a>
</div>
</div>
</div>
</section>
<!-- Featured Products -->
<section class="py-xl max-w-[1280px] mx-auto px-margin-mobile md:px-margin-desktop">
<div class="flex justify-between items-end mb-lg">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Fresh Harvest</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Picked today, delivered tomorrow.</p>
</div>
<a class="hidden md:flex items-center gap-2 font-label-bold text-label-bold text-secondary hover:text-primary transition-colors" href="/Harvestly/Controller/Buyer/DashboardController.php">
                    View All <span class="material-symbols-outlined text-lg">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-gutter">
<!-- Card 1 -->
<div class="bg-surface-container-lowest rounded-[24px] shadow-ambient hover:shadow-ambient-hover transition-shadow duration-300 overflow-hidden group">
<div class="relative h-64 w-full bg-surface-container-low overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A close-up, high-quality photograph of freshly harvested, vibrant orange carrots with bright green leafy tops resting on a clean, light wooden surface. The lighting is bright and natural, highlighting the texture and freshness of the organic produce. The aesthetic is modern, clean, and appetizing." src="/Harvestly/assets/images/new.jpg"/>
<div class="absolute top-4 left-4 flex flex-col gap-2">
<span class="bg-[#2EEA92] text-primary font-label-caps text-label-caps px-3 py-1 rounded-full shadow-sm">Fresh Today</span>
<span class="bg-[#E8F5E9] text-secondary font-label-caps text-label-caps px-3 py-1 rounded-full shadow-sm">Organic</span>
</div>
</div>
<div class="p-6 flex flex-col gap-4">
<div class="flex justify-between items-start">
<div>
<h3 class="font-headline-md text-headline-md text-on-surface">Carrot</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-1">
<span class="material-symbols-outlined text-sm">person</span> Nimal Perera
                                </p>
</div>
<div class="flex items-center gap-1 bg-surface-container rounded-full px-2 py-1">
<span class="material-symbols-outlined text-sm text-yellow-500" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-bold text-[12px]">4.8</span>
</div>
</div>
<div class="flex justify-between items-center mt-2">
<span class="font-headline-md text-headline-md text-primary">LKR 320<span class="font-body-sm text-body-sm text-on-surface-variant">/kg</span></span>
<button class="bg-primary text-on-primary p-3 rounded-full hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center">
<span class="material-symbols-outlined">shopping_cart</span>
</button>
</div>
</div>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest rounded-[24px] shadow-ambient hover:shadow-ambient-hover transition-shadow duration-300 overflow-hidden group">
<div class="relative h-64 w-full bg-surface-container-low overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A vibrant cluster of plump, ripe red tomatoes still attached to their green vines, displayed on a minimalist clean background. The natural light emphasizes the glossy skin and deep red color of the tomatoes, conveying extreme freshness and high quality." src="/Harvestly/assets/images/tomato.jpg"/>
<div class="absolute top-4 left-4 flex flex-col gap-2">
<span class="bg-[#2EEA92] text-primary font-label-caps text-label-caps px-3 py-1 rounded-full shadow-sm">Fresh Today</span>
</div>
</div>
<div class="p-6 flex flex-col gap-4">
<div class="flex justify-between items-start">
<div>
<h3 class="font-headline-md text-headline-md text-on-surface">Tomato</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-1">
<span class="material-symbols-outlined text-sm">person</span> Sunil Kumara
                                </p>
</div>
<div class="flex items-center gap-1 bg-surface-container rounded-full px-2 py-1">
<span class="material-symbols-outlined text-sm text-yellow-500" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-bold text-[12px]">4.5</span>
</div>
</div>
<div class="flex justify-between items-center mt-2">
<span class="font-headline-md text-headline-md text-primary">LKR 260<span class="font-body-sm text-body-sm text-on-surface-variant">/kg</span></span>
<button class="bg-primary text-on-primary p-3 rounded-full hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center">
<span class="material-symbols-outlined">shopping_cart</span>
</button>
</div>
</div>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest rounded-[24px] shadow-ambient hover:shadow-ambient-hover transition-shadow duration-300 overflow-hidden group">
<div class="relative h-64 w-full bg-surface-container-low overflow-hidden">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A neat bundle of freshly harvested leeks with bright white bases fading into vibrant green tops, bound together simply. The setting is clean and modern, bathed in soft, diffused light that highlights the crisp texture of the organic vegetables." src="/Harvestly/assets/images/leafy%20green.jpg"/>
<div class="absolute top-4 left-4 flex flex-col gap-2">
<span class="bg-[#2EEA92] text-primary font-label-caps text-label-caps px-3 py-1 rounded-full shadow-sm">Fresh Today</span>
<span class="bg-[#E8F5E9] text-secondary font-label-caps text-label-caps px-3 py-1 rounded-full shadow-sm">Organic</span>
</div>
</div>
<div class="p-6 flex flex-col gap-4">
<div class="flex justify-between items-start">
<div>
<h3 class="font-headline-md text-headline-md text-on-surface">Leeks</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1 mt-1">
<span class="material-symbols-outlined text-sm">person</span> Chamara Silva
                                </p>
</div>
<div class="flex items-center gap-1 bg-surface-container rounded-full px-2 py-1">
<span class="material-symbols-outlined text-sm text-yellow-500" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-bold text-[12px]">4.9</span>
</div>
</div>
<div class="flex justify-between items-center mt-2">
<span class="font-headline-md text-headline-md text-primary">LKR 450<span class="font-body-sm text-body-sm text-on-surface-variant">/kg</span></span>
<button class="bg-primary text-on-primary p-3 rounded-full hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center">
<span class="material-symbols-outlined">shopping_cart</span>
</button>
</div>
</div>
</div>
</div>
<div class="mt-lg flex justify-center md:hidden">
<a class="font-label-bold text-label-bold text-secondary border border-secondary px-6 py-3 rounded-full hover:bg-surface-container-low transition-colors" href="/Harvestly/Controller/Buyer/DashboardController.php">
                    View All Products
                </a>
</div>
</section>
</main>
<!-- Footer -->
<footer class="w-full pt-xl pb-lg bg-primary dark:bg-tertiary">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter px-margin-mobile md:px-margin-desktop max-w-[1280px] mx-auto text-on-primary dark:text-on-tertiary">
<div class="col-span-1 md:col-span-1 flex flex-col gap-4">
<span class="font-headline-md text-headline-md text-on-primary dark:text-on-tertiary">Harvestly</span>
<p class="font-body-sm text-body-sm text-on-primary/80 dark:text-on-tertiary/80 mt-2 max-w-xs">
                    © 2026 Harvestly. Bridging Sri Lankan Fields to Your Table.
                </p>
</div>
<div class="col-span-1 flex flex-col gap-3">
<a class="font-body-sm text-body-sm text-on-primary/80 dark:text-on-tertiary/80 hover:text-secondary-fixed dark:hover:text-secondary-fixed-dim transition-colors" href="/Harvestly/Controller/Buyer/DashboardController.php">About Harvestly</a>
<a class="font-body-sm text-body-sm text-on-primary/80 dark:text-on-tertiary/80 hover:text-secondary-fixed dark:hover:text-secondary-fixed-dim transition-colors" href="/Harvestly/Controller/Buyer/DashboardController.php">Quick Links</a>
<a class="font-body-sm text-body-sm text-on-primary/80 dark:text-on-tertiary/80 hover:text-secondary-fixed dark:hover:text-secondary-fixed-dim transition-colors" href="/Harvestly/Controller/Buyer/DashboardController.php">Contact Us</a>
<a class="font-body-sm text-body-sm text-on-primary/80 dark:text-on-tertiary/80 hover:text-secondary-fixed dark:hover:text-secondary-fixed-dim transition-colors" href="/Harvestly/Controller/Buyer/DashboardController.php">Privacy Policy</a>
<a class="font-body-sm text-body-sm text-on-primary/80 dark:text-on-tertiary/80 hover:text-secondary-fixed dark:hover:text-secondary-fixed-dim transition-colors" href="/Harvestly/Controller/Buyer/DashboardController.php">Terms of Service</a>
</div>
<div class="col-span-1 md:col-span-2 flex flex-col gap-4">
<h4 class="font-label-bold text-label-bold text-on-primary mb-2">Subscribe to our Newsletter</h4>
<div class="flex gap-2 w-full max-w-md">
<input id="newsletterEmail" class="flex-1 bg-surface/10 border border-on-primary/20 rounded-full px-4 py-2 font-body-md text-on-primary placeholder:text-on-primary/50 focus:outline-none focus:border-secondary-fixed focus:ring-1 focus:ring-secondary-fixed" placeholder="Email address" type="email"/>
<button id="subscribeBtn" type="button" class="bg-secondary-fixed text-primary font-label-bold px-6 py-2 rounded-full hover:bg-[#2EEA92] transition-colors">
                        Subscribe
                    </button>
</div>
</div>
</div>
</footer>
    <script src="/Harvestly/js/Buyer/landing-page.js"></script>
</body></html>