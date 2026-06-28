<!-- KrispiKas - Modal Arsip & Download -->
<div id="modalArsip" class="fixed inset-0 z-[80] hidden bg-black/60 items-center justify-center p-4 backdrop-blur-sm modal-overlay">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl relative p-5 space-y-4 m-2 modal-content">
        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
            <h3 class="text-sm font-black text-purple-700 uppercase tracking-wider">📂 Export & Download Data</h3>
            <button type="button" onclick="closeModal('modalArsip')" class="text-gray-400 text-xl font-bold cursor-pointer">✕</button>
        </div>
        
        <!-- Download Bulan Berjalan -->
        <div class="space-y-2 bg-purple-50/50 p-3 rounded-2xl border border-purple-100">
            <p class="text-[10px] font-black text-purple-800 uppercase tracking-wider">📄 Dokumen Bulan Ini Berjalan</p>
            <div class="grid grid-cols-1 gap-2">
                <button type="button" onclick="downloadCSVBulanIni()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold p-3 rounded-xl text-xs flex justify-center items-center gap-2 cursor-pointer btn-press shadow-sm">⬇️ Download Excel (CSV)</button>
            </div>
        </div>

        <!-- Daftar Arsip Bulan Sebelumnya -->
        <div class="border-t border-gray-100 pt-3">
            <h4 class="font-black text-xs text-gray-700 mb-2">Daftar Rekap Buku Bulan Sebelumnya:</h4>
            <div id="listArsipVisual" class="space-y-3 max-h-48 overflow-y-auto no-scrollbar"></div>
        </div>
    </div>
</div>
