<?= $this->extend('backend/layout/pages-layout'); ?>
<?= $this->section('content'); ?>
<!-- Page Content Here -->
<section class="homesection">
<div class="swal" data-swal="<?= session('success'); ?>"></div>

    <div class="container">
        <div class="rowh">
            <div class="headnar">
                <h1>Selamat <span>Datang</span></h1>
                <p class="p1">Selamat datang di web UPA TIK
                    <br>
                    Ini adalah web yang berguna untuk mengatur penjadwalan.
                </p>
            </div>
            <div class="logo">
                <img src="/backend/src/images/logop.png" alt="" />
            </div>
        </div>
    </div>
</section>


<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    const swalElement = document.querySelector('.swal'); // Mengambil elemen dengan kelas '.swal'
    const swalData = swalElement.dataset.swal; // Mengambil data dari atribut data HTML 'data-swal'

    if (swalData) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: swalData,
            showConfirmButton: false,
            timer: 1900
        });
    }
</script>
<?= $this->endSection(); ?>