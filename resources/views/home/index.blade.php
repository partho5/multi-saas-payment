<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Payment App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/assets/css/home/index.css" rel="stylesheet">
    <style>
        /* Custom styles for animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-r from-white to-blue-200">
<header class="fixed w-full bg-white shadow-md z-10">
    <nav class="flex justify-between items-center p-4">
        <div class="text-2xl font-bold text-blue-600"><a href="/">SaaS Payment</a></div>
        <div class="hidden md:flex space-x-4 items-center">
            <a href="login" class="text-blue-600 hover:text-blue-800 transition">Login</a>
            <a href="register" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Register</a>
        </div>
        <div class="md:hidden">
            <button id="menu-toggle" class="focus:outline-none">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md">
        <a href="#" class="block px-4 py-2 text-blue-600 hover:bg-blue-100">Login</a>
        <a href="#" class="block px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">Register</a>
    </div>
</header>

<main class="pt-16">
    <section class="text-center py-44">
        <h1 class="text-2xl lg:text-6xl font-bold text-white mb-4 text-border">Multi SaaS ♻ Single Dashboard</h1>
        <p class="text-xl text-gray-200 mb-8">Manage payments for your SaaS applications in a single dashboard</p>
        {{--<img src="placeholder-image.svg" alt="Illustration" class="mx-auto w-full max-w-lg fade-in">--}}
        <a href="#" class="mt-8 inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">Get Started</a>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-600 mb-8">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                    <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z"></path>
                    </svg>
                    <h3 class="font-semibold text-lg">Feature 1</h3>
                    <p class="text-gray-600">Description of feature 1.</p>
                </div>
                <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                    <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 12h3v8h6v-6h4v6h6v-8h3L12 2z"></path>
                    </svg>
                    <h3 class="font-semibold text-lg">Feature 2</h3>
                    <p class="text-gray-600">Description of feature 2.</p>
                </div>
                <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                    <svg class="h-12 w-12 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12l-6 6m0-6l6-6m6 6l6 6m0-6l-6-6"></path>
                    </svg>
                    <h3 class="font-semibold text-lg">Feature 3</h3>
                    <p class="text-gray-600">Description of feature 3.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-blue-100">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold text-blue-600 mb-8">Testimonials</h2>
            <p class="text-gray-700 mb-4">"This app has changed the way we handle payments!"</p>
            <p class="text-gray-500">- Happy Customer</p>
        </div>
    </section>

    <section class="py-20 text-center">
        <h2 class="text-3xl font-bold text-blue-600 mb-8">Get Started Today!</h2>
        <a href="#" class="bg-blue-600 text-white px-8 py-4 rounded hover:bg-blue-700 transition">Sign Up Now</a>
    </section>
</main>

<footer class="bg-white text-center py-4">
    <p class="text-gray-600">© {{ date('Y') }} PayPal SaaS. All rights reserved.</p>
</footer>

<script>
    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
</body>
</html>
