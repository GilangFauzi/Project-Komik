<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-4">
            <h1 class="mt-2">Daftar Orang</h1>
            <form action="" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" autocomplete="off" placeholder="Input Keyword to Search"
                        aria-label="Recipient's username" aria-describedby="button-addon2" name="keyword">
                    <button class="btn btn-primary" type="submit" name="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Aksi</th>
                    </tr>

                </thead>
                <tbody>
                    <?php if (empty($orang)) : ?>
                    <div class="alert alert-warning" role="alert">
                        Data not found!
                    </div>
                    <?php endif; ?>
                    <?php $i = 1 + (7 * ($currentPage - 1)); ?>
                    <?php foreach ($orang as $o) : ?>

                    <tr>
                        <th scope="row"><?= $i++; ?></th>
                        <td><?= $o['nama']; ?></td>
                        <td><?= $o['alamat']; ?></td>
                        <td>
                            <a href="#" class="btn btn-success">Detail</a>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $pager->links('orang', 'orang_pagination'); ?>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>