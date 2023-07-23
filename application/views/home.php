<?php
$user_nama = $this->session->userdata('user_nama');
?>

<div class="row row-cols-1 g-4">
    <div class="col">
        <div class="card h-100">
            <!-- <img src="..." class="card-img-top" alt="..."> -->
            <div class="card-body">
                <h2 class="card-title">Tracker Graph</h2>
                <p class="card-text">Selamat datang di Sistem Informasi Grafik Jaringan dan Aplikasi.</p>
                <?php
                if ($user_nama) {
                ?>
                    <p class="card-text">Anda login sebagai <strong><?= ($user_nama) ? $user_nama : 'Anonim'  ?></strong>.</p>
                <?php
                }
                ?>
            </div>
            <div class="card-footer">
                <small class="text-body-secondary">Last updated 3 mins ago</small>
            </div>
        </div>
    </div>
</div>