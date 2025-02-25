<?php
$ar_buah = ["Semangka", "Mangga", "Pisang", "Sirsak"];

// Perbaikan variabel array yang salah
echo "Buah ke-2 adalah $ar_buah[1]"; // Index dimulai dari 0, jadi buah ke-2 ada di index 1

echo "<br>Jumlah array: " . count($ar_buah);

echo '<br>Seluruh Buah <ol>';
foreach ($ar_buah as $buah) {
    echo '<li>' . $buah . '</li>'; // Perbaikan variabel $_buah yang tidak didefinisikan
}
echo '</ol>';

// Menambah elemen array
$ar_buah[] = "Nanas";

// Menghapus elemen array index 1 ("Mangga")
unset($ar_buah[1]);

// Menambahkan elemen dengan index 4
$ar_buah[4] = "Melon";

echo '<ul>';
foreach ($ar_buah as $ak => $av) {
    echo '<li>Index: ' . $ak . ' - Nama Buah: ' . $av . '</li>';
}
echo '</ul>';