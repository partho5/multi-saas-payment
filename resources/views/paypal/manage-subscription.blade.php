<!-- resources/views/paypal/manage-subscription.blade.php -->

@extends('master')

@section('content')
    <div class="container min-h-screen mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-2xl p-6">
            <h2 class="text-2xl text-center font-semibold text-gray-800 mb-4">Manage Subscription</h2>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <h3 class="text-lg font-medium text-gray-700 mb-3">Subscription Details</h3>
            <div class="bg-gray-100 p-4 rounded-lg">
                <div class="grid grid-cols-1 gap-4">
                    <div><strong>Package:</strong> {{ $subscription->packageName ?? '' }}</div>
                    <div><strong>Amount:</strong> {{ $subscription->amount }} {{ $subscription->currency }}</div>
                    <div><strong>Status:</strong>
                        <span class="px-2 py-1 text-white text-sm rounded-lg {{ $subscription->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $subscription->status }}
                        </span>
                    </div>
                    <div><strong>Next Billing Date:</strong> {{ \Carbon\Carbon::parse($subscription->next_billing_date)->format('F j, Y \a\t g:i A') }}</div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
            @if($subscription->status === 'ACTIVE')
                <!-- Cancel Subscription Button -->
                    <button id="cancelButton" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Cancel Subscription
                    </button>

                    <!-- Pause Subscription Form -->
                    <form action="{{ route('suspendSubscription', $subscription->transac_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                            Pause Subscription
                        </button>
                    </form>

            @elseif($subscription->status === 'SUSPENDED')
                <!-- Reactivate Subscription Form (Only for Suspended Subscriptions) -->
                    <form action="{{ route('reactivateSubscription', $subscription->transac_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            Reactivate Subscription
                        </button>
                    </form>

            @else
                <!-- If Subscription is CANCELLED, No Action Buttons -->
                    <p class="text-gray-500">Your subscription has been cancelled. <br> But you can purchase again anytime</p>
            @endif
            </div>

        </div>
    </div>

    <!-- Cancel Subscription Modal -->
    <div id="cancelModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold">Cancel Subscription</h3>
            <p class="text-gray-600 mb-4 hidden">Dear user, are you sure to cancel your subscription?</p>

            <form action="{{ route('cancelSubscription', $subscription->transac_id) }}" method="POST">
                @csrf
                <label class="block mb-2 mt-2">Dear user, your feedback will help us improve service quality:</label>
                <select name="reason" required class="w-full p-2 border rounded-lg">
                    <option value="">Select a reason</option>
                    <option value="Too expensive">Too expensive</option>
                    <option value="Not using enough">Not using enough</option>
                    <option value="Found better alternative">Found better alternative</option>
                    <option value="Technical issues">Technical issues</option>
                    <option value="Other">Other</option>
                </select>
                <div class="mt-4 flex justify-end">
                    <button type="button" id="closeModalButton" class="mr-2 px-4 py-2 border rounded-lg">Close</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelButton = document.getElementById('cancelButton');
            const closeModalButton = document.getElementById('closeModalButton');
            const cancelModal = document.getElementById('cancelModal');

            // Open modal when cancel button is clicked
            if (cancelButton) {
                cancelButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    cancelModal.classList.remove('hidden');
                });
            }

            // Close modal when close button is clicked
            if (closeModalButton) {
                closeModalButton.addEventListener('click', function() {
                    cancelModal.classList.add('hidden');
                });
            }

            // Close modal when clicking outside the modal content
            cancelModal.addEventListener('click', function(e) {
                if (e.target === cancelModal) {
                    cancelModal.classList.add('hidden');
                }
            });
        });
    </script>
@endsection