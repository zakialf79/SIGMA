/**
 * KrispiKas - Arsip & Download Module
 * 
 * Mengelola arsip bulanan, export CSV dan PDF.
 */

// ============================================
// BUKA MODAL ARSIP
// ============================================

function bukaArsipBulanan() {
    let html = '';
    globalState.arsipBulanList.forEach(bulan => {
        html += `<div class="bg-purple-50 border border-purple-100 p-3 rounded-2xl flex flex-col gap-2 shadow-sm">
            <span class="font-black text-purple-900 text-xs">${bulan}</span>
            <div class="grid grid-cols-1 gap-1.5">
                <button onclick="downloadCSVData('${bulan}')" class="w-full bg-emerald-600 text-white px-2 py-2 rounded-xl text-[10px] font-bold shadow text-center cursor-pointer btn-press flex justify-center items-center gap-1">⬇️ Download Excel (CSV)</button>
            </div>
        </div>`;
    });
    document.getElementById('listArsipVisual').innerHTML = html || '<p class="text-center text-gray-400 italic py-2">Belum ada arsip tersimpan.</p>';
    openModal('modalArsip');
}

// ============================================
// DOWNLOAD CSV
// ============================================

function downloadCSVBulanIni() {
    generateCSV(globalState.listBukuKas, 'Buku_Kas_Bulan_Berjalan');
}

async function downloadCSVData(bulanKunci) {
    try {
        const res = await fetch(`${API_URL}?action=get_arsip_bulan&bulan=${encodeURIComponent(bulanKunci)}`);
        const dataArsip = await res.json();
        generateCSV(dataArsip, `Arsip_Kas_${bulanKunci}`);
    } catch (err) {
        showToast('Gagal mengambil data arsip!', '❌');
    }
}

function generateCSV(dataArray, filename) {
    let csvRows = [];
    csvRows.push(['Tanggal', 'Keterangan', 'Debet (Masuk)', 'Kredit (Keluar)', 'Saldo']);

    let runningSaldo = 0;
    let totalDebet = 0;
    let totalKredit = 0;

    dataArray.forEach((row, index) => {
        let debet = parseFloat(row.debet) || 0;
        let kredit = parseFloat(row.kredit) || 0;
        runningSaldo += (debet - kredit);
        totalDebet += debet;
        totalKredit += kredit;

        let rowIndex = index + 2; // Baris 1 adalah header, data mulai dari baris 2
        let formulaSaldo = (rowIndex === 2) 
            ? `"=C2-D2"` 
            : `"=E${rowIndex - 1}+C${rowIndex}-D${rowIndex}"`;

        csvRows.push([
            `"${row.tgl || ''}"`,
            `"${(row.ket || '').replace(/"/g, '""')}"`,
            debet,
            kredit,
            formulaSaldo
        ]);
    });

    let totalRowIndex = dataArray.length + 2;
    csvRows.push([
        '""', 
        '"TOTAL"', 
        `"=SUM(C2:C${totalRowIndex - 1})"`, 
        `"=SUM(D2:D${totalRowIndex - 1})"`, 
        `"=E${totalRowIndex - 1}"`
    ]);

    let csvString = '\uFEFF' + csvRows.map(e => e.join(';')).join('\r\n');
    let blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
    let url = URL.createObjectURL(blob);
    let link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', filename + '.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showToast(`${filename}.csv berhasil didownload!`, '📊');
}
