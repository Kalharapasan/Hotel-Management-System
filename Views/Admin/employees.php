<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="/admin/employees" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Employees</a>
            <a href="/admin/customers" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Customers</a>
            <a href="/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Hotels</a>
            <a href="/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Employee Management</h1>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-6"><?php echo $edit_emp ? 'Edit' : 'Add New'; ?> Employee</h2>
            <form action="/admin/save-employee" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <input type="hidden" name="employee_id" value="<?php echo $edit_emp['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_emp['image_url'] ?? ''; ?>">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="fullname" value="<?php echo $edit_emp['fullname'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                    <input type="text" name="role" value="<?php echo $edit_emp['role'] ?? ''; ?>" required placeholder="Chef, Receptionist..." class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="<?php echo $edit_emp['email'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="<?php echo $edit_emp['phone'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Monthly Salary ($)</label>
                    <input type="number" step="0.01" name="salary" value="<?php echo $edit_emp['salary'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                        <option value="active" <?php echo (isset($edit_emp['status']) && $edit_emp['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo (isset($edit_emp['status']) && $edit_emp['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Profile Photo</label>
                    <input type="file" name="image" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                </div>
                <div class="md:col-span-2 lg:col-span-3 flex gap-4">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold">Save Employee</button>
                    <?php if($edit_emp): ?>
                        <a href="/admin/employees" class="bg-slate-200 text-slate-700 px-8 py-3 rounded-xl font-bold">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Name</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Role</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Salary</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($emp = $employees->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?php echo $emp['fullname']; ?></td>
                        <td class="px-6 py-4 text-slate-500"><?php echo $emp['role']; ?></td>
                        <td class="px-6 py-4 font-bold text-indigo-600">$<?php echo $emp['salary']; ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="/admin/employees?edit=<?php echo $emp['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold mr-4">Edit</a>
                            <a href="/admin/delete-employee?id=<?php echo $emp['id']; ?>" onclick="return confirm('Remove employee?')" class="text-red-500 hover:text-red-700 font-bold">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
