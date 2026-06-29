/**
 * KrispiKas - DSS (Decision Support System) Module
 * 
 * Statistik, grafik Chart.js, dan analisis bisnis sederhana.
 * FIX: Menggunakan elemen dssTotalMasuk & dssTotalKeluar yang sudah ditambahkan di modal_dss.php
 */

// ============================================
// BUKA DASHBOARD STATISTIK
// ============================================

function bukaStatistikDSS() {
    let tMasuk = 0, tKeluar = 0, tKonsinyasi = 0, tLangsung = 0;

    globalState.listBukuKas.forEach(i => {
        tKeluar += parseInt(i.kredit) || 0;
        if ((parseInt(i.debet) || 0) > 0 && !i.ket.toLowerCase().includes('sisa saldo')) {
            tMasuk += parseInt(i.debet);
            if (i.ket.includes('[Titip]')) tKonsinyasi += parseInt(i.debet);
            else if (i.ket.includes('[Lsg]')) tLangsung += parseInt(i.debet);
        }
    });

    // Tampilkan modal terlebih dahulu agar canvas eksis di DOM
    openModal('modalDSS');

    // Delay rendering chart agar canvas terbaca oleh Chart.js
    setTimeout(() => {
        // Destroy chart lama
        if (arrChartsGlobal.length > 0) {
            arrChartsGlobal.forEach(c => c.destroy());
            arrChartsGlobal = [];
        }

        // [FIX] Update elemen total masuk & keluar
        document.getElementById('dssTotalMasuk').innerText = 'Rp ' + tMasuk.toLocaleString('id-ID');
        document.getElementById('dssTotalKeluar').innerText = 'Rp ' + tKeluar.toLocaleString('id-ID');

        // ---- 1. Grafik Laba/Rugi ----
        const canvasLaba = document.getElementById('canvasLabaRugi');
        if (canvasLaba) {
            const ctxLaba = canvasLaba.getContext('2d');
            const chartLaba = new Chart(ctxLaba, {
                type: 'bar',
                data: {
                    labels: ['Masuk (Omset)', 'Keluar (Biaya)'],
                    datasets: [{
                        data: [tMasuk, tKeluar],
                        backgroundColor: ['rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
            arrChartsGlobal.push(chartLaba);
        }

        // Analisis AI Laba/Rugi
        document.getElementById('aiLabaRugi').innerText = tMasuk > tKeluar
            ? '✅ Margin Sehat: Omset murni bulan ini sudah menutupi total biaya operasional.'
            : '⚠️ Defisit: Pengeluaran bulan ini melebihi omset murni. Cek kembali riwayat pembelian bahan.';

        // ---- 2. Grafik Rasio Pendapatan ----
        const canvasMetode = document.getElementById('canvasMetodeJual');
        if (canvasMetode) {
            const ctxMetode = canvasMetode.getContext('2d');
            const chartMetode = new Chart(ctxMetode, {
                type: 'doughnut',
                data: {
                    labels: ['Titip Warung', 'Bayar Langsung'],
                    datasets: [{
                        data: [tKonsinyasi, tLangsung],
                        backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { font: { size: 9 } } }
                    }
                }
            });
            arrChartsGlobal.push(chartMetode);
        }

        // Analisis AI Metode Jual
        document.getElementById('aiMetodeJual').innerText = tKonsinyasi > tLangsung
            ? '💡 Titip warung mendominasi omset. Pastikan penagihan uang ke agen lancar agar cashflow tidak macet.'
            : '💡 Penjualan langsung lebih tinggi. Ini sangat baik untuk menjaga kelancaran uang tunai harian.';

        // ---- 3. Grafik Pemakaian Bahan ----
        const canvasBahan = document.getElementById('canvasBahanBaku');
        if (canvasBahan) {
            const ctxBahan = canvasBahan.getContext('2d');
            const chartBahan = new Chart(ctxBahan, {
                type: 'bar',
                data: {
                    labels: ['Kulit (Kg)', 'Minyak (Kg)', 'Gas (Tbg)'],
                    datasets: [{
                        label: 'Total Terpakai',
                        data: [
                            globalState.akumulasiPakai.mentah,
                            globalState.akumulasiPakai.minyak,
                            globalState.akumulasiPakai.gas
                        ],
                        backgroundColor: ['#f59e0b', '#14b8a6', '#f97316'],
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
            arrChartsGlobal.push(chartBahan);
        }

        // Analisis AI Bahan Baku
        let mentah = globalState.databaseStok['Kulit Mentah'] || 0;
        let minyak = globalState.databaseStok['Minyak'] || 0;
        let textAIBahan = `Total produksi ludes: ${globalState.akumulasiPakai.mentah}Kg kulit. `;

        if (mentah <= 15) {
            textAIBahan += '⚠️ ALERT: Sisa stok mentah gudang kritis (≤ 15Kg). Segera order ke supplier!';
        } else if (minyak <= 5) {
            textAIBahan += '⚠️ ALERT: Stok minyak goreng menipis (≤ 5Kg).';
        } else {
            textAIBahan += '✅ Sisa stok di gudang masih dalam batas aman untuk produksi harian.';
        }

        document.getElementById('aiBahanBaku').innerText = textAIBahan;

        // ---- 4. Grafik Trend Pendapatan ----
        const canvasTrend = document.getElementById('canvasTrendPendapatan');
        if (canvasTrend && globalState.revenueHistory && globalState.revenueHistory.length > 0) {
            const ctxTrend = canvasTrend.getContext('2d');
            
            const labelsTrend = globalState.revenueHistory.map(r => r.bulan);
            const dataTrend = globalState.revenueHistory.map(r => parseFloat(r.pendapatan) || 0);
            
            const chartTrend = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: labelsTrend,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: dataTrend,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000) + 'Jt';
                                    if (value >= 1000) return (value / 1000) + 'k';
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
            arrChartsGlobal.push(chartTrend);
            
            // Analisis AI Trend Pendapatan
            let textAITrend = 'Data pendapatan belum cukup untuk dianalisis.';
            if (dataTrend.length > 1) {
                const last = dataTrend[dataTrend.length - 1];
                const prev = dataTrend[dataTrend.length - 2];
                if (last > prev) {
                    textAITrend = '📈 Bagus! Pendapatan bulan ini mengalami KENAIKAN dibandingkan bulan sebelumnya.';
                } else if (last < prev) {
                    textAITrend = '📉 Perhatian: Pendapatan bulan ini MENURUN dari bulan sebelumnya. Gencarkan promosi!';
                } else {
                    textAITrend = '➡️ Pendapatan terpantau stabil dibandingkan bulan sebelumnya.';
                }
            } else if (dataTrend.length === 1) {
                textAITrend = '📊 Ini adalah rekaman bulan pertama Anda. Semangat kumpulkan omset!';
            }
            document.getElementById('aiTrendPendapatan').innerText = textAITrend;
        } else if (canvasTrend) {
            document.getElementById('aiTrendPendapatan').innerText = '📊 Belum ada data pendapatan yang tercatat.';
        }
    }, 50);
}

// ============================================
// KALKULATOR HPP & MARGIN
// ============================================

function hitungHPP() {
    const hargaKulit = cleanRupiah(document.getElementById('hppHargaKulit').value);
    const hasilBungkus = cleanNumber(document.getElementById('hppBungkus').value);
    const hargaMinyak = cleanRupiah(document.getElementById('hppHargaMinyak').value);
    const hargaGas = cleanRupiah(document.getElementById('hppHargaGas').value);
    const hargaPlastik = cleanRupiah(document.getElementById('hppPlastik').value);
    const hargaBumbu = cleanRupiah(document.getElementById('hppBumbu').value);
    const hargaJual = cleanRupiah(document.getElementById('hppJual').value);

    if (hasilBungkus <= 0 || hargaKulit <= 0) {
        showToast('Harga Kulit Mentah dan Hasil Bungkus wajib diisi!', '⚠️', 3000);
        return;
    }

    // Hitung Rasio Riil dari Gudang
    let totMentah = cleanNumber(globalState.akumulasiPakai.mentah) || 0;
    let totMinyak = cleanNumber(globalState.akumulasiPakai.minyak) || 0;
    let totGas = cleanNumber(globalState.akumulasiPakai.gas) || 0;

    // Default rasio jika data gudang masih kosong (Asumsi normal: 1Kg Kulit butuh 0.3Kg Minyak & 0.1 Tabung Gas)
    let rasioMinyak = totMentah > 0 ? (totMinyak / totMentah) : 0.3;
    let rasioGas = totMentah > 0 ? (totGas / totMentah) : 0.1;

    // Biaya proporsional per 1 Kg adonan kulit
    let costMinyakPerKg = rasioMinyak * hargaMinyak;
    let costGasPerKg = rasioGas * hargaGas;
    
    // Total Biaya untuk 1 Kg adonan utuh
    let totalBiaya1Kg = hargaKulit + costMinyakPerKg + costGasPerKg + hargaBumbu;

    // HPP per 1 bungkus
    let hppIsi = totalBiaya1Kg / hasilBungkus;
    let hppFinal = Math.round(hppIsi + hargaPlastik);

    let labaRp = hargaJual - hppFinal;
    let marginPct = hargaJual > 0 ? Math.round((labaRp / hargaJual) * 100) : 0;

    // Tampilkan Hasil
    document.getElementById('resHPP').innerText = 'Rp ' + hppFinal.toLocaleString('id-ID');
    
    const elMargin = document.getElementById('resMargin');
    elMargin.innerText = `Rp ${labaRp.toLocaleString('id-ID')} (${marginPct}%)`;
    elMargin.className = `text-base font-black ${labaRp > 0 ? 'text-emerald-600' : 'text-red-600'}`;

    // Analisis AI
    let aiText = '';
    if (labaRp < 0) {
        aiText = `⚠️ <b>RUGI BESAR!</b> Anda rugi Rp ${Math.abs(labaRp).toLocaleString('id-ID')} setiap menjual 1 bungkus. Harga jual terlalu murah! Pertimbangkan untuk menaikkan harga jual menjadi minimal Rp ${(hppFinal + 300).toLocaleString('id-ID')} atau kurangi isi kerupuk per bungkusnya!`;
    } else if (marginPct < 20) {
        aiText = `⚠️ <b>Margin Tipis (${marginPct}%)</b>. Keuntungan Rp ${labaRp.toLocaleString('id-ID')} per bungkus terlalu kecil untuk menutupi biaya tenaga kerja dan resiko kerupuk rusak. Idealnya margin kerupuk minimal 30%.`;
    } else if (marginPct >= 20 && marginPct <= 45) {
        aiText = `✅ <b>Margin Sehat (${marginPct}%)</b>. Harga jual sudah ideal dan masuk akal. Pertahankan efisiensi minyak di gudang.`;
    } else {
        aiText = `🌟 <b>Margin Sangat Bagus (${marginPct}%)</b>. Bisnis menguntungkan! Jaga kualitas rasa agar pelanggan tidak lari.`;
    }

    // Info tambahan rasio
    if (totMentah > 0) {
        aiText += `<br><br><span class="text-gray-500 text-[8px]">*Info: AI menggunakan rasio riil gudang Anda (1Kg Kulit menghabiskan ${rasioMinyak.toFixed(2)}Kg Minyak & ${rasioGas.toFixed(2)} Tbg Gas).</span>`;
    }

    document.getElementById('resRekomendasi').innerHTML = aiText;
    
    const resBox = document.getElementById('hasilHPP');
    resBox.classList.remove('hidden');
    resBox.classList.add('flex');
    
    // Auto-scroll ke bawah
    setTimeout(() => {
        const modal = document.getElementById('modalDSS').querySelector('.modal-content');
        modal.scrollTo({ top: modal.scrollHeight, behavior: 'smooth' });
    }, 100);
}
