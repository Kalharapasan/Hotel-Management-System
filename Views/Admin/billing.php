<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing & Payments | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="/admin" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="/admin/billing" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Billing</a>
            <a href="/admin/hotels" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Hotels</a>
            <a href="/admin/rooms" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-8">Billing & Payments</h1>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Customer</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Service</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Amount</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Status</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($pay = $payments->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold"><?php echo $pay['fullname']; ?></td>
                        <td class="px-6 py-4 text-slate-500 capitalize"><?php echo $pay['item_type']; ?></td>
                        <td class="px-6 py-4 font-bold text-indigo-600">$<?php echo $pay['amount']; ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?php echo $pay['status'] == 'completed' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600'; ?>">
                                <?php echo $pay['status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-sm"><?php echo date('M d, Y', strtotime($pay['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
