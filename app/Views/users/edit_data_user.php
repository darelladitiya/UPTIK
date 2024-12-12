<?= $this->extend('backend/layout/pages-layout'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h2 class="mb-3">Form Edit Data User</h2>
            <form action="<?= base_url('admin/update_data_user/' . $user['id_user']) ?>" method="POST">
                <?php $validation = \Config\Services::validation(); ?>


                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Kode Lengkap</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_user" name="nama_user" value="<?= $user['nama_user']; ?>">
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
                        <input type="text" class="form-control" id="username" name="username" value="<?= $user['username']; ?>">
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
                        <input type="text" class="form-control" id="password" name="password" value="<?= $user['password']; ?>">
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
                        <input type="text" class="form-control" id="email" name="email" value="<?= $user['email']; ?>">
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
                        <select name="status" id="status" class="form-control">
                            <?php foreach ($status as $st) : ?>
                                <option value="<?= $st ?>" <?php if ($user['status'] === $st) echo 'selected'; ?>>
                                    <?= $st ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>



                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">No Telp</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?= $user['no_telp']; ?>">
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
                            <?php foreach ($prodi as $item) : ?>
                                <option value="<?= $item['id_prodi'] ?>" <?php if ($user && $item['id_prodi'] == $user['id_prodi']) : ?>selected<?php endif; ?>>
                                    <?= $item['nama_prodi'] ?> <?= $item['program'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>

                    </div>
                </div>


                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda yakin ingin mengedit data ini?');">Simpan Data</button>
            </form>
        </div>
    </div>

    <?= $this->endSection(); ?>