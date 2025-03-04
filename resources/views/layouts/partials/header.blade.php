<header class="fixed w-full bg-white shadow-md z-10">
    <nav class="flex justify-between items-center p-2">
        <div class="text-2xl font-bold text-blue-600 ml-4">
            <a href="{{ $serviceAppUrl }}" class="flex items-center">
                <img src="https://nanybot-landing.s3.us-west-1.amazonaws.com/assets/images/nanybot-logo-128x128.webp" class="w-10 h-10 rounded-full mr-2" alt="NanyBot Logo" />
                {{--If explicit header title is provided, show it, otherwise show .env/APP_NAME--}}
                <span>{{ isset($headerTitle) && $headerTitle ? $headerTitle : env('APP_NAME') }}</span>
            </a>
        </div>
        {{--<div class="hidden space-x-4 items-center">--}}
            {{--<a href="login" class="text-blue-600 hover:text-blue-800 transition">Login</a>--}}
            {{--<a href="register" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Register</a>--}}
        {{--</div>--}}
        {{--<div class="md:hidden">--}}
            {{--<button id="menu-toggle" class="focus:outline-none">--}}
                {{--<svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">--}}
                    {{--<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>--}}
                {{--</svg>--}}
            {{--</button>--}}
        {{--</div>--}}
    </nav>
    {{--<div id="mobile-menu" class="hidden md:hidden bg-white shadow-md">--}}
        {{--<a href="#" class="block px-4 py-2 text-blue-600 hover:bg-blue-100">Login</a>--}}
        {{--<a href="#" class="block px-4 py-2 bg-blue-600 text-white hover:bg-blue-700">Register</a>--}}
    {{--</div>--}}
</header>


<script>
    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>