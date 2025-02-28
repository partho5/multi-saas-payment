<?php

namespace App\Livewire\Packages;

use Livewire\Component;

class PackageSelection extends Component
{
    public $serviceIdentifier; // Public property for serviceIdentifier
    public $appData;
    public $packages = [];  // Initialize packages as an empty array
    public $selectedPackage;

    public function mount($serviceIdentifier)
    {
        $this->serviceIdentifier = $serviceIdentifier; // Set the serviceIdentifier property

        // Get the application's packages from the config file
        $apps = config('data.apps');

        // Check if the serviceIdentifier exists in the configuration
        if (isset($apps[$this->serviceIdentifier])) {
            // Fetch the packages
            $this->packages = $apps[$this->serviceIdentifier]['packages'];
            $this->appData = $apps[$this->serviceIdentifier];
        } else {
            // If not found, abort with a 404 error
            abort(404, 'App not found.');
        }
    }



    // show pricing plans
    public function render()
    {
        // dd($this->appData);
        return view('livewire.packages.package-selection')
            ->layout('livewire.packages.layout', [
                'appData' => $this->appData,
                'packages' => $this->packages ?? [],
            ]);
    }
}