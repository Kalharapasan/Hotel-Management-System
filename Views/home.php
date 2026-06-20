<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStay | MVC Edition</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?php echo BASE_URL; ?>/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-50">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 glass border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center"><span class="text-2xl font-bold text-indigo-600">LuxeStay</span></div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="<?php echo BASE_URL; ?>/" class="text-indigo-600 font-bold">Hotels</a>
                    <a href="<?php echo BASE_URL; ?>/trips" class="text-gray-700 hover:text-indigo-600">Trips</a>
                    <a href="<?php echo BASE_URL; ?>/flights" class="text-gray-700 hover:text-indigo-600">Flights</a>
                    <a href="<?php echo BASE_URL; ?>/restaurant" class="text-gray-700 hover:text-indigo-600">Restaurant</a>
                    <a href="<?php echo BASE_URL; ?>/about" class="text-gray-700 hover:text-indigo-600">About</a>
                    <a href="<?php echo BASE_URL; ?>/contact" class="text-gray-700 hover:text-indigo-600">Contact</a>
                    <a href="<?php echo BASE_URL; ?>/admin" class="bg-indigo-600 text-white px-5 py-2 rounded-full shadow-lg">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative h-[600px] flex items-center justify-center overflow-hidden">
        <img src="<?php echo asset_url($hero['image_url'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945'); ?>" class="absolute inset-0 w-full h-full object-cover" alt="Hero">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative text-center text-white px-4 max-w-4xl">
            <h1 class="text-5xl md:text-7xl font-bold mb-6"><?php echo $hero['title'] ?? 'Discover Luxury, Redefined.'; ?></h1>
            <p class="text-xl md:text-2xl opacity-90 mb-10"><?php echo $hero['content'] ?? 'Book the finest hotels, flights, and trips - all in one seamless experience.'; ?></p>
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <a href="#hotels" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-bold shadow-xl">Browse Hotels</a>
            </div>

            <!-- Search Bar -->
            <div class="bg-white/10 backdrop-blur-xl p-4 rounded-[32px] border border-white/20 shadow-2xl max-w-3xl mx-auto">
                <form action="<?php echo BASE_URL; ?>/" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" placeholder="Search hotels..." value="<?php echo $_GET['search'] ?? ''; ?>"
                               class="w-full bg-white/20 border border-white/30 rounded-2xl px-6 py-4 text-white placeholder-white/60 outline-none focus:bg-white/30 transition">
                    </div>
                    <div class="flex-1 relative">
                        <input type="text" name="location" placeholder="Location..." value="<?php echo $_GET['location'] ?? ''; ?>"
                               class="w-full bg-white/20 border border-white/30 rounded-2xl px-6 py-4 text-white placeholder-white/60 outline-none focus:bg-white/30 transition">
                    </div>
                    <button type="submit" class="bg-white text-indigo-600 px-8 py-4 rounded-2xl font-bold hover:bg-indigo-50 transition shadow-lg">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main id="hotels" class="max-w-7xl mx-auto px-4 py-16">
        <!-- Room Categories -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-slate-900 mb-8 text-center">Explore by Room Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while($cat = $categories->fetch_assoc()): ?>
                <div class="bg-indigo-50 p-8 rounded-[32px] text-center hover:bg-indigo-600 hover:text-white transition group cursor-pointer">
                    <h3 class="text-xl font-bold mb-2"><?php echo $cat['category_name']; ?></h3>
                    <p class="text-sm opacity-70"><?php echo $cat['description']; ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Recommended Hotels</h2>
                <p class="text-slate-500 mt-2">Handpicked stays for the ultimate comfort.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while($hotel = $hotels->fetch_assoc()): ?>
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-slate-100 group">
                <div class="relative h-64 overflow-hidden">
                    <img src="<?php echo asset_url($hotel['image_url']); ?>" alt="" class="w-full h-full object-cover group-hover:scale-110 transition">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-sm font-bold text-indigo-600">
                        $<?php echo number_format($hotel['price_per_night']); ?> / night
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-2"><?php echo $hotel['name']; ?></h3>
                    <p class="text-slate-500 text-sm mb-4"><?php echo $hotel['location']; ?></p>
                    <a href="#" class="block text-center bg-slate-900 text-white py-3 rounded-xl font-semibold">Book Now</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Gallery Section -->
        <div class="mt-32">
            <h2 class="text-3xl font-bold text-slate-900 mb-2">Visual Experience</h2>
            <p class="text-slate-500 mb-12">Peek inside our luxury world.</p>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php while($img = $gallery->fetch_assoc()): ?>
                <div class="aspect-square rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <img src="<?php echo asset_url($img['image_url']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="">
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <!-- Services Section -->
        <div class="mt-32">
            <h2 class="text-3xl font-bold text-slate-900 mb-2 text-center">Exclusive Services & Amenities</h2>
            <p class="text-slate-500 mb-12 text-center">Experience world-class facilities designed for your comfort.</p>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 text-center hover:bg-indigo-600 hover:text-white transition group cursor-pointer">
                    <div class="text-3xl mb-4 group-hover:scale-110 transition">🏋️‍♂️</div>
                    <h3 class="font-bold">Gym</h3>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 text-center hover:bg-indigo-600 hover:text-white transition group cursor-pointer">
                    <div class="text-3xl mb-4 group-hover:scale-110 transition">💆‍♀️</div>
                    <h3 class="font-bold">Spa</h3>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 text-center hover:bg-indigo-600 hover:text-white transition group cursor-pointer">
                    <div class="text-3xl mb-4 group-hover:scale-110 transition">🏊‍♂️</div>
                    <h3 class="font-bold">Pool</h3>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 text-center hover:bg-indigo-600 hover:text-white transition group cursor-pointer">
                    <div class="text-3xl mb-4 group-hover:scale-110 transition">🍸</div>
                    <h3 class="font-bold">Bar</h3>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-slate-100 text-center hover:bg-indigo-600 hover:text-white transition group cursor-pointer">
                    <div class="text-3xl mb-4 group-hover:scale-110 transition">🍽️</div>
                    <h3 class="font-bold">Restaurant</h3>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="mt-32">
            <h2 class="text-3xl font-bold text-slate-900 mb-2 text-center">Meet Our Dedicated Team</h2>
            <p class="text-slate-500 mb-12 text-center">The people behind your luxury experience.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php while($emp = $employees->fetch_assoc()): ?>
                <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm text-center hover:shadow-xl transition">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl font-bold text-indigo-600">
                        <?php echo substr($emp['fullname'], 0, 1); ?>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900"><?php echo $emp['fullname']; ?></h3>
                    <p class="text-indigo-600 font-medium text-sm mb-4"><?php echo $emp['role']; ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

    </main>
    <script src="<?php echo BASE_URL; ?>/js/main.js"></script>
</body>
</html>
