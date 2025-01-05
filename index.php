<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tivicate-DKM Jamie Su'ada</title>
</head>
<body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <style>
/* Navbar */
.navbar {
    background-color: rgba(0, 0, 0, 0.7); /* Transparansi */
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    transition: all 0.3s ease-in-out;
}

.navbar .nav-link {
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    transition: color 0.3s;
}

/* Style untuk navbar */
.navbar .nav-item .nav-link {
    color: navy; /* Warna awal biru navy */
    text-decoration: none; /* Hilangkan garis bawah bawaan */
    position: relative; /* Untuk garis bawah */
    transition: color 0.3s ease, border 0.3s ease; /* Transisi lembut */
}

/* Hover effect */
.navbar .nav-item .nav-link:hover {
    color: gray; /* Warna abu-abu saat hover */
}

.navbar .nav-item .nav-link:hover::after {
    content: ""; /* Tambahkan garis bawah */
    position: absolute;
    bottom: -5px; /* Jarak garis bawah dengan teks */
    left: 0;
    width: 100%; /* Lebar penuh */
    height: 2px; /* Ketebalan garis */
    background-color: gray; /* Warna garis bawah */
}

/* Active state */
.navbar .nav-item .nav-link.active {
    color: navy; /* Tetap biru navy setelah diklik */
}

/* Hero Section */
.hero {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
    position: relative;
    border-radius: 10px; /* Membuat sudut melengkung */
    overflow: hidden; /* Untuk memastikan gambar tidak keluar dari area hero */
    padding: 0 20px; /* Tambahkan padding untuk layar kecil */
}

.hero-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Menjaga proporsi gambar */
    z-index: -1; /* Menempatkan gambar di belakang teks */
}

.hero-text {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.7); /* Warna hitam dengan transparansi */
    padding: 20px; /* Padding lebih besar */
    border-radius: 10px; /* Membuat sudut melengkung */
    max-width: 650px; /* Lebar lebih besar untuk proporsional */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan untuk efek 3D */
    text-align: center;
}

.hero-text h1 {
    font-family: 'Poppins', sans-serif; /* Font modern */
    font-size: 3rem; /* Ukuran besar untuk heading */
    line-height: 1.6; /* Jarak antar baris */
    color:rgb(255, 202, 118); /* Warna emas */
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Efek bayangan pada teks */
    font-weight: bold;
    background: linear-gradient(90deg, #FFD700, #FFC400, #FFB700, #FFD700); 
    -webkit-background-clip: text; 
    -webkit-text-fill-color: transparent;
}

.hero-text h3 {
    font-family: 'Arial', sans-serif; /* Font sederhana */
    font-size: 1.2rem; /* Ukuran lebih kecil untuk paragraf */
    line-height: 1.6; /* Jarak antar baris */
    color: #ecf0f1; /* Warna abu-abu terang */
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5); /* Efek bayangan lembut */
    text-align: justify; /* Meratakan teks ke kiri dan kanan */
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

/* Media Query untuk layar kecil */
@media (max-width: 768px) {
    .hero-text {
        max-width: 90%; /* Kurangi lebar teks untuk layar kecil */
        padding: 15px; /* Sesuaikan padding */
    }

    .hero-text h1 {
        font-size: 2rem; /* Ukuran heading lebih kecil */
    }

    .hero-text h3 {
        font-size: 1rem; /* Ukuran teks deskripsi lebih kecil */
    }
}

/* Tentang Masjid */
.TentangMasjid {
    background: linear-gradient(to bottom, #f9f9f9, #e9ecef);
    padding: 50px 20px;
    border-radius: 10px;
    margin-top: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.TentangMasjid h2 {
    font-family: 'Open Sans', sans-serif;
    color: #333;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.TentangMasjid p {
    font-family: 'Open Sans', sans-serif;
    color: #555;
    line-height: 1.8;
    margin-bottom: 15px;
}

.carousel-control-prev,
.carousel-control-next {
    background-color: rgba(0, 0, 0, 0.5); 
}

.carousel-inner{
    font-family: 'Lucida Sans Unicode', Verdana, sans-serif
}

/* Acara Section */
.acara {
    margin-top: 50px;
    padding: 50px 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.acara h2 {
    font-family: 'Open Sans', sans-serif;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.acara-item {
    display: flex;
    justify-content: center;
}

.acara-box {
    background: linear-gradient(to bottom, #ffffff, #e9ecef);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 15px;
    max-width: 700px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: inline-block; /* Menjaga ukuran sesuai konten */
}

.acara-box:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.acara-date {
    font-size: 20px;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 10px;
}

.acara-box h3 {
    font-family: 'Open Sans', sans-serif;
    color: #333;
    font-size: 20px;
    margin-bottom: 10px;
}

.acara-box p {
    font-family: 'Open Sans', sans-serif;
    color: #555;
    line-height: 1.6;
}

.map py-5{
  font-family: times 
}
/*Contact */
.contact-info {
    background: linear-gradient(to right, #007bff, #0056b3);
    color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.contact-info h4 {
    font-family: 'Open Sans', sans-serif;
    font-size: 20px;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.contact-info p {
    font-family: 'Open Sans', sans-serif;
    margin: 10px 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.contact-info i {
    color: #ffcc00;
    font-size: 20px;
}
  </style>
</head>
<body>
  
  <!-- Navbar Section -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container d-flex align-items-center">
      <!-- Logo di kiri -->
      <a class="navbar-brand">
        <img src="./images/dkm.jpeg" alt="Logo" width="150" class="d-inline-block align-text-top">
      </a>
      <!-- Tombol hamburger untuk tampilan responsif -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Menu navigasi -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link fs-5" aria-current="page" href="#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  fs-5" href="#tentang">Tentang</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link fs-5" href="#sample">Sampel</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link fs-5" href="#acara">Acara</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link fs-5" href="#contact">Kontak</a>
          </li>    
        </ul>
        <!-- Form tombol login -->
        <form class="d-flex align-items-center ms-2">        
          <button class="btn btn-primary ms-2 btn-lg" type="button"  onclick="window.location.href='login.php'">Login</button>
        </form>
      </div>
    </div>
  </nav>
  <br><br><br><br>

  <!-- Hero section -->
  <div class="hero" id="home">
    <img src="./images/imagehead.jpeg" alt="hero image" class="hero-image">
    <div class="hero-text">
      <h1>MASJID JAMIE SU'ADA</h1>
      <h2>Ciasem, Subang</h2>
      <br>
      <h3>Masjid Jami Su'ada tidak hanya menjadi tempat ibadah, tetapi juga mendukung kegiatan pendidikan, 
          sosial, dan keagamaan. Setiap acara diselenggarakan dengan semangat dan profesionalisme, dimana 
          peserta mendapatkan sertifikat berkualitas tinggi sebagai penghargaan atas kontribusi dan partisipasi mereka.</h3>
    </div>
  </div>
  <!-- Tentang Section -->
  <div class="container p-5 TentangMasjid" id="tentang">
    <h2 class="text-center fw-bold mb-4">Tentang Masjid Jamie Su'ada</h2>
    <p class="text-center fs-5">
      Masjid Jamie Su'ada adalah salah satu masjid bersejarah yang terletak di pusat kota. 
      Masjid ini tidak hanya menjadi tempat ibadah, tetapi juga pusat kegiatan sosial, 
      pendidikan, dan budaya bagi masyarakat sekitar.
    </p>
    <p class="text-center fs-5">
      Didirikan pada tahun 2001, Masjid Jamie Su'ada telah menjadi simbol keberagaman 
      dan solidaritas umat Muslim. Dengan berbagai acara seperti pengajian, dan perlombaan, 
      masjid ini berkontribusi aktif dalam pembangunan karakter masyarakat.
    </p>
  </div>
  <!-- Sample Section -->
  <div class="container w-75 my-5" id="sample">
    <div id="carouselExampleIndicators" class="carousel slide text-center" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <br>
          <img src="./images/sampel1.png" class="d-block mx-auto" style="width: 45%;" alt="sampleSertifikat">
        </div>
        <div class="carousel-item">
          <br>
          <img src="./images/sampel2.png" class="d-block mx-auto" style="width: 45%;" alt="sampleSertifikat">
        </div>
        <div class="carousel-item">
          <br>
          <img src="./images/sampel3.png" class="d-block mx-auto" style="width: 45%;" alt="sampleSertifikat">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
  <br>
  <!-- Acara Section -->
  <div class="container" id="acara">
    <div class="row">
      <div class="col-md-4 col-sm-6 col-12 acara-item mb-4">
        <div class="acara-box text-center">
          <div class="acara-date">17 Juni 2024</div>
          <img src="./images/kurban.jpeg" alt="sampleimage" class="img-fluid mb-3">
          <h3>Penyembelihan Hewan Kurban</h3>
          <p> Masjid Jamie Su'ada akan mengadakan acara penyembelihan hewan kurban pada Hari Raya Idul Adha. 
              Semua jamaah diundang untuk menyaksikan dan ikut serta dalam kegiatan ini, 
              yang dimulai pukul 07.00 WIB.
          </p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-12 acara-item mb-4">
        <div class="acara-box text-center">
          <div class="acara-date">7 Agustus 2023</div>
          <img src="./images/sample-perlombaan.jpeg" alt="sampleimage" class="img-fluid mb-3">
          <h3>Lomba Kaligrafi</h3>
          <p> Lomba kaligrafi untuk anak-anak dan remaja dengan berbagai tema di setiap kesempatannya.
              Kegiatan ini bertujuan untuk mengasah kreativitas dan kecintaan terhadap seni kaligrafi Islami.
          </p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-12 acara-item mb-4">
        <div class="acara-box text-center">
          <div class="acara-date">19 Februari 2023</div>
          <img src="./images/kajian.jpeg" alt="sampleimage" class="img-fluid mb-3">
          <h3>Kajian Isro Mi'raj</h3>
          <p> Mengundang jamaah untuk menghadiri kajian dalam rangka memperingati Isra Mi'raj 
              Nabi Muhammad SAW. Kajian ini akan membahas hikmah dan nilai-nilai spiritual dari perjalanan tersebut.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact and Maps Section -->
<div class="container p-5" id="contact">
  <div class="row d-flex align-items-stretch">
    <!-- Contact Info -->
    <div class="col-md-5 d-flex">
      <div class="p-4 border rounded bg-light contact-info w-70">
        <h4 class="mb-4 text-center">Informasi Kontak</h4>
        <hr>
        <p><i class="fas fa-map-marker-alt"></i> Jl. Raya Pantura, Ciasem Hilir, Kecamatan Ciasem, Kabupaten Subang, 41256</p>
        <br>
        <p><i class="fas fa-phone"></i> +62 855-2440-8684</p>
        <br>
        <p><i class="fab fa-instagram"></i> irmadaofficial</p>
      </div>
    </div>

    <!-- Maps -->
    <div class="col-md-7 d-flex">
      <div class="map w-100">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31764.398872208166!2d107.6917062!3d-6.3181066!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69423efe88f893%3A0xff0e1ee4fd287ef5!2sMasjid%20Jami%20Su'ada!5e0!3m2!1sid!2sid!4v1698664086956!5m2!1sid!2sid" 
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>
  </div> 

  <!-- Footer Section -->
  <footer class="bg-light text-center py-3">
    <div class="container">
      <p>&copy; 2024 DKM Su'ada. Semua Hak Dilindungi.</p>
    </div>
  </footer>

</body>
</html>