<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Bill | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-8">
    <div class="max-w-2xl w-full bg-white rounded-[40px] shadow-2xl p-12 border border-slate-100">
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-4xl font-bold text-indigo-600">LuxeStay</h1>
                <p class="text-slate-500 mt-1">Premium Hospitality Services</p>
            </div>
            <div class="text-right">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">Invoice Date</div>
                <div class="text-sm font-bold text-slate-900"><?php echo date('M d, Y'); ?></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-12">
            <div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Bill To</div>
                <div class="text-lg font-bold text-slate-900"><?php echo $booking['fullname']; ?></div>
                <div class="text-sm text-slate-500"><?php echo $booking['email']; ?></div>
            </div>
            <div class="text-right">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Booking ID</div>
                <div class="text-lg font-bold text-slate-900">#BK-<?php echo $booking['id']; ?></div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-8 mb-8">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <th class="pb-4">Description</th>
                        <th class="pb-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr>
                        <td class="py-4">
                            <div class="font-bold text-slate-900">Room Stay</div>
                            <div class="text-xs text-slate-500">Service: <?php echo ucfirst($booking['item_type']); ?></div>
                        </td>
                        <td class="py-4 text-right font-bold text-slate-900">$<?php echo number_format($room_price, 2); ?></td>
                    </tr>
                    <tr>
                        <td class="py-4">
                            <div class="font-bold text-slate-900">Restaurant & Bar</div>
                            <div class="text-xs text-slate-500">Food, Drinks & Services</div>
                        </td>
                        <td class="py-4 text-right font-bold text-slate-900">$<?php echo number_format($restaurant_total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-indigo-50 rounded-3xl p-8 flex justify-between items-center mb-12">
            <div>
                <div class="text-indigo-600 font-bold">Total Amount Payable</div>
                <div class="text-xs text-indigo-400 italic">Including all taxes & services</div>
            </div>
            <div class="text-4xl font-black text-indigo-600">$<?php echo number_format($total, 2); ?></div>
        </div>

        <form action="<?php echo BASE_URL; ?>/admin/checkout" method="POST" class="flex gap-4">
            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $booking['user_id']; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
            <button type="submit" class="flex-1 bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-indigo-600 transition shadow-lg">Confirm Payment & Checkout</button>
            <button type="button" onclick="window.print()" class="px-6 bg-white border border-slate-200 text-slate-600 rounded-2xl hover:bg-slate-50 transition">Print</button>
        </form>
    </div>
</body>
</html>
