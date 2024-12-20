<?= $this->extend('backend/layout/display-layout'); ?>

<?= $this->section('content'); ?>

<!-- breadcrumb-section -->
<div class="breadcrumb-section1 breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div id="jadwal-container">
    <?php if (isset($message)): ?>
        <h4 class="mt-3">
            <marquee behavior="scroll" direction="left">
                <?= $message; ?>
            </marquee>
        </h4>
    <?php endif; ?>
    <?php if (isset($jadwalData)): ?>
        <h4 class="mt-3">
            <marquee behavior="scroll" direction="left">
                <?php foreach ($jadwalData as $a): ?>
                    Ruangan:
                    <?= $a['nama_ruangan']; ?> sedang dipakai oleh
                    Dosen:
                    <?= $a['nama_dosen']; ?>
                    Kelas:
                    <?= $a['kelas']; ?>
                    Prodi:
                    <?= $a['nama_prodi']; ?>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php endforeach; ?>
            </marquee>
        </h4>
    <?php endif; ?>
</div>
<!-- end breadcrumb section -->


<div id="myCarousel" class="carousel slide row" data-bs-ride="carousel" data-bs-interval="9000">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="3"></li>
        <li data-bs-target="#myCarousel" data-bs-slide-to="4"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">

        <div class="carousel-item active">
            <?= $this->include('display/jadwal/jadwalReguler', ['hari' => 'Senin']); ?>
        </div>

        <div class="carousel-item">
            <?= $this->include('display/jadwal/jadwalSelasa', ['hari' => 'Selasa']); ?>

        </div>

        <div class="carousel-item">
            <?= $this->include('display/jadwal/jadwalRabu', ['hari' => 'Rabu']); ?>

        </div>

        <div class="carousel-item">
            <?= $this->include('display/jadwal/jadwalKamis', ['hari' => 'Kamis']); ?>

        </div>

        <div class="carousel-item">
            <?= $this->include('display/jadwal/jadwalJumat', ['hari' => 'Jumat']); ?>
        </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<script>
    // Definisikan fungsi untuk memperbarui jadwal
    function updateJadwal() {
        fetch('<?= route_to('display.fetchUpdatedJadwal'); ?>')
            .then(response => response.json())
            .then(data => {
                // Buat variabel untuk menyimpan konten jadwal baru
                let jadwalContent = '';

                // Cek apakah ada pesan
                if (data.message) {
                    jadwalContent += '<h4 class="mt-3"><marquee behavior="scroll" direction="left">';
                    jadwalContent += data.message;
                    jadwalContent += '</marquee></h4>';
                }

                // Cek apakah ada jadwalData
                if (data.jadwalData && data.jadwalData.length > 0) {
                    jadwalContent += '<h4 class="mt-3"><marquee behavior="scroll" direction="left">';
                    data.jadwalData.forEach(a => {
                        jadwalContent += 'Ruangan: ' + a.nama_ruangan + ' Sedang di pakai oleh ';
                        jadwalContent += 'Dosen : ' + a.nama_dosen + ' ';
                        jadwalContent += 'Kelas: ' + a.kelas + ' ';
                        jadwalContent += 'Prodi: ' + a.nama_prodi + ' ';
                        jadwalContent += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    });
                    jadwalContent += '</marquee></h4>';
                }

                // Perbarui konten dalam elemen #jadwal-container
                document.getElementById('jadwal-container').innerHTML = jadwalContent;
            })
            .catch(error => console.error('Error:', error));
    }

    // Panggil fungsi updateJadwal secara berkala setiap 60 detik
    setInterval(updateJadwal, 180000); // 60000 milidetik = 60 detik
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

<?= $this->endSection(); ?>