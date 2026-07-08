<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk | MBG</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ["IBM Plex Sans", "sans-serif"],
              display: ["Space Grotesk", "sans-serif"],
            },
            colors: {
              brandYellow: "#FCD34D",
              brandYellowDark: "#FBBF24",
              brandWhite: "#FFFFFF",
              brandGray: "#F8FAFC",
              brandText: "#1f2937",
            },
          },
        },
      };
    </script>
</head>
<body class="min-h-screen bg-[#EBE7D1] text-[#1F2922] font-sans">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(244,214,84,0.16),transparent_20%),radial-gradient(circle_at_bottom_right,rgba(40,56,42,0.08),transparent_22%)]"></div>
    <div class="absolute inset-0 bg-[repeating-linear-gradient(180deg,rgba(255,255,255,0.45),rgba(255,255,255,0.45)_1px,transparent_1px,transparent_32px)]"></div>
    <div class="relative min-h-screen flex items-center justify-center px-4 py-10">
        <div class="relative w-full max-w-sm">
            <div class="absolute -inset-1 rounded-[32px] bg-gradient-to-br from-[#F4E6A1]/80 via-[#F8E5B1]/50 to-[#FFFFFF]/80 blur-2xl"></div>
            <div class="relative overflow-hidden rounded-[32px] border border-[#E8D9AD] bg-white/95 p-8 shadow-[0_32px_90px_rgba(34,48,31,0.12)] backdrop-blur-md">
                <div class="mb-8 text-center">
                    <p class="text-xs uppercase tracking-[0.35em] text-[#3B522F]"></p>
                    <h1 class="mt-4 text-3xl font-semibold text-[#12200F]">Form Login</h1>
                    <p class="mt-3 text-sm text-[#4F5F3A]">Masuk untuk melihat kondisi kas & logistik dapur.</p>
                </div>

                <?php if(isset($errors) && $errors->any()): ?>
                  <div class="mb-4 rounded-lg border border-[#F0DFA1] bg-[#FFF8E5] p-3 text-sm text-[#7B5E1A]">
                    <ul class="list-disc pl-5 m-0">
                      <?php foreach($errors->all() as $err): ?>
                        <li><?php echo htmlspecialchars($err, ENT_QUOTES); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>

                <form action="/masuk" method="POST" class="space-y-6">
                  <?php echo csrf_field(); ?>
                  <div>
                    <label for="name" class="block text-sm font-medium text-[#2B3A23]">Nama Pengguna</label>
                    <input id="name" name="name" type="text" required
                         value="<?php echo htmlspecialchars(old('name'), ENT_QUOTES); ?>"
                         class="mt-3 w-full rounded-3xl border border-[#31603C] bg-white px-4 py-3 text-sm text-[#15210F] shadow-sm outline-none transition duration-300 focus:border-[#25492D] focus:ring-2 focus:ring-[#FDE68A]/60" placeholder="Nama pengguna Anda" />
                    <?php if(isset($errors) && $errors->has('name')): ?>
                      <p class="mt-2 text-xs text-red-600"><?php echo htmlspecialchars($errors->first('name'), ENT_QUOTES); ?></p>
                    <?php endif; ?>
                  </div>
                  <div>
                    <label for="password" class="block text-sm font-medium text-[#2B3A23]">Kata Sandi</label>
                    <input id="password" name="password" type="password" required
                         class="mt-3 w-full rounded-3xl border border-[#31603C] bg-white px-4 py-3 text-sm text-[#15210F] shadow-sm outline-none transition duration-300 focus:border-[#25492D] focus:ring-2 focus:ring-[#FDE68A]/60" placeholder="Kata sandi Anda" />
                    <?php if(isset($errors) && $errors->has('password')): ?>
                      <p class="mt-2 text-xs text-red-600"><?php echo htmlspecialchars($errors->first('password'), ENT_QUOTES); ?></p>
                    <?php endif; ?>
                  </div>
                  <button type="submit"
                      class="w-full rounded-3xl bg-[#B78E1A] px-4 py-3 text-sm font-semibold text-[#12200F] transition duration-300 hover:bg-[#9B7A18] hover:shadow-[0_15px_30px_rgba(91,66,13,0.25)]">
                    Login
                  </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>