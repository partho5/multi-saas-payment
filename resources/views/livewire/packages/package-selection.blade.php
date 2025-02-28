@section('title')
    {{ $appData['serviceName'] }}
@endsection

<div xmlns:wire="http://www.w3.org/1999/xhtml">
    <div class="container max-w-6xl mx-auto py-24 px-4">
        {{--<h1 class="text-4xl font-bold text-center mb-4">{{ $appData['serviceName'] }}</h1>--}}
        <h1 class="text-2xl font-bold text-center pt-4 mb-8">{{ $appData['serviceName'] }} Pricing Plans</h1>

        @if(!empty($appData['packages']))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($appData['packages'] as $index => $package)
                    <div class="pricing-box pricing-box-{{ $index+1 }} border rounded shadow-lg overflow-hidden">
                        <div class="header bg-cover bg-center h-32 flex items-center justify-center" style="background-image: url('{{ asset('assets/images/' . ($package['header_image_url'] ?? '')) }}');">
                            <h2 class="text-white text-4xl font-bold text-center text-border">{{ $package['name'] }}</h2>
                        </div>


                        <div class="p-6">
                            <p class="price">${{ $package['price'] }} / month</p>
                            <ul>
                                @foreach($package['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>

                            <div class="mt-2">
                                <a class="w-full" href="{{ $package['slug'] }}/buy">
                                    <button class="w-full">
                                        {{--wire:click="selectPackage('{{ $package['id'] }}')"--}}
                                        @if($index == 0)
                                            Start with {{ $package['name'] }}
                                        @elseif($index == 1)
                                            Select {{ $package['name'] }}
                                        @else
                                            Go for {{ $package['name'] }}
                                        @endif
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <div>Need a customized plan?</div>
                <div>Contact support: {{ $appData['supportEmail'] }}</div>
            </div>


            <div class="mt-20">
                <hr class="border-gray-300">
            </div>

            <div class="mt-8 flex flex-col md:flex-row justify-between">
                <div class="policies mb-4 md:mb-0">
                    <h3>Policies of {{ $appData['serviceName'] }}:</h3>
                    <div class="">
                        <span class="policy-link"><a href="/" target="_blank">Privacy Policy</a></span>
                        <span class="policy-link"><a href="/" target="_blank">Refund Policy</a></span>
                        <span class="policy-link"><a href="/" target="_blank">Terms & Conditions</a></span>
                    </div>
                </div>

                <div class="mt-4 md:mt-0">
                    <h3>Customer Support:</h3>
                    Contact support via: {{ $appData['supportEmail'] }}
                </div>
            </div>

            <div class="mt-12 -mb-16 text-center">
                <div class="text-md font-weight-bold">
                    This payment system is powered by
                    <a href="/" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                        {{ env('APP_NAME') }}
                    </a>
                </div>
            </div>
        @else
            <p class="text-center text-gray-600">No packages available for this application.</p>
        @endif
    </div>

</div>