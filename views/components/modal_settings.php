<!-- Modal Settings / Pengaturan Tampilan -->
<div id="modalSettings" class="fixed inset-0 z-[80] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl relative flex flex-col max-h-[90vh] m-2 modal-content">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-3xl sticky top-0 z-10">
            <h2 class="text-sm font-black text-gray-800 uppercase tracking-wider flex items-center gap-2">
                ⚙️ Pengaturan Tampilan
            </h2>
            <button type="button" onclick="closeModal('modalSettings')" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-xl cursor-pointer transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-5 overflow-y-auto no-scrollbar space-y-6">
            
            <!-- Mode Gelap Toggle -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Mode Gelap (Malam)</h3>
                    <p class="text-xs text-gray-500">Ubah warna layar agar tidak silau</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleDarkMode" class="sr-only peer" onchange="toggleTheme()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 shadow-inner"></div>
                </label>
            </div>

            <!-- Teks Besar Toggle -->
            <div class="flex items-center justify-between border-t border-gray-100 pt-5">
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Teks Besar</h3>
                    <p class="text-xs text-gray-500">Perbesar tulisan agar mudah dibaca</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggleLargeText" class="sr-only peer" onchange="toggleLargeText()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600 shadow-inner"></div>
                </label>
            </div>

            <!-- Backup Database -->
            <div class="flex flex-col border-t border-gray-100 pt-5 space-y-2">
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Backup Data (Migrasi Hosting)</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">Download seluruh data (buku kas & gudang) untuk persiapan pindah server (misal ke Niagahoster/Hostinger) jika masa percobaan di Railway habis.</p>
                </div>
                <button type="button" onclick="downloadDatabaseSQL()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold p-3 rounded-xl text-xs flex justify-center items-center gap-2 cursor-pointer shadow-md transition-all active:scale-95 btn-press mt-1">
                    📦 Download Backup SQL
                </button>
            </div>

            <!-- Instal PWA (Disembunyikan secara default, muncul via JS) -->
            <div id="btnInstallPWA" class="hidden flex-col border-t border-gray-100 pt-5 space-y-2">
                <div>
                    <h3 class="font-bold text-gray-800 mb-1">Install Aplikasi SIGMA 📱</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">Pasang aplikasi ini di layar utama HP ibuk agar bisa dibuka langsung tanpa mengetik link di Google Chrome.</p>
                </div>
                <button type="button" onclick="installPWA()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold p-3 rounded-xl text-xs flex justify-center items-center gap-2 cursor-pointer shadow-md transition-all active:scale-95 btn-press mt-1">
                    ⬇️ Install ke Layar Utama (Home Screen)
                </button>
            </div>

        </div>

    </div>
</div>
