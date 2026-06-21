<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotels | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="<?php echo BASE_URL; ?>/admin/categories" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Room Categories</a>
            <a href="<?php echo BASE_URL; ?>/admin/hotels" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Hotels</a>
            <a href="<?php echo BASE_URL; ?>/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Hotel Management</h1>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-6"><?php echo $edit_hotel ? 'Edit' : 'Add New'; ?> Hotel</h2>
            <form action="<?php echo BASE_URL; ?>/admin/save-hotel" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="hotel_id" value="<?php echo $edit_hotel['id'] ?? ''; ?>">
                <input type="hidden" id="hotel_existing_image" name="existing_image" value="<?php echo $edit_hotel['image_url'] ?? ''; ?>">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hotel Name</label>
                    <input type="text" name="name" value="<?php echo $edit_hotel['name'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" value="<?php echo $edit_hotel['location'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 h-24"><?php echo $edit_hotel['description'] ?? ''; ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Amenities (comma separated)</label>
                    <input type="text" name="amenities" value="<?php echo $edit_hotel['amenities'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Price per Night ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $edit_hotel['price_per_night'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hotel Image</label>
                    <div class="flex items-center gap-3">
                        <input type="file" name="image" onchange="previewLocalFile(this,'hotel_image_preview')" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                        <button type="button" onclick="openAssetPicker('hotel_existing_image','hotel_image_preview')" class="whitespace-nowrap px-4 py-2 rounded-xl border border-indigo-200 text-indigo-600 font-semibold text-sm hover:bg-indigo-50 transition">Browse Assets</button>
                    </div>
                    <img id="hotel_image_preview" src="<?php echo !empty($edit_hotel['image_url']) ? asset_url($edit_hotel['image_url']) : ''; ?>" class="mt-2 h-16 rounded-lg object-cover border border-slate-100 <?php echo empty($edit_hotel['image_url']) ? 'hidden' : ''; ?>" alt="">
                    <?php if(!empty($edit_hotel['image_url'])): ?>
                        <div class="mt-2 text-xs text-slate-400 italic">Current: <?php echo basename($edit_hotel['image_url']); ?></div>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Booking URL</label>
                    <input type="text" name="booking_url" value="<?php echo $edit_hotel['booking_url'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2 flex gap-4">
                    <button type="submit" name="save_hotel" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition">Save Hotel</button>
                    <?php if($edit_hotel): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/hotels" class="bg-slate-200 text-slate-700 px-8 py-3 rounded-xl font-bold hover:bg-slate-300 transition">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Hotel Name</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Location</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Price</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($hotel = $hotels->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?php echo $hotel['name']; ?></td>
                        <td class="px-6 py-4 text-slate-500"><?php echo $hotel['location']; ?></td>
                        <td class="px-6 py-4 font-bold text-indigo-600">$<?php echo $hotel['price_per_night']; ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?php echo BASE_URL; ?>/admin/hotels?edit=<?php echo $hotel['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold mr-4">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/admin/delete-hotel?id=<?php echo $hotel['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 font-bold">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include __DIR__ . '/partials/asset_picker.php'; ?>
</body>
</html>
