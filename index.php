<!doctype html>
<html>
<?php
$temp = [];
$json = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama    = $_POST['nama'];
  $nim     = $_POST['nim'];
  $prodi   = $_POST['prodi'];
  $kelamin = $_POST['kelamin'];
  $alamat  = $_POST['alamat'];
  $hobi    = isset($_POST['hobi']) ? $_POST['hobi'] : [];

  $temp = [
    'nama'    => $nama,
    'nim'     => $nim,
    'prodi'   => $prodi,
    'kelamin' => $kelamin,
    'alamat'  => $alamat,
    'hobi'    => $hobi
  ];

  $file = "data_mahasiswa.json";
  $existing = [];

  if (file_exists($file)) {
    $jsonData = file_get_contents($file);
    $existing = json_decode($jsonData, true) ?? [];
  }

  $existing[] = $temp;
  file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT));
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Biodata Mahasiswa</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #ff9800, #ff5722);
      color: #333;
    }
    .container {
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
    }
    .card {
      background: rgba(255,255,255,0.9);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 50px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.25);
      backdrop-filter: blur(6px);
    }
    /* khusus untuk Input Data Diri */
    .card-form {
      max-width: 500px;   /* kecilkan form */
      margin: 0 auto 50px auto; /* center + jarak bawah */
    }
    h1 {
      text-align: center;
      color: #e65100;
      margin-bottom: 20px;
    }
    form label {
      font-weight: bold;
      margin-bottom: 5px;
      display: block;
    }
    input[type="text"], textarea, select {
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      width: 100%;
    }
    /* ukuran custom */
    .input-small {
       width: 97% !important;   /* paksa jadi 60% */
  display: block;
  margin-bottom: 10px;
    }
    .input-medium {
      width: 100%;
    }
    .input-large {
      width: 97%;
    }
    input[type="radio"], input[type="checkbox"] {
      margin-right: 6px;
    }
    button {
      background: #e65100;
      color: white;
      border: none;
      padding: 10px 20px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #bf360c;
    }
    .search-box {
      display: flex;
      justify-content: center;
      margin-bottom: 15px;
    }
    .search-box input {
      flex: 1;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }
    .search-box button {
      margin-left: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    th {
      background: #ff7043;
      color: white;
    }
    tr:nth-child(even) {
      background: #fbe9e7;
    }
  </style>
</head>

<body>
  <div class="container">

    <!-- Input Data Diri -->
    <div class="card card-form">
      <h1>Form Biodata Mahasiswa</h1>
      <form action="" method="POST">
        <br>
        <label>Nama Lengkap:</label>
        <input type="text" name="nama" placeholder="Masukkan Nama" class="input-small" required>

        <label>NIM:</label>
        <input type="text" name="nim" placeholder="Masukkan NIM" class="input-small" required>

        <label>Program Studi:</label>
        <select name="prodi" class="input-medium" required>
          <option value="Informatika">Informatika</option>
          <option value="Sistem Informasi">Sistem Informasi</option>
          <option value="Teknik Elektro">Teknik Elektro</option>
        </select>

        <label>Jenis Kelamin:</label>
        <input type="radio" name="kelamin" value="Laki-laki" required> Laki-laki
        <input type="radio" name="kelamin" value="Perempuan" required> Perempuan

        <br><br>
        <label>Hobi:</label>
        <input type="checkbox" name="hobi[]" value="Membaca"> Membaca
        <input type="checkbox" name="hobi[]" value="Olahraga"> Olahraga
        <input type="checkbox" name="hobi[]" value="Menulis"> Menulis
        <input type="checkbox" name="hobi[]" value="Menggambar"> Menggambar

        <br><br>
        <label>Alamat:</label>
        <textarea name="alamat" placeholder="Masukkan Alamat Anda" rows="4" class="input-large" required></textarea>

        <button type="submit">Submit</button>
      </form>
    </div>

    <!-- Data Mahasiswa -->
    <div class="card">
      <?php
      $file = 'data_mahasiswa.json';
      $jsonData = file_exists($file) ? file_get_contents($file) : '';
      $data = json_decode($jsonData, true) ?? [];

      if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $keyword = strtolower(trim($_GET['search']));
        $filtered = [];

        foreach ($data as $mhs) {
          if (
            strpos(strtolower($mhs['nama']), $keyword) !== false ||
            strpos(strtolower($mhs['nim']), $keyword) !== false ||
            strpos(strtolower($mhs['prodi']), $keyword) !== false ||
            strpos(strtolower($mhs['kelamin']), $keyword) !== false ||
            strpos(strtolower($mhs['alamat']), $keyword) !== false
          ) {
            $filtered[] = $mhs;
            continue;
          }
          foreach ($mhs['hobi'] as $ho) {
            if (strpos(strtolower($ho), $keyword) !== false) {
              $filtered[] = $mhs;
              break;
            }
          }
        }
        $data = $filtered;
      }
      ?>

      <h1>Data Mahasiswa</h1>
      <form method="GET" action="" class="search-box">
        <input type="text" name="search" placeholder="Cari Mahasiswa..."
          value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Cari</button>
      </form>

      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>NIM</th>
            <th>Prodi</th>
            <th>Jenis Kelamin</th>
            <th>Hobi</th>
            <th>Alamat</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($data as $mhs) {
            echo "<tr>";
            echo "<td>{$mhs['nama']}</td>";
            echo "<td>{$mhs['nim']}</td>";
            echo "<td>{$mhs['prodi']}</td>";
            echo "<td>{$mhs['kelamin']}</td>";
            echo "<td>" . implode(", ", $mhs['hobi']) . "</td>";
            echo "<td>{$mhs['alamat']}</td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

  </div>
</body>
</html>
