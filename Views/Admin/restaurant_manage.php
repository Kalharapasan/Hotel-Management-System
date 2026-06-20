<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';
$conn = getDb();

$message = "";

// Handle Menu Item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_menu'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $cat = $conn->real_escape_string($_POST['category']);
    $price = $_POST['price'];
    $desc = $conn->real_escape_string($_POST['description']);
    $url = $conn->real_escape_string($_POST['image_url']);

    if (isset($_POST['menu_id']) && !empty($_POST['menu_id'])) {
        $id = $_POST['menu_id'];
        $sql = "UPDATE menu_items SET name='$name', category='$cat', price='$price', description='$desc', image_url='$url' WHERE id=$id";
    } else {
        $sql = "INSERT INTO menu_items (name, category, price, description, image_url) VALUES ('$name', '$cat', '$price', '$desc', '$url')";
    }
    $conn->query($sql);
    $message = "Menu item saved.";
}

// Handle Order Update
if (isset($_POST['update_order'])) {
    $oid = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE restaurant_orders SET status='$status' WHERE id=$oid");
    $message = "Order status updated.";
}

$menu = $conn->query("SELECT * FROM menu_items ORDER BY category");
$orders = $conn->query("SELECT o.*, u.fullname FROM restaurant_orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC");

$edit_menu = null;
if (isset($_GET['edit_menu'])) {
    $id = $_GET['edit_menu'];
    $edit_menu = $conn->query("SELECT * FROM menu_items WHERE id = $id")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="manage_restaurant.php" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Restaurant</a>
            <a href="manage_employees.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Employees</a>
            <a href="manage_rooms.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Rooms</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Restaurant Management</h1>
            <?php if($message): ?>
                <div class="bg-green-50 text-green-600 px-4 py-2 rounded-xl border border-green-100 text-sm"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Menu Management -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Manage Menu Items</h2>
                <form action="manage_restaurant.php" method="POST" class="space-y-4 mb-8">
                    <input type="hidden" name="menu_id" value="<?php echo $edit_menu['id'] ?? ''; ?>">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="name" value="<?php echo $edit_menu['name'] ?? ''; ?>" placeholder="Item Name" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                        <input type="text" name="category" value="<?php echo $edit_menu['category'] ?? ''; ?>" placeholder="Category" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" step="0.01" name="price" value="<?php echo $edit_menu['price'] ?? ''; ?>" placeholder="Price ($)" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                        <input type="text" name="image_url" value="<?php echo $edit_menu['image_url'] ?? ''; ?>" placeholder="Image URL" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none">
                    </div>
                    <textarea name="description" placeholder="Description" class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none h-20"><?php echo $edit_menu['description'] ?? ''; ?></textarea>
                    <button type="submit" name="save_menu" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold">Save Item</button>
                </form>

                <div class="max-h-[400px] overflow-y-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-sm font-bold">Item</th>
                                <th class="px-4 py-2 text-sm font-bold">Price</th>
                                <th class="px-4 py-2 text-sm font-bold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php while($m = $menu->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-semibold"><?php echo $m['name']; ?></div>
                                    <div class="text-xs text-slate-500"><?php echo $m['category']; ?></div>
                                </td>
                                <td class="px-4 py-3 font-bold text-indigo-600">$<?php echo $m['price']; ?></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="manage_restaurant.php?edit_menu=<?php echo $m['id']; ?>" class="text-indigo-600 text-sm font-bold">Edit</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Management -->
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Active Orders</h2>
                <div class="space-y-4">
                    <?php while($o = $orders->fetch_assoc()): ?>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                        <div>
                            <div class="font-bold text-slate-900"><?php echo $o['fullname']; ?></div>
                            <div class="text-sm text-indigo-600 font-bold">$<?php echo $o['total_amount']; ?></div>
                            <div class="text-xs text-slate-400"><?php echo $o['order_date']; ?></div>
                        </div>
                        <form action="" method="POST" class="flex gap-2">
                            <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                            <select name="status" class="text-xs border rounded-lg px-2 py-1 outline-none">
                                <option value="pending" <?php echo $o['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="preparing" <?php echo $o['status'] == 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                                <option value="delivered" <?php echo $o['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            </select>
                            <button type="submit" name="update_order" class="bg-slate-900 text-white px-3 py-1 rounded-lg text-xs font-bold">Update</button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                    <?php if($orders->num_rows == 0): ?>
                        <p class="text-center text-slate-400 py-8">No active orders.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
