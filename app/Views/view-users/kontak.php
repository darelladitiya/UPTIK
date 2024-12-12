<?php $this->extend('backend/layout/user-layout') ?>

<?= $this->section('content'); ?>
<!-- hero area -->
<div class="swal" data-swal="<?= session('success'); ?>"></div>

<div class="hero-area hero-bg">
    <div class="container">
        <div class="row">
            <div class="text-center">
                <div class="hero-text">
                    <div class="hero-text-tablecell">
                        <p class="subtitle">UPA-TIK</p>
                        <h1 class="subtitle">Politeknik Negeri Bandung</h1>
                        <div class="hero-btns">
                            <a href="<?= route_to('user.galeri'); ?>" class="bordered-btn">Galeri</a>
                            <a href="<?= route_to('user.kontak'); ?>" class="bordered-btn">Kontak Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end hero area -->
<div class="container6">
<h2 class="my-5">Kontak</h2>
</div>
<center>
    <div class="containerkk">
    <div class="containerk">
        <div class="headingk">Kontak Kami</div>
        <form action="<?= base_url('user/save_data_kritik') ?>" method="POST" class="form">
        <?php $validation = \Config\Services::validation(); ?>
            
            <input required="" class="input" type="text" name="nama" id="nama" placeholder="Nama Lengkap">
            <?php if ($validation->getError('nama')): ?>
                    <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                        <?= $validation->getError('nama'); ?>
                    </div>
                <?php endif; ?>
            <input required="" class="input" type="text" name="email" id="email" placeholder="Email">
            <?php if ($validation->getError('email')): ?>
                    <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                        <?= $validation->getError('email'); ?>
                    </div>
                <?php endif; ?>
            <input required="" class="input3" type="text" name="komentar" id="komentar" placeholder="Komentar">
            <?php if ($validation->getError('komentar')): ?>
                    <div class="d-block text-danger " style="margin-top:5px;margin-left:5px;">
                        <?= $validation->getError('komentar'); ?>
                    </div>
                <?php endif; ?>

            <input class="login-button" type="submit">

        </form>        
    </div>
    </div>
</center>




<?= $this->endSection(); ?>