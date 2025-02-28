<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') Pricing Plans</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* General Container */
        .container {
            max-width: 1200px;
        }

        /* Header Styles */
        h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #333;
        }

        /* Pricing Box Styles */
        .pricing-box {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .pricing-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        /* Header Image Overlay */
        .pricing-box .header {
            position: relative;
            height: 100px;
            background-size: cover;
            background-position: center;
        }
        .pricing-box .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1; /* Ensure the gradient is above the image */
        }
        /* Pricing Box 1 */
        .pricing-box-1 .header::before {
            background: linear-gradient(90deg, rgba(0, 198, 255, 0.9), rgba(0, 123, 255, 0.8));
        }
        .pricing-box-1 {
            background: rgba(235, 252, 255, 0.4);
        }
        .pricing-box-1:hover {
            background: #FFF;
        }

        /* Pricing Box 2 */
        .pricing-box-2 .header::before {
            background: linear-gradient(90deg, rgba(210, 145, 255, 0.9), rgba(106, 13, 173, 0.8));
        }
        .pricing-box-2 {
            background: rgba(253, 248, 255, 0.7);
        }
        .pricing-box-2:hover {
            background: #FFF;
        }

        /* Pricing Box 3 */
        .pricing-box-3 .header::before {
            background: linear-gradient(90deg, rgba(255, 111, 97, 0.9), rgba(220, 53, 69, 0.8));
        }
        .pricing-box-3 {
            background: rgba(255, 239, 241, 0.4);
        }
        .pricing-box-3:hover {
            background: #FFF;
        }



        .pricing-box h2 {
            position: relative;
            color: #fff;
            z-index: 2;
        }

        /* Pricing and Features */
        .pricing-box .price {
            font-size: 1.5rem;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .pricing-box ul {
            list-style-type: none;
            padding: 0;
        }
        .pricing-box ul li {
            color: #555;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .pricing-box ul li:last-child {
            border-bottom: none;
        }

        /* Base button styles */
        .pricing-box button {
            width: 100%;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        /* Pricing Box 1 */
        .pricing-box-1 button {
            background: linear-gradient(90deg, #007bff 0%, #00b8ed 100%);
        }

        .pricing-box-1 button:hover {
            background: linear-gradient(90deg, #00b8ed 0%, #007bff 100%);
        }

        /* Pricing Box 2 */
        .pricing-box-2 button {
            background: linear-gradient(90deg, #6A0DAD 0%, #D291FF 100%);
        }

        .pricing-box-2 button:hover {
            background: linear-gradient(90deg, #D291FF 0%, #6A0DAD 100%);
        }

        /* Pricing Box 3 */
        .pricing-box-3 button {
            background: linear-gradient(90deg, #DC3545 0%, #FF6F61 100%);
        }

        .pricing-box-3 button:hover {
            background: linear-gradient(90deg, #FF6F61 0%, #DC3545 100%);
        }


        .text-border {
            color: white; /* Main text color */
            text-shadow:
                    1px 1px 0 rgba(0, 0, 0, 0.5),
                    -1px -1px 0 rgba(0, 0, 0, 0.5),
                    1px -1px 0 rgba(0, 0, 0, 0.5),
                    -1px 1px 0 rgba(0, 0, 0, 0.5);
        }

        .policies a{
            color: #00F;
        }
        .policies .policy-link{
            margin-right: 20px;
            text-decoration: underline;
        }
    </style>


    <!-- Add any global styles or scripts here -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @livewireStyles
</head>

<body>
<!-- Include a header or navbar if necessary -->
@include('layouts.partials.header', ['headerTitle' => $appData['serviceName']])

<main>
<!-- Livewire component content will be injected here -->
{{ $slot }}
</main>

<!-- Include a footer if necessary -->
@include('layouts.partials.footer')

<!-- Livewire Scripts -->
@livewireScripts
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
