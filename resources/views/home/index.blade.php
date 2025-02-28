@extends('master')

@section('title', env('APP_NAME'))

@section('css')
    <link href="/assets/css/home/index.css" rel="stylesheet">
@endsection

@section('content')
    <section class="text-center py-44">
        <h1 class="text-2xl lg:text-6xl font-bold text-white mb-4 text-border fade-in ">Nany SaaS ♻ Payment</h1>
        <p class="text-xl text-gray-200 mb-8">Any payment from NanyBot is processed here,<br>then redirected back to corresponding app</p>
        <a href="/register" class="mt-8 inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">Get Started</a>
    </section>

    <section class="py-20 bg-gradient-to-b from-white to-blue-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-4">Powerful Features</h2>
            <p class="text-gray-600 mb-12 max-w-2xl mx-auto">Streamline your SaaS payments with our comprehensive solution</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Unified Dashboard -->
                <div class="p-8 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Unified Dashboard</h3>
                    <p class="text-gray-600 leading-relaxed">Track and manage all your SaaS subscriptions in one place. Get real-time analytics and payment history across all your applications.</p>
                </div>

                <!-- Smart Routing -->
                <div class="p-8 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Smart Routing</h3>
                    <p class="text-gray-600 leading-relaxed">Seamless payment processing with intelligent routing. Automatic redirection back to your apps after successful transactions.</p>
                </div>

                <!-- Security & Compliance -->
                <div class="p-8 bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Security & Compliance</h3>
                    <p class="text-gray-600 leading-relaxed">Bank-grade encryption and compliance with PCI DSS. Multi-factor authentication and fraud prevention for all transactions.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-b from-blue-50 to-blue-200 relative overflow-hidden">
        <div class="container mx-auto text-center relative z-10">
            <h2 class="text-3xl font-bold text-blue-600 mb-8">Get Started Today!</h2>
            <a href="/register" class="bg-blue-600 text-white px-8 py-4 rounded hover:bg-blue-700 transition">Sign Up Now</a>
        </div>

        <!-- SVG serving as background -->
        <svg class="absolute top-0 left-0 w-full h-[30vh] object-cover z-0" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 511.997 511.997" xml:space="preserve" fill="#000000">
        <!-- SVG content here -->
            <g id="SVGRepo_iconCarrier">
                <circle style="fill:#fff;" cx="255.999" cy="255.999" r="255.999"/>
                <ellipse style="fill:#ED4C54;" cx="255.999" cy="421.644" rx="182.283" ry="14.369"/>
                <!-- other paths and elements of your SVG -->
            </g>
        </svg>
    </section>


@endsection

@section('js')
    <script src="/assets/js/home/home.js"></script>
@endsection
