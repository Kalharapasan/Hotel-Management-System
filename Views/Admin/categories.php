<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Categories | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Dashboard</a>
            <a href="<?php echo BASE_URL; ?>/admin/categories" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Room Categories</a>
            <a href="<?php echo BASE_URL; ?>/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Hotels</a>
            <a href="<?php echo BASE_URL; ?>/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Manage Room Categories</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm h-fit">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Add / Edit Category</h2>
                <form action="<?php echo BASE_URL; ?>/admin/save-category" method="POST" class="space-y-4">
                    <input type="hidden" id="category_id" name="category_id">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Category Name</label>
                        <input type="text" id="category_name" name="category_name" required placeholder="e.g. Deluxe Suite" 
                               class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Brief description..." 
                                  class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition">Save Category</button>
                    <button type="reset" onclick="resetForm()" class="w-full bg-slate-100 text-slate-600 py-2 rounded-xl font-bold hover:bg-slate-200 transition">Reset</button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-slate-700">Category Name</th>
                            <th class="px-6 py-4 text-sm font-bold text-slate-700">Description</th>
                            <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php while($cat = $categories->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-900 font-semibold"><?php echo $cat['category_name']; ?></td>
                            <td class="px-6 py-4 text-slate-500 text-sm"><?php echo $cat['description']; ?></td>
                            <td class="px-6 py-4 text-right">
                                <button onclick='editCategory(<?php echo json_encode($cat); ?>)' class="text-indigo-600 hover:text-indigo-800 font-bold mr-4">Edit</button>
                                <a href="<?php echo BASE_URL; ?>/admin/delete-category?id=<?php echo $cat['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 font-bold">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function editCategory(cat) {
            document.getElementById('category_id').value = cat.id;
            document.getElementById('category_name').value = cat.category_name;
            document.getElementById('description').value = cat.description;
        }
        function resetForm() {
            document.getElementById('category_id').value = '';
        }
    </script>
</body>
</html>
