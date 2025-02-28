<!DOCTYPE html>
<html>
<head>
    <title>Payment Processing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5 text-center">
    <h2>{{ env('APP_NAME') }}</h2>

    @if (!session('success'))
        <form action="{{ route('processTransaction') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    @endif

    @if (session('success'))
        <div class="alert alert-success mt-4">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-4">{{ session('error') }}</div>
    @endif
</div>

</body>
</html>
