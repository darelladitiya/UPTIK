<?= $this->extend('backend/layout/pages-layout'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h2 class="mb-3">Form Tambah Data Unit</h2>
            <form action="/admin/save_data_unit" method="POST">
                <?php $validation = \Config\Services::validation(); ?>

                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Kode Unit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kode_unit" name="kode_unit">
                        <?php if ($validation->getError('kode_unit')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('kode_unit'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Nama Unit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_unit" name="nama_unit">
                        <?php if ($validation->getError('nama_unit')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('nama_unit'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Tambah Data</button>
            </form>
        </div>
    </div>

    <?= $this->endSection(); ?>