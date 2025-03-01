<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PackageController extends Controller
{
    public function showPackages(Request $request, $serviceIdentifier) {
        // Get the application's packages from the config file
        $apps = config('data.apps');

        // Check if the serviceIdentifier exists in the configuration
        if (isset($apps[$serviceIdentifier])) {
            // Return the available packages for the specific serviceIdentifier
            return response()->json($apps[$serviceIdentifier]['packages']);
        }

        // Return a 404 response if the serviceIdentifier is not found
        return response()->json(['error' => 'App not found.'], 404);
    }


    public function showBuyPackage(Request $request, $serviceIdentifier, $packageSlug){
        $services = config('data.apps');
        $packageUrl = $services[$serviceIdentifier]['packageUrl'];
        // Fetch JSON from the package URL
        $packageData = Http::get($packageUrl)->json();
        //dd($packageData);

        $chosenPackage = null;
        $packagePrice = 0;
        // Loop through the packages to find the one with the matching slug
        foreach ($packageData as $package) {
            //dd($package);
            if ($package['slug'] === $packageSlug) {
                $chosenPackage = $package;
                $packagePrice = $package['price'];
                \Session::put('packagePrice', $packagePrice);
                $packageCode = $package['packageCode'];
                \Session::put('packageCode', $packageCode);
                break; // Exit the loop once the package is found
            }
        }

        return view('livewire.packages.show_buy', [
            //'serviceIdentifier' => $serviceIdentifier,
            'serviceName' => $services[$serviceIdentifier]['serviceName'],
            'packageData' => $packageData,
            'packageSlug' => $packageSlug,
        ]);
    }

    public function processPayment(Request $request){
        // Logic to handle payment processing
    }
}
