<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #fff; }
        
        /* Layout Grid agar saat cetak massal tidak berantakan */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .label-container {
            border: 1px solid #000; 
            width: 180px; 
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            page-break-inside: avoid; /* Mencegah label terpotong antar halaman */
        }

        .qr-code img { 
            width: 130px; 
            height: 130px; 
            margin: 10px 0;
        }

        .nama-barang { 
            font-weight: bold; 
            font-size: 14px; 
            margin-bottom: 5px;
            text-transform: uppercase;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Batasi nama barang max 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .kode-barang { 
            font-size: 12px; 
            font-family: monospace;
            background: #eee;
            padding: 2px 5px;
        }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .label-container { border: 1px solid #ccc; } 
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center; background: #f4f4f4; padding: 15px; border-radius: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-weight: bold;">🖨️ Cetak Label Sekarang</button>
        <a href="<?= base_url('barang') ?>" style="margin-left: 10px; text-decoration: none; color: blue;">← Kembali ke Data Barang</a>
    </div>

    <div class="grid-container">
        <?php foreach ($rows as $row): ?>
            <div class="label-container">
                <div class="nama-barang"><?= esc($row['nama_barang']) ?></div>
                
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $row['kode_barang'] ?>" alt="QR Code">
                </div>
                
                <div class="kode-barang"><?= esc($row['kode_barang']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>