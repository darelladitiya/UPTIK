<?= $this->extend('backend/layout/pages-layout'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h2 class="mb-3">Form Tambah Data User</h2>
            <form action="<?= base_url('admin/save_data_user') ?>" method="POST">
                <?php $validation = \Config\Services::validation(); ?>


                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Kode Lengkap</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_user" name="nama_user">
                        <?php if ($validation->getError('nama_user')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('nama_user'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Nama Pengguna</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username">
                        <?php if ($validation->getError('username')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('username'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="password" name="password">
                        <?php if ($validation->getError('password')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('password'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">E-mail</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="email" name="email">
                        <?php if ($validation->getError('email')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('email'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="status" name="status">
                            <option value="">Pilih Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>
                        <?php if ($validation->getError('status')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('status'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">No Telp</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_telp" name="no_telp">
                        <?php if ($validation->getError('no_telp')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('no_telp'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Program Studi</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="id_prodi" name="id_prodi">
                            <option value="">Pilih Program Studi</option>
                            <?php foreach ($prodi as $item) : ?>
                                <option value="<?= $item['id_prodi'] ?>" <?php if ($user && $item['id_prodi'] == $user['id_prodi']) : ?>selected<?php endif; ?>>
                                    <?= $item['nama_prodi'] ?> <?= $item['program'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->getError('id_prodi')) : ?>
                            <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                                <?= $validation->getError('id_prodi'); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>



                <button type="submit" class="btn btn-primary">Tambah Data</button>
            </form>
        </div>
    </div>

    <?= $this->endSection(); ?>