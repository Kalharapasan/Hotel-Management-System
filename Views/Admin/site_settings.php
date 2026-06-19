<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';
$conn = getDb();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_site'])) {
    $page_key = $_POST['page_key'];
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $image_url = $conn->real_escape_string($_POST['image_url']);

    $sql = "UPDATE site_settings SET title='$title', content='$content', image_url='$image_url' WHERE page_key='$page_key'";
    if ($conn->query($sql)) {
        $message = "Settings updated successfully.";
    } else {
        $message = "Error updating settings: " . $conn->error;
    }
}

$settings = [];
$res = $conn->query("SELECT * FROM site_settings");
while ($row = $res->fetch_assoc()) {
    $settings[$row['page_key']] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Site Content | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Dashboard</a>
            <a href="manage_site.php" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Site Settings</a>
            <a href="manage_hotels.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Hotels</a>
            <a href="manage_rooms.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Rooms</a>
            <a href="manage_flights.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Flights</a>
            <a href="manage_trips.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Trips</a>
            <a href="billing.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl">Billing</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Manage Site Content</h1>
            <?php if($message): ?>
                <div class="bg-green-50 text-green-600 px-4 py-2 rounded-xl border border-green-100 text-sm"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <?php foreach(['home_hero', 'about', 'contact'] as $key): ?>
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-6 uppercase"><?php echo str_replace('_', ' ', $key); ?> Section</h2>
                <form action="manage_site.php" method="POST" class="space-y-4">
                    <input type="hidden" name="page_key" value="<?php echo $key; ?>">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Title</label>
                        <input type="text" name="title" value="<?php echo $settings[$key]['title'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Content / Description</label>
                        <textarea name="content" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 h-32"><?php echo $settings[$key]['content'] ?? ''; ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Image URL</label>
                        <input type="text" name="image_url" value="<?php echo $settings[$key]['image_url'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" name="update_site" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-indigo-600 transition shadow-lg">Update <?php echo ucfirst($key); ?></button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
