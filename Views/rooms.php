<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms | LuxeStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <nav class="bg-white border-b border-slate-100 py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>/" class="text-2xl font-bold text-indigo-600">LuxeStay</a>
            <div class="flex gap-8 items-center">
                <a href="<?php echo BASE_URL; ?>/" class="text-slate-600 font-medium">Home</a>
                <a href="<?php echo BASE_URL; ?>/restaurant" class="text-slate-600 font-medium">Restaurant</a>
                <a href="<?php echo BASE_URL; ?>/profile" class="bg-indigo-600 text-white px-5 py-2 rounded-full font-bold shadow-lg shadow-indigo-100">My Account</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-bold text-slate-900 mb-2">Luxury Rooms</h1>
        <p class="text-slate-500 mb-12">Choose your perfect stay from our verified hotels.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while($room = $rooms->fetch_assoc()): ?>
            <div class="bg-white rounded-[40px] overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl transition duration-500 group">
                <div class="relative h-72">
                    <img src="<?php echo asset_url($room['image_url']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="">
                    <div class="absolute top-6 right-6 bg-white/90 backdrop-blur px-4 py-2 rounded-2xl text-indigo-600 font-bold shadow-sm">
                        $<?php echo number_format($room['price_per_night']); ?> <span class="text-slate-400 font-normal text-xs">/ night</span>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest"><?php echo $room['category_name']; ?></span>
                            <h3 class="text-2xl font-bold text-slate-900 mt-1"><?php echo $room['room_type']; ?></h3>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm mb-6 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                        <?php echo $room['hotel_name']; ?>
                    </p>
                    <div class="flex gap-2 mb-4">
                        <?php 
                        $amenities = explode(',', $room['amenities']);
                        foreach($amenities as $a): if(empty(trim($a))) continue; ?>
                        <span class="bg-slate-50 text-slate-500 text-[10px] font-bold px-3 py-1 rounded-full border border-slate-100"><?php echo trim($a); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/rooms/book" method="GET" class="space-y-4">
                        <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Check In</label>
                                <input type="date" name="check_in" required class="w-full text-xs p-2 rounded-lg border border-slate-100 outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Check Out</label>
                                <input type="date" name="check_out" required class="w-full text-xs p-2 rounded-lg border border-slate-100 outline-none">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-indigo-600 transition shadow-lg shadow-slate-200">Book This Room</button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
