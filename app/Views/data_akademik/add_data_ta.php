<?= $this->extend('backend/layout/pages-layout'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h2 class="mb-3">Form Tambah Data Tahun Ajaran</h2>
            <form action="/admin/save_data_ta" method="POST">
                <?php if (!empty(session()->getFlashdata('success'))) : ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('succes'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <spance aria-hidden="true">&times;</spance>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (!empty(session()->getFlashdata('fail'))) : ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('fail'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <spance aria-hidden="true">&times;</spance>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="thn_awal">Tahun Awal</label>
                    <input type="text" class="form-control <?= ($validation->hasError('thn_awal')) ? 'is-invalid' : '' ?>" id="thn_awal" name="thn_awal">
                    <!-- Tampilkan pesan kesalahan jika ada -->
                    <div class="invalid-feedback"><?= $validation->getError('thn_awal') ?></div>
                </div>
                <div class="form-group">
                    <label for="thn_akhir">Tahun Akhir</label>
                    <input type="text" class="form-control <?= ($validation->hasError('thn_akhir')) ? 'is-invalid' : '' ?>" id="thn_akhir" name="thn_akhir">
                    <!-- Tampilkan pesan kesalahan jika ada -->
                    <div class="invalid-feedback"><?= $validation->getError('thn_akhir') ?></div>
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <input type="text" class="form-control <?= ($validation->hasError('semester')) ? 'is-invalid' : '' ?>" id="semester" name="semester">
                    <!-- Tampilkan pesan kesalahan jika ada -->
                    <div class="invalid-feedback"><?= $validation->getError('semester') ?></div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Data</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>