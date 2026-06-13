<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | LuxeStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <nav class="bg-white border-b border-slate-100 py-4">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-indigo-600">LuxeStay</a>
            <div class="flex items-center gap-6">
                <a href="/" class="text-slate-600 hover:text-indigo-600 font-medium">Home</a>
                <a href="/logout" class="text-red-500 font-bold">Logout</a>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-16">
        <div class="bg-white rounded-[32px] p-8 md:p-12 border border-slate-100 shadow-xl shadow-indigo-100/50">
            <h1 class="text-3xl font-bold text-slate-900 mb-8">Account Profile</h1>
            
            <?php if(isset($_GET['success'])): ?>
                <div class="bg-green-50 text-green-600 p-4 rounded-2xl border border-green-100 mb-8">
                    Profile updated successfully!
                </div>
            <?php endif; ?>

            <form action="/profile/update" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required 
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required 
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        Update Profile
                    </button>
                </div>
            </form>
            </div>

            <!-- Booking History -->
            <div class="lg:col-span-2 space-y-8 mt-12">
                <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Active Bookings</h2>
                    <?php if($bookings->num_rows > 0): ?>
                    <div class="space-y-4">
                        <?php while($b = $bookings->fetch_assoc()): ?>
                        <div class="flex justify-between items-center p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div>
                                <div class="font-bold text-slate-900"><?php echo $b['room_type']; ?></div>
                                <div class="text-sm text-slate-500"><?php echo $b['hotel_name']; ?></div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-1"><?php echo $b['status']; ?></div>
                                <div class="text-sm text-slate-400"><?php echo date('M d, Y', strtotime($b['booking_date'])); ?></div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-slate-400 text-center py-8">No active bookings found.</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Restaurant Orders</h2>
                    <?php if($orders->num_rows > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php while($o = $orders->fetch_assoc()): ?>
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div class="flex justify-between mb-2">
                                <span class="font-bold text-slate-900">$<?php echo number_format($o['total_amount'], 2); ?></span>
                                <span class="text-xs font-bold text-indigo-600 uppercase"><?php echo $o['status']; ?></span>
                            </div>
                            <div class="text-xs text-slate-400"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-slate-400 text-center py-8">No orders yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
