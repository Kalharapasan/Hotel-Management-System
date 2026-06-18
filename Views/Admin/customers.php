<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="/admin/customers" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Customers</a>
            <a href="/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Hotels</a>
            <a href="/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Customer Management</h1>

        <?php if($edit_customer): ?>
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Edit Customer</h2>
            <form action="/admin/save-customer" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="user_id" value="<?php echo $edit_customer['id']; ?>">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="fullname" value="<?php echo $edit_customer['fullname']; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="<?php echo $edit_customer['email']; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div class="md:col-span-2 flex gap-4">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold">Update Customer</button>
                    <a href="/admin/customers" class="bg-slate-200 text-slate-700 px-8 py-3 rounded-xl font-bold">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Name</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Email</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($user = $customers->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?php echo $user['fullname']; ?></td>
                        <td class="px-6 py-4 text-slate-500"><?php echo $user['email']; ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="/admin/customers?edit=<?php echo $user['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold mr-4">Edit</a>
                            <a href="/admin/delete-customer?id=<?php echo $user['id']; ?>" onclick="return confirm('Delete this user?')" class="text-red-500 hover:text-red-700 font-bold">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
