<?php
require_once 'config/db.php';
$conn = getDb();
$trips = $conn->query("SELECT * FROM trips ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Trips | LuxeStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-50">
    <!-- Navbar (Same as index.php) -->
    <nav class="sticky top-0 z-50 glass border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center"><span class="text-2xl font-bold text-indigo-600">LuxeStay</span></div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 transition">Hotels</a>
                    <a href="trips.php" class="text-indigo-600 font-bold transition">Trips</a>
                    <a href="flights.php" class="text-gray-700 hover:text-indigo-600 transition">Flights</a>
                    <a href="admin/login.php" class="bg-indigo-600 text-white px-5 py-2 rounded-full hover:bg-indigo-700 transition">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Curated Trip Plans</h1>
        <p class="text-slate-500 mb-12">Handpicked adventures across the globe.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php while($trip = $trips->fetch_assoc()): ?>
            <div class="group cursor-pointer">
                <div class="relative h-80 rounded-3xl overflow-hidden mb-4">
                    <img src="<?php echo $trip['image_url']; ?>" alt="<?php echo $trip['title']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <span class="bg-indigo-600 px-3 py-1 rounded-full text-xs font-bold mb-2 inline-block"><?php echo $trip['duration']; ?></span>
                        <h3 class="text-2xl font-bold"><?php echo $trip['title']; ?></h3>
                    </div>
                </div>
                <div class="px-2">
                    <p class="text-slate-500 text-sm mb-4 line-clamp-2"><?php echo $trip['description']; ?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-slate-900">From $<?php echo number_format($trip['price']); ?></span>
                        <button class="bg-slate-900 text-white px-6 py-2 rounded-xl font-semibold hover:bg-indigo-600 transition">Explore</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
