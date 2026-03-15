<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Barang | Inventaris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 40px; background-color: #f8f9fa; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        label { font-weight: bold; display: block; margin-top: 15px; color: #555; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        input[readonly] { background-color: #e9ecef; color: #495057; cursor: not-allowed; border: 1px solid #ced4da; }
        .btn-update { background-color: #4e73df; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 20px; }
        .back-link { text-decoration: none; color: #4e73df; font-size: 14px; }
    </style>
</head>
<body>

<div class="card">
    <a href="<?= base_url('barang') ?>" class="back-link">← Kembali</a>
    <h2 style="border-bottom: 2px solid #4e73df; padding-bottom: 10px;">Edit Barang</h2>

    <form method="post" action="<?= base_url('barang/update/'.$row['id']) ?>">
        <?= csrf_field() ?>
        
        <label>Kode Barang (Otomatis)</label>
        <input type="text" name="kode_barang" value="<?= $row['kode_barang'] ?>" readonly>

        <label>Nama Barang</label>
        <input name="nama_barang" value="<?= esc($row['nama_barang']) ?>" required>

        <label>Kategori</label>
        <input name="kategori" value="<?= esc($row['kategori']) ?>">

        <label>Lokasi</label>
        <select name="lokasi_id">
            <?php foreach ($list_lokasi as $l): ?>
                <option value="<?= $l['id'] ?>" <?= ($row['lokasi_id'] == $l['id']) ? 'selected' : '' ?>>
                    <?= esc($l['nama_lokasi']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Kondisi</label>
        <select name="kondisi">
            <option value="baik" <?= ($row['kondisi'] == 'baik') ? 'selected' : '' ?>>Baik</option>
            <option value="rusak_ringan" <?= ($row['kondisi'] == 'rusak_ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
            <option value="rusak_berat" <?= ($row['kondisi'] == 'rusak_berat') ? 'selected' : '' ?>>Rusak Berat</option>
        </select>

        <label>Stok</label>
        <input type="number" name="stok" value="<?= $row['stok'] ?>" required>

        <button type="submit" class="btn-update">Update Data</button>
    </form>
</div>

</body>
</html>