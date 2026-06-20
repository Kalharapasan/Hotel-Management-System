<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeDine | Fine Dining Restaurant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>/" class="text-2xl font-bold text-indigo-600">LuxeStay</a>
            <div class="flex space-x-8 items-center">
                <a href="<?php echo BASE_URL; ?>/" class="text-slate-600 hover:text-indigo-600 transition">Home</a>
                <a href="<?php echo BASE_URL; ?>/restaurant" class="text-indigo-600 font-bold">Restaurant</a>
                <?php if(isset($_SESSION['user_name'])): ?>
                    <span class="text-slate-900 font-bold">Hi, <?php echo $_SESSION['user_name']; ?></span>
                    <a href="<?php echo BASE_URL; ?>/logout" class="text-red-500 text-sm">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/login" class="text-slate-600 font-bold hover:text-indigo-600">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="h-[400px] relative flex items-center justify-center">
        <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b" class="absolute inset-0 w-full h-full object-cover" alt="Restaurant">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative text-center text-white">
            <h1 class="text-5xl font-bold mb-4">LuxeDine Restaurant</h1>
            <p class="text-xl opacity-80">Gourmet experiences delivered to your suite.</p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-20">
        <?php if(!empty($message)): ?>
            <div class="bg-green-50 text-green-600 p-6 rounded-3xl mb-12 border border-green-100 font-bold text-center"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php while($m = $menu->fetch_assoc()): ?>
            <div class="bg-white rounded-[40px] overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl transition duration-500 group">
                <div class="h-64 overflow-hidden relative">
                    <img src="<?php echo asset_url($m['image_url']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="">
                    <div class="absolute top-6 right-6 bg-white/90 backdrop-blur px-4 py-1 rounded-full font-bold text-indigo-600 shadow-sm">$<?php echo $m['price']; ?></div>
                </div>
                <div class="p-8">
                    <span class="text-xs font-bold uppercase text-indigo-500 tracking-widest mb-2 block"><?php echo $m['category']; ?></span>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2"><?php echo $m['name']; ?></h3>
                    <p class="text-slate-500 text-sm mb-6 line-clamp-2"><?php echo $m['description']; ?></p>
                    <a href="<?php echo BASE_URL; ?>/restaurant?order=<?php echo $m['id']; ?>" class="block text-center bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-indigo-600 transition">Order Now</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 LuxeStay Hotel Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
