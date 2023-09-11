<?php
function hitung_tagihan_awal($hargaSatuan, $jumlahPesanan)
{
    $tagihanAwal = $hargaSatuan * $jumlahPesanan;
    return $tagihanAwal;
}

$cabang = array('Majalengka', 'Bandung', 'Jakarta', 'Cirebon');
sort($cabang);

$hargaSatuan = 50000;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Nasi Kotak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Form Pemesanan Nasi Kotak</h3>
                        <img src="logo.jpg" class="card-img-top" alt="Logo Restoran" width="200" height="200">
                        <form action="index.php" method="post" id="formPemesanan">
                            <div class="mb-3">
                                <label for="cabang" class="form-label">Cabang:</label>
                                <select id="cabang" name="cabang" class="form-select">
                                    <option value="">- Pilih Cabang -</option>
                                    <?php
                                    foreach ($cabang as $item) {
                                        echo "<option value='$item'>$item</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Pelanggan:</label>
                                <input type="text" id="nama" name="nama" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="noHP" class="form-label">Nomor HP:</label>
                                <input type="number" id="noHP" name="noHP" class="form-control" maxlength="16">
                            </div>
                            <div class="mb-3">
                                <label for="jumlahPesanan" class="form-label">Jumlah Kotak:</label>
                                <input type="number" id="jumlahPesanan" name="jumlahPesanan" class="form-control"
                                    maxlength="4">
                            </div>
                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-primary btn-lg" type="submit" form="formPemesanan" value="Pesan"
                                    name="submit">Pesan</button>
                            </div>
                        </form>

                        <?php
                        if (isset($_POST['submit'])) {
                            $datapesanan = array(
                                'cabang' => $_POST['cabang'],
                                'nama' => $_POST['nama'],
                                'noHP' => $_POST['noHP'],
                                'jumlahPesanan' => $_POST['jumlahPesanan']
                            );

                            $berkas = "data.json";
                            $dataJson = json_encode($datapesanan, JSON_PRETTY_PRINT);
                            file_put_contents($berkas, $dataJson);

                            $dataJson = file_get_contents($berkas);
                            $datapesanan = json_decode($dataJson, true);

                            $tagihanAwal = hitung_tagihan_awal($hargaSatuan, $datapesanan['jumlahPesanan']);

                            $diskon = 0;

                            if ($tagihanAwal >= 1000000) {
                                $diskon = (5 / 100) * $tagihanAwal;
                                $tagihanAkhir = $tagihanAwal - $diskon;
                            } else {
                                $tagihanAkhir = $tagihanAwal;
                            }

                            echo "
                            <div class='row'>
                                  <div class='col-4'>Cabang</div>
                                  <div class='col-8'> : " . $datapesanan['cabang'] . "</div>
                               </div>

                               <div class='row'>
                                  <div class='col-4'>Nama Pelanggan</div>
                                  <div class='col-8'> : " . $datapesanan['nama'] . "</div>
                               </div>

                               <div class='row'>
                                  <div class='col-4'>No.HP</div>
                                  <div class='col-8'> : " . $datapesanan['noHP'] . "</div>
                               </div>

                               <div class='row'>
                                  <div class='col-4'>Jumlah Kotak</div>
                                  <div class='col-8'> : " . $datapesanan['jumlahPesanan'] . "</div>
                               </div>

                               <div class='row'>
                                  <div class='col-4'>Harga Satuan</div>
                                  <div class='col-8'> : Rp" . number_format($hargaSatuan, 0, ".", ".") . "</div>
                               </div>

                               <div class='row'>
                                  <div class='col-4'>Tagihan Awal</div>
                                  <div class='col-8'> : Rp" . number_format($tagihanAwal, 0, ".", ".") . "</div>
                               </div>

                               <div class='row'>
                                  <div class='col-4'>Diskon</div>
                                  <div class='col-8'> : Rp" . number_format($diskon, 0, ".", ".") . "</div>
                               </div>

                               <div class='row'>
                                  <div class='col-4'>Tagihan Akhir</div>
                                  <div class='col-8'> : Rp" . number_format($tagihanAkhir, 0, ".", ".") . "</div>
                               </div>
                            ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>