<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="<?php echo BASE_URL; ?>/admin/restaurant" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Restaurant</a>
            <a href="<?php echo BASE_URL; ?>/admin/employees" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Employees</a>
            <a href="<?php echo BASE_URL; ?>/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Restaurant Management</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Menu Management -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Manage Menu Items</h2>
                <form action="<?php echo BASE_URL; ?>/admin/save-menu-item" method="POST" enctype="multipart/form-data" class="space-y-4 mb-8">
                    <input type="hidden" name="menu_id" value="<?php echo $edit_menu['id'] ?? ''; ?>">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="name" value="<?php echo $edit_menu['name'] ?? ''; ?>" placeholder="Item Name" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                        <input type="text" name="category" value="<?php echo $edit_menu['category'] ?? ''; ?>" placeholder="Category" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" step="0.01" name="price" value="<?php echo $edit_menu['price'] ?? ''; ?>" placeholder="Price ($)" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                        <div class="flex items-center gap-2">
                            <input type="text" id="menu_image_url" name="image_url" value="<?php echo $edit_menu['image_url'] ?? ''; ?>" placeholder="Image URL" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                            <button type="button" onclick="openAssetPicker('menu_image_url','menu_image_preview')" class="whitespace-nowrap px-3 py-2 rounded-xl border border-indigo-200 text-indigo-600 font-semibold text-xs hover:bg-indigo-50 transition">Browse</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Or upload from your computer</label>
                        <input type="file" name="image" accept="image/*" onchange="previewLocalFile(this,'menu_image_preview')" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                    </div>
                    <img id="menu_image_preview" src="<?php echo !empty($edit_menu['image_url']) ? asset_url($edit_menu['image_url']) : ''; ?>" class="h-14 rounded-lg object-cover border border-slate-100 <?php echo empty($edit_menu['image_url']) ? 'hidden' : ''; ?>" alt="">
                    <textarea name="description" placeholder="Description" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none h-20"><?php echo $edit_menu['description'] ?? ''; ?></textarea>
                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold">Save Item</button>
                </form>

                <div class="max-h-[400px] overflow-y-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-sm font-bold">Item</th>
                                <th class="px-4 py-2 text-sm font-bold">Price</th>
                                <th class="px-4 py-2 text-sm font-bold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php while($m = $menu->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-semibold"><?php echo $m['name']; ?></div>
                                    <div class="text-xs text-slate-500"><?php echo $m['category']; ?></div>
                                </td>
                                <td class="px-4 py-3 font-bold text-indigo-600">$<?php echo $m['price']; ?></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="<?php echo BASE_URL; ?>/admin/restaurant?edit_menu=<?php echo $m['id']; ?>" class="text-indigo-600 text-sm font-bold">Edit</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Management -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Active Orders</h2>
                <div class="space-y-4">
                    <?php while($o = $orders->fetch_assoc()): ?>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                        <div>
                            <div class="font-bold text-slate-900"><?php echo $o['fullname']; ?></div>
                            <div class="text-sm text-indigo-600 font-bold">$<?php echo $o['total_amount']; ?></div>
                            <div class="text-xs text-slate-400"><?php echo $o['order_date']; ?></div>
                        </div>
                        <form action="<?php echo BASE_URL; ?>/admin/update-order-status" method="POST" class="flex gap-2">
                            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                            <select name="status" class="text-xs border rounded-lg px-2 py-1 outline-none">
                                <option value="pending" <?php echo $o['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="preparing" <?php echo $o['status'] == 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                                <option value="delivered" <?php echo $o['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            </select>
                            <button type="submit" class="bg-slate-900 text-white px-3 py-1 rounded-lg text-xs font-bold">Update</button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                    <?php if($orders->num_rows == 0): ?>
                        <p class="text-center text-slate-400 py-8">No active orders.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/partials/asset_picker.php'; ?>
</body>
</html>
