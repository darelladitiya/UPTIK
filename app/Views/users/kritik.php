<?= $this->extend('backend/layout/pages-layout'); ?>
<?= $this->section('content'); ?>




<body>
    <center>
        <h1 class="my-3"></h1>Kritik User
    </center>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr class="text-center">
                <th scope="col">No</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Nama Lengkap</th>
                <th scope="col">Email</th>
                <th scope="col">Komentar</th>


        <tbody>
            <?php $i = 1; ?>
            <?php
            // Ambil nilai parameter 'page_lab2' dari URL dan konversi ke integer
            $page = intval(request()->getVar('page_kritik'));

            // Jika parameter tidak ada, atur nilai default ke 1
            if ($page <= 0) {
                $page = 1;
            }

            // Hitung nilai $i
            $i = 1 + (10 * ($page - 1));
            ?>
            <?php foreach ($kritik as $user): ?>
                <tr class="text-center">
                    <td scope="row-1">
                        <?= $i++; ?>
                    </td>
                    <td>
                        <?= $user['tanggal']; ?>
                    </td>
                    <td>
                        <?= $user['nama']; ?>
                    </td>
                    <td>
                        <?= $user['email']; ?>
                    </td>
                    <td>
                        <?= $user['komentar']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col">
            <?= $pager->links('kritik', 'my_pagination'); ?>
        </div>
    </div>
</body>

<?= $this->endSection(); ?>