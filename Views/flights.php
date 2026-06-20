<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Flights | LuxeStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <a href="<?php echo BASE_URL; ?>/" class="text-gray-700 hover:text-indigo-600 transition">Hotels</a>
                    <a href="<?php echo BASE_URL; ?>/trips" class="text-gray-700 hover:text-indigo-600 transition">Trips</a>
                    <a href="<?php echo BASE_URL; ?>/flights" class="text-indigo-600 font-bold transition">Flights</a>
                    <a href="<?php echo BASE_URL; ?>/login" class="bg-indigo-600 text-white px-5 py-2 rounded-full hover:bg-indigo-700 transition">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-16">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-slate-900 mb-4">Book Your Next Flight</h1>
            <p class="text-slate-500">Compare prices and find the best routes for your journey.</p>
        </div>

        <div class="space-y-6">
            <?php while($flight = $flights->fetch_assoc()): ?>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6 hover:shadow-md transition">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center text-indigo-600">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M21,16.5C21,16.88 20.79,17.21 20.47,17.38L12.57,21.82C12.41,21.94 12.21,22 12,22C11.79,22 11.59,21.94 11.43,21.82L3.53,17.38C3.21,17.21 3,16.88 3,16.5V7.5C3,7.12 3.21,6.79 3.53,6.62L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.79,6.79 21,7.12 21,7.5V16.5Z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-slate-900"><?php echo $flight['airline']; ?></h3>
                        <p class="text-slate-500 text-sm">Economy Class</p>
                    </div>
                </div>
                
                <div class="flex-1 flex items-center justify-center gap-8">
                    <div class="text-right">
                        <div class="text-2xl font-bold text-slate-900"><?php echo $flight['departure']; ?></div>
                        <div class="text-xs text-slate-500">Departure</div>
                    </div>
                    <div class="flex flex-col items-center gap-1 w-32">
                        <div class="h-0.5 w-full bg-slate-200 relative">
                            <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 text-indigo-500 bg-white px-1">
                                <svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16l-4-4 4-4v8zM3 16l4-4-4-4v8z"></path></svg>
                            </div>
                        </div>
                        <span class="text-xs text-slate-400">Non-stop</span>
                    </div>
                    <div class="text-left">
                        <div class="text-2xl font-bold text-slate-900"><?php echo $flight['arrival']; ?></div>
                        <div class="text-xs text-slate-500">Arrival</div>
                    </div>
                </div>

                <div class="text-center md:text-right">
                    <div class="text-3xl font-bold text-indigo-600 mb-1">$<?php echo $flight['price']; ?></div>
                    <button class="bg-slate-900 text-white px-8 py-3 rounded-2xl font-bold hover:bg-indigo-600 transition">Book Flight</button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
