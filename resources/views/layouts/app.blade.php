<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Inspektorat Kabupaten Rembang')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#195de6",
                        secondary: "#0f172a",
                        accent: "#38bdf8",
                        backgroundLight: "#f8fafc",
                    },
                    fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"] },
                },
            },
        }
    </script>
    @stack('styles')
</head>
<body class="bg-backgroundLight text-slate-800 antialiased">

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
<script>
    // Gunakan event listener DOMContentLoaded untuk memastikan semua elemen sudah siap
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        if (btn && menu) {
            btn.addEventListener('click', function() {
                menu.classList.toggle('hidden');
                console.log('Toggle clicked!'); // Cek di console (F12) untuk tes
            });
        }
    });
</script>
</body>
</html>