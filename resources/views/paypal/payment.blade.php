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
        {{--<form action="{{ route('processTransaction') }}" method="POST">--}}
            {{--@csrf--}}
            {{--<button type="submit" class="btn btn-primary">Pay Now</button>--}}
        {{--</form>--}}
    @endif

    @if (session('success'))
        <div class="alert alert-success mt-4">{{ session('success') }}</div>
        <div>Your account has been recharged by <b>{{ \Session::get('creditAmount') }} credits</b>. </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-4">{{ session('error') }}</div>
    @endif

    <div class="" style="margin-top: 8rem">
        Go Back to
        <a id="redirect-link" href="{{ env('NANY_ARTICLE_REDIRECT_URL') }}" class="text-blue-500">{{ \Session::get('serviceName') }}</a>
        <div id="countdown" style="margin-top: 1rem; font-size: 16px;">
            Redirecting in <span id="timer">5</span>...
        </div>
    </div>

</div>

<script>
    /**
     * Redirect back showing count down
     * */
    // Set countdown time in seconds
    let countdownTime = 5;

    // Update countdown every second
    const timerElement = document.getElementById('timer');
    const redirectLink = document.getElementById('redirect-link');

    const countdownInterval = setInterval(() => {
            countdownTime--;
    timerElement.textContent = countdownTime;

    if (countdownTime <= 0) {
        clearInterval(countdownInterval);
        window.location.href = redirectLink.href; // Redirect after countdown
    }
    }, 1000);
</script>

</body>
</html>
