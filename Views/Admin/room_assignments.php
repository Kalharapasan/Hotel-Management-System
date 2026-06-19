<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';
$conn = getDb();

$message = "";

// Handle Room Assignment / Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_room'])) {
    $booking_id = $_POST['booking_id'];
    $room_id = $_POST['room_id'];
    $status = $_POST['status'];

    // Update booking status and room status
    $conn->query("UPDATE bookings SET item_id='$room_id', status='$status' WHERE id=$booking_id");
    if ($status == 'confirmed') {
        $conn->query("UPDATE rooms SET status='booked' WHERE id=$room_id");
    } else if ($status == 'cancelled') {
        $conn->query("UPDATE rooms SET status='available' WHERE id=$room_id");
    }
    
    $message = "Room assignment updated.";
}

// Fetch Room Bookings
$room_bookings = $conn->query("SELECT b.*, u.fullname, u.email, r.room_type, h.name as hotel_name 
                               FROM bookings b 
                               JOIN users u ON b.user_id = u.id 
                               LEFT JOIN rooms r ON b.item_id = r.id AND b.item_type = 'room'
                               LEFT JOIN hotels h ON r.hotel_id = h.id
                               WHERE b.item_type = 'room' OR b.item_type = 'hotel'
                               ORDER BY b.booking_date DESC");

$available_rooms = $conn->query("SELECT r.*, h.name as hotel_name FROM rooms r JOIN hotels h ON r.hotel_id = h.id WHERE r.status='available'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Management | LuxeStay Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex">
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="p-6"><span class="text-2xl font-bold text-indigo-400">LuxeStay Admin</span></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Dashboard</a>
            <a href="manage_customers.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Customers</a>
            <a href="manage_room_bookings.php" class="flex items-center px-4 py-3 bg-indigo-600 rounded-xl">Room Assignments</a>
            <a href="manage_hotels.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Hotels</a>
            <a href="manage_rooms.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Rooms</a>
            <a href="billing.php" class="flex items-center px-4 py-3 hover:bg-slate-800 rounded-xl transition">Billing</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Customer Room Assignments</h1>
            <?php if($message): ?>
                <div class="bg-green-50 text-green-600 px-4 py-2 rounded-xl border border-green-100 text-sm"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Customer</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Hotel/Room</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Status</th>
                        <th class="px-6 py-4 text-sm font-bold text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($booking = $room_bookings->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900"><?php echo $booking['fullname']; ?></div>
                            <div class="text-xs text-slate-500"><?php echo $booking['email']; ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($booking['room_type']): ?>
                                <div class="text-sm font-semibold text-slate-800"><?php echo $booking['room_type']; ?></div>
                                <div class="text-xs text-slate-500"><?php echo $booking['hotel_name']; ?></div>
                            <?php else: ?>
                                <span class="text-xs text-orange-500 font-bold italic">Room Not Assigned</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                                <?php echo $booking['status'] == 'confirmed' ? 'bg-green-100 text-green-600' : ($booking['status'] == 'pending' ? 'bg-orange-100 text-orange-600' : 'bg-red-100 text-red-600'); ?>">
                                <?php echo $booking['status']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form action="" method="POST" class="flex gap-2">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <select name="room_id" class="text-xs border rounded-lg px-2 py-1 outline-none w-32">
                                    <option value="">Assign Room</option>
                                    <?php 
                                    $available_rooms->data_seek(0);
                                    while($room = $available_rooms->fetch_assoc()): ?>
                                        <option value="<?php echo $room['id']; ?>" <?php echo $booking['item_id'] == $room['id'] ? 'selected' : ''; ?>>
                                            <?php echo $room['hotel_name'] . " - " . $room['room_type']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <select name="status" class="text-xs border rounded-lg px-2 py-1 outline-none">
                                    <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirm/Check-in</option>
                                    <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancel/Check-out</option>
                                </select>
                                <button type="submit" name="assign_room" class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs font-bold">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
