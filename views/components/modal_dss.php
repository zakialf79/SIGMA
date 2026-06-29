<!-- KrispiKas - Modal DSS (Dashboard Statistik & Analisis) -->
<!-- FIX: Menambahkan elemen dssTotalMasuk dan dssTotalKeluar yang sebelumnya hilang -->
<div id="modalDSS" class="fixed inset-0 z-[80] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl relative p-5 space-y-5 m-2 max-h-[94vh] overflow-y-auto no-scrollbar modal-content">
        <div class="flex justify-between items-center border-b border-blue-100 pb-2 sticky top-0 bg-white z-10">
            <h3 class="text-sm font-black text-blue-700 uppercase tracking-wider">📊 Dashboard Analisis SIGMA</h3>
            <button type="button" onclick="closeModal('modalDSS')" class="text-gray-400 text-xl font-bold cursor-pointer">✕</button>
        </div>

        <!-- [FIX] Ringkasan Total Masuk & Keluar — elemen yang sebelumnya hilang -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-3.5 rounded-2xl border border-emerald-100 text-center">
                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Total Omset Masuk</p>
                <p id="dssTotalMasuk" class="text-base font-black text-emerald-800 num-transition">Rp 0</p>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100/50 p-3.5 rounded-2xl border border-red-100 text-center">
                <p class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-0.5">Total Pengeluaran</p>
                <p id="dssTotalKeluar" class="text-base font-black text-red-800 num-transition">Rp 0</p>
            </div>
        </div>
        
        <!-- 1. Grafik Laba/Rugi -->
        <div class="space-y-2">
            <h4 class="font-black text-xs text-gray-700 uppercase tracking-wider flex items-center gap-1"><span>📈</span> 1. Performa Laba/Rugi Bulanan</h4>
            <div class="bg-gray-50 border border-gray-200 p-2 rounded-2xl shadow-inner relative h-40 w-full">
                <canvas id="canvasLabaRugi"></canvas>
            </div>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-2.5 rounded-r-lg text-[10px] text-blue-800 font-medium" id="aiLabaRugi"></div>
        </div>

        <hr class="border-gray-100">

        <!-- 2. Rasio Pendapatan -->
        <div class="space-y-2">
            <h4 class="font-black text-xs text-gray-700 uppercase tracking-wider flex items-center gap-1"><span>🤝</span> 2. Rasio Sumber Pendapatan</h4>
            <div class="bg-gray-50 border border-gray-200 p-2 rounded-2xl shadow-inner relative h-48 w-full flex justify-center">
                <canvas id="canvasMetodeJual"></canvas>
            </div>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-2.5 rounded-r-lg text-[10px] text-blue-800 font-medium" id="aiMetodeJual"></div>
        </div>

        <hr class="border-gray-100">

        <!-- 3. Pemakaian Bahan -->
        <div class="space-y-2">
            <h4 class="font-black text-xs text-gray-700 uppercase tracking-wider flex items-center gap-1"><span>🔥</span> 3. Akumulasi Pemakaian Bahan</h4>
            <div class="bg-gray-50 border border-gray-200 p-2 rounded-2xl shadow-inner relative h-40 w-full">
                <canvas id="canvasBahanBaku"></canvas>
            </div>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-2.5 rounded-r-lg text-[10px] text-blue-800 font-medium" id="aiBahanBaku"></div>
        </div>

        <hr class="border-gray-100">

        <!-- 4. Trend Pendapatan Bulanan -->
        <div class="space-y-2">
            <h4 class="font-black text-xs text-gray-700 uppercase tracking-wider flex items-center gap-1"><span>📊</span> 4. Trend Pendapatan (Bulan ke Bulan)</h4>
            <div class="bg-gray-50 border border-gray-200 p-2 rounded-2xl shadow-inner relative h-48 w-full">
                <canvas id="canvasTrendPendapatan"></canvas>
            </div>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-2.5 rounded-r-lg text-[10px] text-blue-800 font-medium" id="aiTrendPendapatan"></div>
        </div>

        <hr class="border-gray-100">

        <!-- 5. Kalkulator HPP Cerdas -->
        <div class="space-y-3 bg-gradient-to-br from-amber-50 to-orange-50/50 p-4 rounded-3xl border border-amber-200/60 shadow-sm mt-4">
            <h4 class="font-black text-xs text-amber-800 uppercase tracking-wider flex items-center gap-1">
                <span>🧮</span> 5. Kalkulator HPP & Simulasi Laba
            </h4>
            <p class="text-[10px] text-amber-700 font-medium leading-relaxed mb-2">Simulasikan Harga Pokok Penjualan (HPP) berdasarkan harga pasar bahan baku dan rasio pemakaian riil dari gudang Anda.</p>
            
            <div class="grid grid-cols-2 gap-2">
                <div class="space-y-1">
                    <label class="block text-[9px] font-black text-gray-600 uppercase">Harga 1 Kg Kulit Mentah</label>
                    <input type="text" id="hppHargaKulit" onkeyup="formatInputRupiah(this)" class="w-full p-2.5 border border-amber-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-amber-400" placeholder="Rp">
                </div>
                <div class="space-y-1">
                    <label class="block text-[9px] font-black text-gray-600 uppercase">Hasil per 1 Kg (Bungkus)</label>
                    <input type="number" id="hppBungkus" class="w-full p-2.5 border border-amber-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-amber-400" placeholder="Pcs">
                </div>
                <div class="space-y-1">
                    <label class="block text-[9px] font-black text-gray-600 uppercase">Harga Minyak Goreng /Kg</label>
                    <input type="text" id="hppHargaMinyak" onkeyup="formatInputRupiah(this)" class="w-full p-2.5 border border-amber-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-amber-400" placeholder="Rp">
                </div>
                <div class="space-y-1">
                    <label class="block text-[9px] font-black text-gray-600 uppercase">Harga Gas /Tabung</label>
                    <input type="text" id="hppHargaGas" onkeyup="formatInputRupiah(this)" class="w-full p-2.5 border border-amber-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-amber-400" placeholder="Rp">
                </div>
                <div class="space-y-1">
                    <label class="block text-[9px] font-black text-gray-600 uppercase">Harga 1 Lembar Plastik</label>
                    <input type="text" id="hppPlastik" onkeyup="formatInputRupiah(this)" class="w-full p-2.5 border border-amber-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-amber-400" placeholder="Rp">
                </div>
                <div class="space-y-1">
                    <label class="block text-[9px] font-black text-gray-600 uppercase">Biaya Bumbu per Kg</label>
                    <input type="text" id="hppBumbu" onkeyup="formatInputRupiah(this)" class="w-full p-2.5 border border-amber-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-amber-400" placeholder="Rp">
                </div>
            </div>
            
            <div class="space-y-1 mt-2">
                <label class="block text-[9px] font-black text-emerald-700 uppercase">Harga Jual per Bungkus Saat Ini</label>
                <input type="text" id="hppJual" onkeyup="formatInputRupiah(this)" class="w-full p-2.5 border-2 border-emerald-300 rounded-xl text-xs font-black text-emerald-800 focus:outline-emerald-500 bg-emerald-50/30" placeholder="Rp">
            </div>

            <button type="button" onclick="hitungHPP()" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-black text-xs p-3 rounded-xl shadow-md transition-all active:scale-95 mt-3 cursor-pointer btn-press">
                🧮 Hitung HPP & Margin
            </button>

            <div id="hasilHPP" class="hidden flex-col gap-2 mt-4 p-4 bg-white border border-amber-200 rounded-2xl shadow-sm">
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">HPP Pokok / Bungkus:</span>
                    <span class="text-base font-black text-red-600" id="resHPP">Rp 0</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Laba Bersih / Bungkus:</span>
                    <span class="text-base font-black text-emerald-600" id="resMargin">Rp 0 (0%)</span>
                </div>
                <div class="bg-blue-50/70 border-l-4 border-blue-500 p-3 rounded-r-xl text-[10px] text-blue-900 font-medium leading-relaxed mt-1" id="resRekomendasi"></div>
            </div>
        </div>
    </div>
</div>
