<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden lg:flex">
        <div class="p-8"><span class="text-2xl font-bold text-indigo-400">LuxeStay</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="/admin" class="flex items-center px-4 py-3 bg-indigo-600 rounded-2xl">Dashboard</a>
            <a href="/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-2xl transition">Hotels</a>
            <a href="/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-2xl transition">Rooms</a>
            <a href="/admin/categories" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-2xl transition">Categories</a>
            <a href="/admin/customers" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-2xl transition">Customers</a>
            <a href="/admin/employees" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-2xl transition">Employees</a>
            <a href="/admin/bookings" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-2xl transition">Bookings</a>
            <a href="/admin/billing" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-2xl transition">Billing</a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-2xl transition">Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 lg:p-12 overflow-y-auto">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl font-bold text-slate-900">Hotel Overview</h1>
                <p class="text-slate-500 mt-1">Welcome back, Admin. Here's what's happening today.</p>
            </div>
            <div class="flex gap-4">
                <a href="/" target="_blank" class="px-6 py-3 bg-white border border-slate-200 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition">View Website</a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="text-indigo-600 text-3xl mb-4">💰</div>
                <div class="text-2xl font-bold text-slate-900">$<?php echo number_format($totalRevenue, 2); ?></div>
                <div class="text-sm text-slate-400 font-medium uppercase tracking-widest">Total Revenue</div>
            </div>
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="text-indigo-600 text-3xl mb-4">🏨</div>
                <div class="text-2xl font-bold text-slate-900"><?php echo $hotelCount; ?></div>
                <div class="text-sm text-slate-400 font-medium uppercase tracking-widest">Hotels Managed</div>
            </div>
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="text-indigo-600 text-3xl mb-4">🛏️</div>
                <div class="text-2xl font-bold text-slate-900"><?php echo $roomStats['booked']; ?> / <?php echo $roomStats['total']; ?></div>
                <div class="text-sm text-slate-400 font-medium uppercase tracking-widest">Rooms Occupied</div>
            </div>
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="text-indigo-600 text-3xl mb-4">👥</div>
                <div class="text-2xl font-bold text-slate-900"><?php echo $customerCount; ?></div>
                <div class="text-sm text-slate-400 font-medium uppercase tracking-widest">Total Customers</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Recent Bookings</h3>
                <div class="space-y-4">
                    <?php while($b = $recentBookings->fetch_assoc()): ?>
                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div>
                            <div class="font-bold text-slate-900"><?php echo $b['fullname']; ?></div>
                            <div class="text-xs text-slate-400"><?php echo ucfirst($b['item_type']); ?> Booking</div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-indigo-600 uppercase"><?php echo $b['status']; ?></span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Recent Restaurant Activity -->
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Recent Restaurant Orders</h3>
                <div class="space-y-4">
                    <?php while($o = $recentOrders->fetch_assoc()): ?>
                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div>
                            <div class="font-bold text-slate-900"><?php echo $o['fullname']; ?></div>
                            <div class="text-xs text-slate-400">$<?php echo $o['total_amount']; ?> Order</div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-orange-600 uppercase"><?php echo $o['status']; ?></span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
