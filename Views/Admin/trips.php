<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';
$conn = getDb();

$message = "";

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM trips WHERE id = $id");
    $message = "Trip deleted successfully.";
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_trip'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $duration = $conn->real_escape_string($_POST['duration']);
    $price = $_POST['price'];
    $image_url = $conn->real_escape_string($_POST['image_url']);

    if (isset($_POST['trip_id']) && !empty($_POST['trip_id'])) {
        $id = $_POST['trip_id'];
        $sql = "UPDATE trips SET title='$title', description='$description', duration='$duration', price='$price', image_url='$image_url' WHERE id=$id";
    } else {
        $sql = "INSERT INTO trips (title, description, duration, price, image_url) VALUES ('$title', '$description', '$duration', '$price', '$image_url')";
    }

    if ($conn->query($sql)) {
        $message = "Trip saved successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

$trips = $conn->query("SELECT * FROM trips ORDER BY created_at DESC");

$edit_trip = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_trip = $conn->query("SELECT * FROM trips WHERE id = $id")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Trips | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="manage_hotels.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Hotels</a>
            <a href="manage_flights.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Flights</a>
            <a href="manage_trips.php" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Trips</a>
            <a href="view_bookings.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Bookings</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Manage Trip Plans</h1>
            <?php if($message): ?>
                <div class="bg-green-50 text-green-600 px-4 py-2 rounded-xl border border-green-100 text-sm"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm mb-8">
            <h2 class="text-xl font-bold text-slate-900 mb-6"><?php echo $edit_trip ? 'Edit' : 'Add New'; ?> Trip</h2>
            <form action="manage_trips.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="trip_id" value="<?php echo $edit_trip['id'] ?? ''; ?>">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Trip Title</label>
                    <input type="text" name="title" value="<?php echo $edit_trip['title'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Duration (e.g. 7 Days)</label>
                    <input type="text" name="duration" value="<?php echo $edit_trip['duration'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500 h-24"><?php echo $edit_trip['description'] ?? ''; ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Total Price ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $edit_trip['price'] ?? ''; ?>" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Image URL</label>
                    <input type="text" name="image_url" value="<?php echo $edit_trip['image_url'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2 flex gap-4">
                    <button type="submit" name="save_trip" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Save Trip</button>
                    <?php if($edit_trip): ?>
                        <a href="manage_trips.php" class="bg-slate-200 text-slate-700 px-8 py-3 rounded-xl font-bold hover:bg-slate-300 transition">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Trip Title</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Duration</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Price</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($trip = $trips->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="<?php echo $trip['image_url']; ?>" class="w-10 h-10 rounded-lg object-cover" alt="">
                                <span class="font-semibold text-slate-900"><?php echo $trip['title']; ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500"><?php echo $trip['duration']; ?></td>
                        <td class="px-6 py-4 font-bold text-indigo-600">$<?php echo $trip['price']; ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="manage_trips.php?edit=<?php echo $trip['id']; ?>" class="text-indigo-600 hover:text-indigo-800 font-bold mr-4">Edit</a>
                            <a href="manage_trips.php?delete=<?php echo $trip['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 font-bold">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
