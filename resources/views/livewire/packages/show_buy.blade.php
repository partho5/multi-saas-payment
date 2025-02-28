@extends('master')

@section('title', env('APP_NAME'))

@section('css')

@endsection

@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg p-6">
            @php
                $selectedPackage = collect($packageData)->firstWhere('slug', $packageSlug);
            @endphp

            @if($selectedPackage)
                <h2 class="text-2xl font-bold text-gray-800">
                    Your plan
                    <span class="font-semibold uppercase text-gray-900 bg-yellow-200 border border-gray-400 px-4 py-1 rounded-2xl shadow-lg">{{ $selectedPackage['packageName'] }}</span>
                </h2>
                <p class="text-gray-500 mt-4">In this plan you will get <b>{{ $selectedPackage['credits'] }} credits</b> for just</p>

                <div class="mt-4 flex items-center justify-between">
                    <span class="text-3xl font-semibold text-blue-600">${{ $selectedPackage['price'] }}</span>

                    <form action="{{ route('processTransaction') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="relative bg-gradient-to-r from-green-500 to-green-400 hover:from-green-500 hover:to-green-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-300 group">
                          <span class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="none" viewBox="0 0 20 20">
                              <path d="M9.661 2.237a.531.531 0 0 1 .678 0 11.947 11.947 0 0 0 7.078 2.749.5.5 0 0 1 .479.425c.069.52.104 1.05.104 1.59 0 5.162-3.26 9.563-7.834 11.256a.48.48 0 0 1-.332 0C5.26 16.564 2 12.163 2 7c0-.538.035-1.069.104-1.589a.5.5 0 0 1 .48-.425 11.947 11.947 0 0 0 7.077-2.75Zm4.196 5.954a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"></path>
                            </svg>
                            <span class="transform transition-transform duration-300 hover:translate-x-2">Pay Now</span>
                              <!-- Right Arrow Icon -->
                            <span class="w-4 h-4 ml-2 opacity-0 group-hover:opacity-100 transition-opacity transform transition-transform duration-300 translate-x-2" >
                                <svg class="" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            </span>
                          </span>
                        </button>
                    </form>
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
@endsection
