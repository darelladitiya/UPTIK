<?= $this->extend('backend/layout/pages-layout'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h2 class="mb-3">Form Tambah Data Jurusan</h2>
            <form action="/admin/save_data_jurusan" method="POST">
                <?php $validation = \Config\Services::validation(); ?>
                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Jurusan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= ($validation->hasError('nama_jurusan')) ? 'is-invalid' : '' ?>" id="nama_jurusan" name="nama_jurusan" value="<?= old('nama_jurusan') ?>">
                        <?php if ($validation->getError('nama_jurusan')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('nama_jurusan'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Data</button>
            </form>
        </div>
    </div>

    <?= $this->endSection(); ?>