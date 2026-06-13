<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | LuxeStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-indigo-600">LuxeStay</a>
            <div class="flex gap-8 items-center">
                <a href="/" class="text-slate-600 font-medium">Home</a>
                <a href="/rooms" class="text-slate-600 font-medium">Rooms</a>
                <a href="/about" class="text-indigo-600 font-bold">About</a>
                <a href="/contact" class="text-slate-600 font-medium">Contact</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="py-24 bg-gradient-premium text-white">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h1 class="text-5xl md:text-7xl font-bold mb-6">Our Story</h1>
                <p class="text-xl opacity-90 leading-relaxed">Redefining luxury hospitality since 2010. We believe every stay should be an unforgettable masterpiece.</p>
            </div>
        </section>

        <section class="py-24 max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div class="animate-on-scroll">
                    <h2 class="text-4xl font-bold text-slate-900 mb-6">World-Class Excellence</h2>
                    <p class="text-slate-500 text-lg mb-8 leading-relaxed">At LuxeStay, we don't just provide rooms; we provide experiences. Our dedicated team of over 500 professionals works tirelessly to ensure that every detail of your stay is perfect.</p>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <div class="text-4xl font-bold text-indigo-600">50+</div>
                            <div class="text-slate-400 font-medium">Luxury Hotels</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-indigo-600">10k+</div>
                            <div class="text-slate-400 font-medium">Happy Guests</div>
                        </div>
                    </div>
                </div>
                <div class="relative rounded-[48px] overflow-hidden shadow-2xl animate-on-scroll">
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb" class="w-full h-[500px] object-cover" alt="About LuxeStay">
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-slate-900 text-white py-12 mt-24">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="text-2xl font-bold mb-4">LuxeStay</div>
            <p class="text-slate-400">&copy; <?php echo date('Y'); ?> LuxeStay International. All rights reserved.</p>
        </div>
    </footer>

    <script src="/js/main.js"></script>
</body>
</html>
