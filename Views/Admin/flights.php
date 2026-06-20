<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Flights | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="<?php echo BASE_URL; ?>/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="<?php echo BASE_URL; ?>/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Hotels</a>
            <a href="<?php echo BASE_URL; ?>/admin/flights" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Flights</a>
            <a href="<?php echo BASE_URL; ?>/admin/trips" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Trips</a>
            <a href="<?php echo BASE_URL; ?>/admin/bookings" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Bookings</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Manage Flights</h1>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-6"><?php echo $edit_flight ? 'Edit' : 'Add New'; ?> Flight</h2>
            <form action="<?php echo BASE_URL; ?>/admin/save-flight" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="flight_id" value="<?php echo $edit_flight['id'] ?? ''; ?>">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Airline</label>
                    <input type="text" name="airline" value="<?php echo $edit_flight['airline'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Price ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $edit_flight['price'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Departure City</label>
                    <input type="text" name="departure" value="<?php echo $edit_flight['departure'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Arrival City</label>
                    <input type="text" name="arrival" value="<?php echo $edit_flight['arrival'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Departure Time</label>
                    <input type="datetime-local" name="departure_time" value="<?php echo isset($edit_flight['departure_time']) ? date('Y-m-d\TH:i', strtotime($edit_flight['departure_time'])) : ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2 flex gap-4">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Save Flight</button>
                    <?php if($edit_flight): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/flights" class="bg-slate-200 text-slate-700 px-8 py-3 rounded-xl font-bold hover:bg-slate-300 transition">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Airline</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Route</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Price</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($flight = $flights->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-900"><?php echo $flight['airline']; ?></td>
                        <td class="px-6 py-4 text-slate-500"><?php echo $flight['departure']; ?> &rarr; <?php echo $flight['arrival']; ?></td>
                        <td class="px-6 py-4 font-bold text-indigo-600">$<?php echo $flight['price']; ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?php echo BASE_URL; ?>/admin/flights?edit=<?php echo $flight['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold mr-4">Edit</a>
                            <a href="<?php echo BASE_URL; ?>/admin/delete-flight?id=<?php echo $flight['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 font-bold">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
