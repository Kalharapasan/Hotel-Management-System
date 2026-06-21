<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | LuxeStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?php echo BASE_URL; ?>/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50">
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-100 py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <a href="<?php echo BASE_URL; ?>/" class="text-2xl font-bold text-indigo-600">LuxeStay</a>
            <div class="flex gap-8 items-center">
                <a href="<?php echo BASE_URL; ?>/" class="text-slate-600 font-medium">Home</a>
                <a href="<?php echo BASE_URL; ?>/rooms" class="text-slate-600 font-medium">Rooms</a>
                <a href="<?php echo BASE_URL; ?>/about" class="text-slate-600 font-medium">About</a>
                <a href="<?php echo BASE_URL; ?>/contact" class="text-indigo-600 font-bold">Contact</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-24">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-bold text-slate-900 mb-4">Get in Touch</h1>
            <p class="text-slate-500 text-lg">We're here to assist you 24/7. Let us know how we can help.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 bg-white p-12 rounded-[40px] shadow-sm border border-slate-100">
                <form action="<?php echo BASE_URL; ?>/contact/send" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Your Name</label>
                            <input type="text" name="name" required class="input-field" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" required class="input-field" placeholder="john@example.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Subject</label>
                        <input type="text" name="subject" required class="input-field" placeholder="How can we help?">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Message</label>
                        <textarea name="message" rows="6" required class="input-field" placeholder="Your message here..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full md:w-auto">Send Message</button>
                </form>
            </div>

            <div class="space-y-8">
                <div class="bg-indigo-600 p-8 rounded-[40px] text-white">
                    <h3 class="text-2xl font-bold mb-6">Contact Info</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="text-2xl">📍</div>
                            <div>
                                <div class="font-bold">Headquarters</div>
                                <div class="text-indigo-100 text-sm">123 Luxury Ave, Paris, France</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="text-2xl">📞</div>
                            <div>
                                <div class="font-bold">Phone</div>
                                <div class="text-indigo-100 text-sm">+1 (234) 567-890</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="text-2xl">📧</div>
                            <div>
                                <div class="font-bold">Email</div>
                                <div class="text-indigo-100 text-sm">support@luxestay.com</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="<?php echo BASE_URL; ?>/js/main.js"></script>
</body>
</html>
