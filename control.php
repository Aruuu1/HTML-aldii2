<?php

// Proses form jika ada data yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $tugas = $_POST['tugas'];
    $quiz = $_POST['quiz'];
    $uts = $_POST['uts'];
    $uas = $_POST['uas'];

    // Menghitung nilai akhir
    $nilai_akhir = (0.2 * $tugas) + (0.2 * $quiz) + (0.3 * $uts) + (0.3 * $uas);

    // Menentukan grade
    if ($nilai_akhir >= 85) {
        $grade = 'A';
    } elseif ($nilai_akhir >= 70) {
        $grade = 'B';
    } elseif ($nilai_akhir >= 60) {
        $grade = 'C';
    } elseif ($nilai_akhir >= 40) {
        $grade = 'D';
    } else {
        $grade = 'E';
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO mahasiswa (nim, nama, tugas, quiz, uts, uas, nilai_akhir, grade) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddddds", $nim, $nama, $tugas, $quiz, $uts, $uas, $nilai_akhir, $grade);

    if ($stmt->execute()) {
        $message = "Data berhasil disimpan!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>