<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Form Nilai Mahasiswa</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, rgba(109,183,238,1) 0%, rgba(217,255,217,1) 100%);
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
            gap: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 70%;
            max-width: 850px; /* Perkecil ukuran */
        }

        form {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label {
            font-weight: 500;
            font-size: 1rem; /* Ukuran font lebih kecil */
            color: #333;
        }

        form input[type="number"],
        form input[type="text"] {
            padding: 8px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 0.9rem; /* Ukuran font lebih kecil */
        }

        form input[type="submit"] {
            padding: 8px;
            background-color: #6db7ee;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #558fc8;
        }

        .table-container {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 15px; /* Perkecil jarak antar elemen */
        }

        h3 {
            text-align: center;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table th,
        table td {
            padding: 10px; /* Perkecil padding */
            border: 1px solid #ccc;
            font-size: 0.85rem; /* Ukuran font lebih kecil */
        }

        table th {
            background-color: #f7f7f7;
            font-weight: 600;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>
    <div class="container">
        <form method="post" action="">
            <label for="nim">NIM:</label>
            <input type="number" id="nim" name="nim" required>

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="tugas">Tugas:</label>
            <input type="number" id="tugas" name="tugas" required>

            <label for="quiz">Quiz:</label>
            <input type="number" id="quiz" name="quiz" required>

            <label for="uts">UTS:</label>
            <input type="number" id="uts" name="uts" required>

            <label for="uas">UAS:</label>
            <input type="number" id="uas" name="uas" required>

            <input type="submit" value="Submit">
        </form>

        <div class="table-container">
            <h3>Hasil Nilai Mahasiswa</h3>
            <?php
            // koneksi ke database
            include("connect.php");

            // Pastikan koneksi ke database berhasil
            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }

            // Proses form saat disubmit
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nim = mysqli_real_escape_string($conn, $_POST['nim']);
                $nama = mysqli_real_escape_string($conn, $_POST['nama']);
                $tugas = mysqli_real_escape_string($conn, $_POST['tugas']);
                $quiz = mysqli_real_escape_string($conn, $_POST['quiz']);
                $uts = mysqli_real_escape_string($conn, $_POST['uts']);
                $uas = mysqli_real_escape_string($conn, $_POST['uas']);

                // Hitung nilai akhir (contoh: rata-rata)
                $nilai_akhir = ($tugas + $quiz + $uts + $uas) / 4;

                // Tentukan grade berdasarkan nilai akhir
                if ($nilai_akhir >= 85) {
                    $grade = 'A';
                } elseif ($nilai_akhir >= 70) {
                    $grade = 'B';
                } elseif ($nilai_akhir >= 55) {
                    $grade = 'C';
                } elseif ($nilai_akhir >= 40) {
                    $grade = 'D';
                } else {
                    $grade = 'E';
                }

                // Masukkan data ke database
                $sql = "INSERT INTO mahasiswa (nim, nama, tugas, quiz, uts, uas, nilai_akhir, grade) 
                        VALUES ('$nim', '$nama', '$tugas', '$quiz', '$uts', '$uas', '$nilai_akhir', '$grade')";

                if ($conn->query($sql) === TRUE) {
                    // Redirect setelah insert agar data tidak tersubmit ulang saat refresh
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            // Ambil data mahasiswa dari database
            $sql = "SELECT * FROM mahasiswa";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Tugas</th>
                            <th>Quiz</th>
                            <th>UTS</th>
                            <th>UAS</th>
                            <th>Nilai Akhir</th>
                            <th>Grade</th>
                        </tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['nim'] . "</td>
                            <td>" . $row['nama'] . "</td>
                            <td>" . $row['tugas'] . "</td>
                            <td>" . $row['quiz'] . "</td>
                            <td>" . $row['uts'] . "</td>
                            <td>" . $row['uas'] . "</td>
                            <td>" . $row['nilai_akhir'] . "</td>
                            <td>" . $row['grade'] . "</td>
                        </tr>";
                }

                echo "</table>";
            } else {
                echo "Belum ada data mahasiswa.";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
