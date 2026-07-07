<aside :class="bukaNav ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 z-50 w-64 shrink-0 bg-green-deep text-[#EFEAD8] flex flex-col p-4 border-r-[3px] border-double border-[#16281a] transform transition-transform duration-200 md:translate-x-0">
    <div class="flex items-center justify-between pb-5 border-b border-white/15 mb-3.5">
      <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-md bg-mustard text-[#2b1c04] flex items-center justify-center font-display font-bold text-[13px] -rotate-3 shrink-0">MBG</div>
          <div>
            <div class="font-display font-semibold text-[15px] leading-tight">Buku Kas Dapur</div>
            <div class="text-[11px] opacity-75">Program Makan Gratis</div>
          </div>
      </div>
      <button @click="bukaNav = false" class="md:hidden text-white/80 hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
      </button>
    </div>
    <nav class="flex flex-col gap-0.5 flex-1 overflow-y-auto">
        <a href="{{ route('ringkasan') }}" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm {{ Route::is('ringkasan') ? 'bg-mustard text-ink font-semibold' : 'hover:bg-white/10' }}">Ringkasan</a>
        <a href="{{ route('master.indeks') }}" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm {{ Route::is('master.indeks') ? 'bg-mustard text-ink font-semibold' : 'hover:bg-white/10' }}">Master Barang</a>
        <a href="{{ route('olahan.indeks') }}" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm {{ Route::is('olahan.indeks') ? 'bg-mustard text-ink font-semibold' : 'hover:bg-white/10' }}">Barang Olahan</a>
        <a href="{{ route('inventaris.indeks') }}" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm {{ Route::is('inventaris.indeks') ? 'bg-mustard text-ink font-semibold' : 'hover:bg-white/10' }}">Barang Inventaris</a>
        <a href="{{ route('operasional.indeks') }}" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm {{ Route::is('operasional.indeks') ? 'bg-mustard text-ink font-semibold' : 'hover:bg-white/10' }}">Pengeluaran Operasional</a>
        <a href="{{ route('laporan.indeks') }}" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm {{ Route::is('laporan.indeks') ? 'bg-mustard text-ink font-semibold' : 'hover:bg-white/10' }}">Laporan Bulanan</a>
    </nav>
</aside>