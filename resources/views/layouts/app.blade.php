<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioMed Summit 2027 | European Scientific Summit</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/MMC-LOGO-2.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <style>
        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: high-quality;
        }
        .whatsapp-float {
            position: fixed;
            width: 68px;
            height: 68px;
            bottom: 35px;
            left: 35px;
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: #FFF;
            border-radius: 50%;
            text-align: center;
            font-size: 42px;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: pulse-green 2.5s infinite;
        }
        .whatsapp-float i {
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }
        .whatsapp-float:hover {
            transform: scale(1.15) translateY(-4px);
            color: #FFF;
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.7);
        }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.65); }
            70% { box-shadow: 0 0 0 18px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }
    </style>
</head>
<body>
    @yield('content')
    <!-- Floating WhatsApp Button (Bottom Left) -->
    <a href="{{ $settings['contact_whatsapp_link'] ?? 'https://wa.me/918148018894' }}" class="whatsapp-float" target="_blank" rel="noopener noreferrer" title="Chat with us on WhatsApp (+91 81480 18894)">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- Floating QR Code Widget (Bottom Right) -->
    @include('sections.floating-qr-widget')

    @yield('scripts')
</body>
</html>
