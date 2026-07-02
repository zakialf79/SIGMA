<?php
/**
 * KrispiKas - Kas Controller
 * 
 * Menangani API untuk inisialisasi data dan sinkronisasi state buku kas.
 */
class KasController
{
    /**
     * GET: Ambil semua data awal saat aplikasi dimuat.
     * Endpoint: ?action=get_init_data
     */
    public function getInitData(): void
    {
        try {
            $bukuKas       = new BukuKas();
            $gudangStok    = new GudangStok();
            $mitra         = new Mitra();
            $historiGudang = new HistoriGudang();
            $akumulasi     = new AkumulasiPakai();

            $mitraData = $mitra->getAll();

            echo json_encode([
                'listBukuKas'    => $bukuKas->getAktif(),
                'databaseStok'   => $gudangStok->getAll(),
                'agenKonsinyasi' => $mitraData['konsinyasi'],
                'agenLangsung'   => $mitraData['langsung'],
                'historiGudang'  => $historiGudang->getRecent(),
                'arsipBulanList' => $bukuKas->getArsipBulanList(),
                'akumulasiPakai' => $akumulasi->getLatest(),
                'revenueHistory' => $bukuKas->getRevenueHistory(),
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal memuat data: ' . $e->getMessage()]);
        }
    }

    /**
     * POST: Sinkronisasi seluruh state dari frontend ke database.
     * Endpoint: ?action=sync_all_state
     */
    public function syncAllState(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'));
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request body']);
            return;
        }

        $db = Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            // 1. Update stok gudang
            $gudangStok = new GudangStok();
            if (isset($data->databaseStok)) {
                $gudangStok->syncAll($data->databaseStok);
            }

            // 2. Simpan log histori gudang (jika ada entri baru)
            $histori = new HistoriGudang();
            if (isset($data->clearHistoriGudang) && $data->clearHistoriGudang === true) {
                $histori->clearAll();
            }
            if (!empty($data->newLogTeks)) {
                $histori->insert($data->newLogTeks);
            }

            // 3. Sinkronisasi buku kas bulan berjalan
            $bukuKas = new BukuKas();
            if (isset($data->listBukuKas)) {
                $bukuKas->syncBulk($data->listBukuKas);
            }

            // 4. Update akumulasi pemakaian bulanan
            if (isset($data->akumulasiPakai)) {
                $akumulasi = new AkumulasiPakai();
                $akumulasi->upsert(
                    $data->akumulasiPakai->mentah ?? 0,
                    $data->akumulasiPakai->minyak ?? 0,
                    $data->akumulasiPakai->gas ?? 0
                );
            }

            $db->commit();
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            $db->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET: Download Backup SQL Database (Untuk Migrasi Server)
     * Endpoint: ?action=backup_db
     */
    public function backupDb(): void
    {
        $db = Database::getInstance()->getConnection();
        $tables = ['buku_kas', 'gudang_stok', 'histori_gudang', 'akumulasi_pakai', 'mitra'];
        
        $sql = "-- Backup Database SIGMA\n";
        $sql .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $stmt = $db->query("SHOW CREATE TABLE `$table`");
            $row = $stmt->fetch(PDO::FETCH_NUM);
            $sql .= $row[1] . ";\n\n";

            $stmt = $db->query("SELECT * FROM `$table`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($rows) > 0) {
                $sql .= "INSERT INTO `$table` VALUES \n";
                $values = [];
                foreach ($rows as $r) {
                    $vals = array_map(function ($val) use ($db) {
                        return is_null($val) ? 'NULL' : $db->quote($val);
                    }, $r);
                    $values[] = "(" . implode(", ", $vals) . ")";
                }
                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="backup_sigma_' . date('Y-m-d') . '.sql"');
        echo $sql;
    }
}
