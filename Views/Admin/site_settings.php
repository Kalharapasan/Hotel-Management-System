<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Site Content | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Dashboard</a>
            <a href="<?php echo BASE_URL; ?>/admin/site-settings" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Site Settings</a>
            <a href="<?php echo BASE_URL; ?>/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Hotels</a>
            <a href="<?php echo BASE_URL; ?>/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Rooms</a>
            <a href="<?php echo BASE_URL; ?>/admin/flights" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Flights</a>
            <a href="<?php echo BASE_URL; ?>/admin/trips" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Trips</a>
            <a href="<?php echo BASE_URL; ?>/admin/billing" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Billing</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Manage Site Content</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <?php foreach(['home_hero', 'about', 'contact'] as $key): ?>
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-6 uppercase"><?php echo str_replace('_', ' ', $key); ?> Section</h2>
                <form action="<?php echo BASE_URL; ?>/admin/save-site-setting" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="page_key" value="<?php echo $key; ?>">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Title</label>
                        <input type="text" name="title" value="<?php echo $settings[$key]['title'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Content / Description</label>
                        <textarea name="content" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 h-32"><?php echo $settings[$key]['content'] ?? ''; ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Image URL</label>
                        <div class="flex items-center gap-3">
                            <input type="text" id="setting_image_<?php echo $key; ?>" name="image_url" value="<?php echo $settings[$key]['image_url'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                            <button type="button" onclick="openAssetPicker('setting_image_<?php echo $key; ?>','setting_preview_<?php echo $key; ?>')" class="whitespace-nowrap px-4 py-2 rounded-xl border border-indigo-200 text-indigo-600 font-semibold text-sm hover:bg-indigo-50 transition">Browse</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Or upload from your computer</label>
                        <input type="file" name="image" accept="image/*" onchange="previewLocalFile(this,'setting_preview_<?php echo $key; ?>')" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                    </div>
                    <img id="setting_preview_<?php echo $key; ?>" src="<?php echo !empty($settings[$key]['image_url']) ? asset_url($settings[$key]['image_url']) : ''; ?>" class="h-16 rounded-lg object-cover border border-slate-100 <?php echo empty($settings[$key]['image_url']) ? 'hidden' : ''; ?>" alt="">
                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg">Update <?php echo ucfirst($key); ?></button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include __DIR__ . '/partials/asset_picker.php'; ?>
</body>
</html>
