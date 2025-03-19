<!-- resources/views/paypal/manage-subscription.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Manage Subscription</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <h5>Subscription Details</h5>
                        <table class="table">
                            <tr>
                                <th>Package:</th>
                                <td>{{ $subscription->package_code }}</td>
                            </tr>
                            <tr>
                                <th>Amount:</th>
                                <td>{{ $subscription->amount }} {{ $subscription->currency }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                <span class="badge {{ $subscription->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $subscription->status }}
                                </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Next Billing Date:</th>
                                <td>{{ $subscription->next_billing_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Billing Cycle:</th>
                                <td>Every {{ $subscription->recurring_duration }} days</td>
                            </tr>
                        </table>

                        <hr>

                        <div class="d-flex justify-content-between">
                        @if($subscription->is_active)
                            <!-- Cancel Subscription Button -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    Cancel Subscription
                                </button>

                                <!-- Suspend Subscription Button -->
                                <form action="{{ route('suspendSubscription', $subscription->subscription_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Pause Subscription</button>
                                </form>
                        @else
                            <!-- Reactivate Subscription Button -->
                                <form action="{{ route('reactivateSubscription', $subscription->subscription_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Reactivate Subscription</button>
                                </form>
                            @endif
                        </div>

                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cancelModalLabel">Cancel Subscription</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('cancelSubscription', $subscription->subscription_id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Are you sure you want to cancel your subscription?</p>
                                            <div class="mb-3">
                                                <label for="reason" class="form-label">Reason for cancellation:</label>
                                                <select class="form-select" id="reason" name="reason" required>
                                                    <option value="">Select a reason</option>
                                                    <option value="Too expensive">Too expensive</option>
                                                    <option value="Not using enough">Not using enough</option>
                                                    <option value="Found better alternative">Found better alternative</option>
                                                    <option value="Technical issues">Technical issues</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection