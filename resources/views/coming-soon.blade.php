<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - Zaya Wellness</title>
    <!-- Fonts loaded via app.css -->
    @vite(['resources/css/app.css'])

    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-sans), system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            /* Add perspective to body for 3D context */
            perspective: 1000px;
            overflow: hidden;
        }

        /* Container for content */
        .container {
            text-align: center;
            opacity: 0;
            animation: fadeIn 1.5s ease-out forwards;
            position: relative;
            z-index: 10;
            transform-style: preserve-3d;
        }

        /* Logo Styling */
        .logo {
            max-width: 250px;
            height: auto;
            margin-bottom: 24px;
            /* Center horizontally explicitly */
            display: block;
            margin-left: auto;
            margin-right: auto;
            /* Full 3D Rotation animation */
            animation: rotate3D 10s ease-in-out infinite;
            /* Ensure smooth rendering */
            backface-visibility: visible;
            transform-style: preserve-3d;
        }

        /* Interactive hover - pause rotation to view details */
        .logo:hover {
            animation-play-state: paused;
            filter: drop-shadow(0 15px 25px rgba(0, 0, 0, 0.1));
            transition: all 0.5s ease;
        }

        /* Text Styling */
        .message {
            font-size: 1.125rem;
            /* 18px */
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #4a4a4a;
            margin-top: 10px;
            position: relative;
            padding-bottom: 10px;
            /* Gentle pulse animation */
            animation: textPulse 4s ease-in-out infinite;
        }

        /* Decorative line under text */
        .message::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background-color: #d4d4d4;
            border-radius: 2px;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 3D Rotation Animation */
        @keyframes rotate3D {
            0% {
                transform: rotateY(0deg) translateY(0);
                filter: drop-shadow(0 10px 10px rgba(0, 0, 0, 0.05));
            }

            25% {
                /* Side view, moving up */
                transform: rotateY(90deg) translateY(-15px);
                filter: drop-shadow(-15px 20px 15px rgba(0, 0, 0, 0.05));
            }

            50% {
                /* Back view */
                transform: rotateY(180deg) translateY(0);
                filter: drop-shadow(0 10px 10px rgba(0, 0, 0, 0.05));
            }

            75% {
                /* Side view, moving down */
                transform: rotateY(270deg) translateY(-15px);
                filter: drop-shadow(15px 20px 15px rgba(0, 0, 0, 0.05));
            }

            100% {
                /* Front view */
                transform: rotateY(360deg) translateY(0);
                filter: drop-shadow(0 10px 10px rgba(0, 0, 0, 0.05));
            }
        }

        @keyframes textPulse {

            0%,
            100% {
                opacity: 0.8;
                color: #4a4a4a;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                color: #1a1a1a;
                transform: scale(1.02);
            }
        }

        /* Responsiveness */
        @media (max-width: 600px) {
            .logo {
                max-width: 180px;
            }

            .message {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Logo -->
        <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness Logo" class="logo">

        <!-- Development Label -->
        <div class="message">Website Under Development</div>
    </div>

</body>

</html>