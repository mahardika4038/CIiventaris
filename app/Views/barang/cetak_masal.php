<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Masal QR Code</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0; background-color: #fff; }
        
        /* Container untuk tampilan Grid */
        .grid-container {
            display: grid;
            /* Mengatur 4 kolom agar pas di kertas A4 */
            grid-template-columns: repeat(4, 1fr); 
            gap: 15px;
            padding: 20px;
        }

        /* Styling Kartu Label */
        .label-card {
            border: 1px dashed #bbb; /* Garis putus-putus sebagai panduan potong */
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            background: white;
            page-break-inside: avoid; /* Mencegah kartu terpotong saat ganti halaman */
        }

        .label-card img {
            width: 120px;
            height: 120px;
            margin: 5px 0;
        }

        .nama-barang { 
            font-weight: bold; 
            font-size: 11px; 
            margin-bottom: 5px; 
            text-transform: uppercase;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Maksimal 2 baris nama barang agar tidak berantakan */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .kode-barang { 
            font-family: 'Courier New', monospace; 
            font-size: 10px; 
            color: #333; 
            background: #f0f0f0;
            padding: 2px 5px;
            border-radius: 3px;
        }
        
        /* Kontrol Navigasi (Hanya muncul di layar) */
        .no-print { 
            background: #333; 
            color: white;
            padding: 15px; 
            border-bottom: 1px solid #000; 
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-print { background: #2ecc71; color: white; }
        .btn-close { background: #e74c3c; color: white; }

        /* Pengaturan Cetak */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .grid-container { 
                padding: 0; 
                gap: 10px; 
            }
            .label-card { 
                border: 1px solid #eee; /* Garis lebih tipis saat diprint */
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Sekarang</button>
        <button onclick="window.close()" class="btn btn-close">Tutup Halaman</button>
    </div>

    <div class="grid-container">
        <?php if (!empty($qrData)): ?>
            <?php foreach ($qrData as $qr): ?>
                <div class="label-card">
                    <div class="nama-barang"><?= esc($qr['nama']) ?></div>
                    <img src="<?= $qr['img'] ?>" alt="QR Code">
                    <div class="kode-barang"><?= esc($qr['kode']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Data barang tidak ditemukan.</p>
        <?php endif; ?>
    </div>

</body>
</html>