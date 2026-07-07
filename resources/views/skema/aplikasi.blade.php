<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>Buku Kas Dapur MBG</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              paper: "#EDEFE2",
              paperline: "#C7C4AC",
              ink: "#22301F",
              green: { DEFAULT: "#2F5233", deep: "#203823" },
              rust: "#A6432D",
              mustard: "#C98A1F",
              card: "#F7F6ED",
              muted: "#6E6B57",
            },
            fontFamily: {
              sans: ["IBM Plex Sans", "sans-serif"],
              display: ["Space Grotesk", "sans-serif"],
              mono: ["IBM Plex Mono", "monospace"],
            },
          },
        },
      };
    </script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>
<body class="font-sans text-ink min-h-screen antialiased">
    <div class="flex min-h-screen w-full" x-data="{ bukaNav: false }">
        @include('komponen.navigasi_samping')
        <div x-show="bukaNav" @click="bukaNav = false" class="fixed inset-0 bg-black/40 z-30 md:hidden" x-cloak></div>
        <button @click="bukaNav = !bukaNav" class="md:hidden fixed top-3 left-3 z-40 flex items-center gap-1.5 bg-green-deep text-white px-3 py-2 rounded-md text-xs font-mono shadow-md">Menu</button>
        <main class="flex-1 min-w-0 px-4 pt-16 pb-8 sm:px-6 md:px-10 md:py-8">
            @yield('konten')
        </main>
    </div>
    
    @include('komponen.kaki_halaman')
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>