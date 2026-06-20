<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Bookings | LuxeStay Admin</title>
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
            <a href="<?php echo BASE_URL; ?>/admin/flights" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Flights</a>
            <a href="<?php echo BASE_URL; ?>/admin/trips" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Trips</a>
            <a href="<?php echo BASE_URL; ?>/admin/bookings" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Bookings</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">User Bookings</h1>
            <p class="text-slate-500">Monitor and manage all customer reservations.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">User Details</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Service Type</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Date</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Status</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($booking = $bookings->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900"><?php echo $booking['customer_name']; ?></div>
                            <div class="text-xs text-slate-500"><?php echo $booking['customer_email']; ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                                <?php echo $booking['item_type'] == 'hotel' ? 'bg-blue-100 text-blue-600' : ($booking['item_type'] == 'flight' ? 'bg-purple-100 text-purple-600' : 'bg-orange-100 text-orange-600'); ?>">
                                <?php echo $booking['item_type']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-sm"><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo $booking['status'] == 'confirmed' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600'; ?>">
                                <?php echo $booking['status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if($booking['status'] == 'pending'): ?>
                                <a href="<?php echo BASE_URL; ?>/admin/bill?booking_id=<?php echo $booking['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold underline">Checkout & Bill</a>
                            <?php else: ?>
                                <span class="text-slate-400 italic">Paid</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
