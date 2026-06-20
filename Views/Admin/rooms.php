<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms | LuxeStay Admin</title>
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
            <a href="<?php echo BASE_URL; ?>/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Hotels</a>
            <a href="<?php echo BASE_URL; ?>/admin/rooms" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Room Management</h1>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-6"><?php echo $edit_room ? 'Edit' : 'Add New'; ?> Room</h2>
            <form action="<?php echo BASE_URL; ?>/admin/save-room" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <input type="hidden" name="room_id" value="<?php echo $edit_room['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_room['image_url'] ?? ''; ?>">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Select Hotel</label>
                    <select name="hotel_id" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                        <?php while($h = $hotels->fetch_assoc()): ?>
                            <option value="<?php echo $h['id']; ?>" <?php echo (isset($edit_room['hotel_id']) && $edit_room['hotel_id'] == $h['id']) ? 'selected' : ''; ?>><?php echo $h['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Room Category</label>
                    <select name="category_id" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">No Category</option>
                        <?php 
                        $categories->data_seek(0);
                        while($c = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo (isset($edit_room['category_id']) && $edit_room['category_id'] == $c['id']) ? 'selected' : ''; ?>><?php echo $c['category_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Room Type / Name</label>
                    <input type="text" name="room_type" value="<?php echo $edit_room['room_type'] ?? ''; ?>" required placeholder="e.g. Deluxe Suite" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Price per Night ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $edit_room['price_per_night'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="available" <?php echo (isset($edit_room['status']) && $edit_room['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                        <option value="booked" <?php echo (isset($edit_room['status']) && $edit_room['status'] == 'booked') ? 'selected' : ''; ?>>Booked</option>
                        <option value="maintenance" <?php echo (isset($edit_room['status']) && $edit_room['status'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Room Image</label>
                    <input type="file" name="image" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                    <?php if(!empty($edit_room['image_url'])): ?>
                        <div class="mt-2 text-xs text-slate-400 italic">Current: <?php echo basename($edit_room['image_url']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Amenities</label>
                    <input type="text" name="amenities" value="<?php echo $edit_room['amenities'] ?? ''; ?>" placeholder="WiFi, AC, TV" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2 lg:col-span-3 flex gap-4">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition">Save Room</button>
                    <?php if($edit_room): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/rooms" class="bg-slate-200 text-slate-700 px-8 py-3 rounded-xl font-bold hover:bg-slate-300 transition">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Hotel</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Category</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Room Type</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Price</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($room = $rooms->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold"><?php echo $room['hotel_name']; ?></td>
                        <td class="px-6 py-4 text-slate-500"><?php echo $room['category_name'] ?? 'N/A'; ?></td>
                        <td class="px-6 py-4"><?php echo $room['room_type']; ?></td>
                        <td class="px-6 py-4 font-bold text-indigo-600">$<?php echo $room['price_per_night']; ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?php echo BASE_URL; ?>/admin/rooms?edit=<?php echo $room['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold mr-4">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/admin/delete-room?id=<?php echo $room['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 font-bold">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
