<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | LuxeStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-[32px] p-8 md:p-12 border border-slate-100 shadow-xl shadow-indigo-100/50">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-indigo-600 mb-2">LuxeStay</h1>
            <p class="text-slate-500 font-medium">Join our premium community</p>
        </div>

        <form action="<?php echo BASE_URL; ?>/register" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                <input type="text" name="fullname" required placeholder="John Doe" 
                       class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com" 
                       class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full px-6 py-4 rounded-2xl bg-slate-50 border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Create Account
            </button>
        </form>

        <p class="text-center mt-8 text-slate-600">
            Already have an account? <a href="<?php echo BASE_URL; ?>/login" class="text-indigo-600 font-bold hover:underline">Login here</a>
        </p>
    </div>
</body>
</html>
