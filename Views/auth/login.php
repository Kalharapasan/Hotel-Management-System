<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LuxeStay</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <!-- Login Container -->
    <div class="w-96 bg-white p-6 border">

        <!-- Heading -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Welcome Back</h1>
            <p>Please login to your account</p>
        </div>

        <!-- Error Message -->
        <?php if(!empty($error)): ?>
            <div class="bg-red-100 text-red-600 p-2 mb-4 border">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="/login" method="POST">

            <!-- Email Input -->
            <div class="mb-4">
                <label>Email Address</label>
                <input
                    type="email"
                    name="email"
                    required
                    class="w-full p-2 border"
                >
            </div>

            <!-- Password Input -->
            <div class="mb-4">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full p-2 border"
                >
            </div>

            <!-- Login Button -->
            <button
                type="submit"
                class="w-full bg-blue-500 text-white p-2"
            >
                Login
            </button>

        </form>

        <!-- Register Link -->
        <p class="text-center mt-4">
            New here?
            <a href="/register" class="text-blue-500">
                Create Account
            </a>
        </p>

    </div>

</body>
</html>