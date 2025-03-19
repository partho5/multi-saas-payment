@extends('master')

@section('title', env('APP_NAME'))

@section('css')

@endsection

@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg my-16 py-4 px-2 md:px-16">
            @php
                $selectedPackage = collect($packageData)->firstWhere('slug', $packageSlug);
            @endphp

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <strong class="font-bold">Error:</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif


        @if($selectedPackage)
                <h2 class="text-xl text-gray-800 py-4 px-4">
                    Your plan
                    <span class="font-semibold uppercase text-gray-900 bg-yellow-200 border border-gray-400 px-4 py-1 rounded-2xl shadow-lg">{{ $selectedPackage['packageName'] }}</span>
                </h2>
                <div class="mt-4 px-4 flex items-center justify-between">
                    <p class="text-gray-500">
                        <span class="inline-block md:hidden">You will get</span>
                        <span class="hidden md:inline-block">In this plan you will get</span>
                        <b>{{ $selectedPackage['credits'] }} credits</b> for just
                    </p>
                    <span class="text-3xl font-semibold text-blue-600">${{ $selectedPackage['price'] }}</span>
                </div>

                <!-- User Info Form -->
                <form action="{{ route('processTransaction') }}" method="POST" class="mt-8 border border-blue-100 rounded-lg p-4 pt-0">
                    <p class="text-center mb-3 -mt-3 text-gray-600 bg-blue-50 border border-blue-100 rounded-lg">
                        <span class="pb-1">Billing Info</span>
                    </p>
                    @csrf

                    <!-- User Details -->
                    <div class="space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center mt-8">
                            <label for="name" class="block w-full md:w-1/4 font-medium text-gray-700 mb-1 md:mb-0">Name</label>
                            <div class="w-full md:w-3/4">
                                <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        placeholder="Your Full Name"
                                        value="{{ old('name', auth()->user()->name ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        required
                                />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="email" class="block w-full md:w-1/4 font-medium text-gray-700 mb-1 md:mb-0">Email</label>
                            <div class="w-full md:w-3/4">
                                <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        placeholder="The same email used in Nany account"
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        required
                                />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="phone" class="block w-full md:w-1/4 font-medium text-gray-700 mb-1 md:mb-0">Phone</label>
                            <div class="w-full md:w-3/4">
                                <input
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        required
                                        placeholder="Needed for fraud protection system"
                                        value="{{ old('phone') }}"
                                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                />
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center">
                            <label for="paymentType" class="block w-full md:w-1/4 font-medium text-gray-700 mb-1 md:mb-0">Pay for</label>
                            <div class="w-full md:w-3/4">
                                <select
                                        id="paymentType"
                                        name="paymentType"
                                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                        required
                                >
                                    <option value="one-time" {{ old('paymentType') == 'one-time' ? 'selected' : '' }}>One-time</option>
                                    <option value="monthly" {{ old('paymentType') == 'monthly' ? 'selected' : '' }}>Monthly Recurrence</option>
                                </select>
                            </div>
                        </div>

                        <!-- Hidden Fields for Backend -->
                        <input type="hidden" id="isRecurring" name="isRecurring" value="{{ old('paymentType') == 'monthly' ? 1 : 0 }}">
                        <input type="hidden" id="recurringDuration" name="recurringDuration" value=30>
                        <input type="hidden" name="packageName" value="{{ $selectedPackage['packageName'] }}">

                    </div>

                    {{--<input type="hidden" name="packageCode" value="{{ $selectedPackage['packageCode'] }}">--}}
                    <input type="hidden" name="userId" id="userId" value="">

                    <!-- Pay Now Button -->
                    <button type="submit" class="mt-12 relative bg-gradient-to-r from-green-500 to-green-400 hover:from-green-500 hover:to-green-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-300 group w-full">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="none"
                                 viewBox="0 0 20 20">
                                <path
                                        d="M9.661 2.237a.531.531 0 0 1 .678 0 11.947 11.947 0 0 0 7.078 2.749.5.5 0 0 1 .479.425c.069.52.104 1.05.104 1.59 0 5.162-3.26 9.563-7.834 11.256a.48.48 0 0 1-.332 0C5.26 16.564 2 12.163 2 7c0-.538.035-1.069.104-1.589a.5.5 0 0 1 .48-.425 11.947 11.947 0 0 0 7.077-2.75Zm4.196 5.954a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z">
                                </path>
                            </svg>
                            <span class="transform transition-transform duration-300 hover:translate-x-2">Pay Now</span>
                            <span class="w-4 h-4 ml-2 opacity-0 group-hover:opacity-100 transition-opacity transform transition-transform duration-300 translate-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </span>
                    </button>
                </form>

                <!-- Soft Feel Section Below the Pay Now Button -->
                <div class="mt-6 text-gray-400 text-sm text-center">
                    No hidden fees. Secure and fast checkout.
                </div>


                <div class="mt-6">
                    <hr>
                    <h3 class="text-lg font-semibold text-gray-700">Features:</h3>
                    <ul class="mt-2 space-y-2">
                        @foreach($selectedPackage['features'] as $feature)
                            @if(!empty($feature))
                                <li class="flex items-center space-x-2 text-gray-600">
                                    <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-center text-gray-600">Package not found.</p>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/home/home.js"></script>

    <script>
        /**
         * Take URL parameter ?d= and extracts data from it, decode base64. then set to input fields.
         * */

        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Get base64 encoded data from URL
        const encodedData = getQueryParam('d');

        if (encodedData) {
            try {
                // Decode Base64
                const jsonString = atob(encodedData);
                // Parse JSON
                const data = JSON.parse(jsonString);

                // Set values to input fields if present
                if (data.userId) document.getElementById('userId').value = data.userId;
                if (data.email) document.getElementById('email').value = data.email;
                if (data.displayName) document.getElementById('name').value = nameToUpperCase(data.displayName);
            } catch (error) {
                console.error('Error decoding base64 data:', error);
            }
        }

        function nameToUpperCase(name) {
            return name
                    .split(' ') // Split by space
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Capitalize first letter
                    .join(' '); // Join words back together
        }



        document.getElementById('paymentType').addEventListener('change', function () {
            const isRecurring = this.value === 'monthly' ? 1 : 0;
            document.getElementById('isRecurring').value = isRecurring;
            document.getElementById('recurringDuration').value = isRecurring === 0 ? 0 : 30;
        });
    </script>


@endsection
