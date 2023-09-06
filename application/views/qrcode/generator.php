<?php
$timeHelp = "?" . time();
?>

<style>
    canvas#qrcode.show {
        width: fit-content;
        border-radius: 0.375rem !important;
        background-color: rgba(255, 255, 255, 1) !important;
        transition: all 0.3s ease-in-out;
        left: 0;
        right: 0;
        margin: auto;
    }
</style>

<div class="position-relative">
    <form onsubmit="return false;" class="mb-4">
        <div class="mb-3">
            <button type="button" class="btn btn-primary me-1 mb-2" id="generateQRCode">
                <i class="bi bi-qr-code me-1"></i> Buat QR Code
            </button>
            <button type="button" class="btn btn-secondary me-1 mb-2" id="increaseQRSize">
                <i class="bi bi-node-plus-fill me-1"></i> Tambah Pixel
            </button>
            <button type="button" class="btn btn-secondary me-1 mb-2" id="decreaseQRSize">
                <i class="bi bi-node-minus-fill me-1"></i> Kurangi Pixel
            </button>
            <a href="<?= base_url('scanner.php') ?>" target="_qrScanner" class="btn btn-info me-1 mb-2">
                <i class="bi bi-qr-code-scan me-1"></i> QR Code Scanner
            </a>
        </div>
        <div class="mb-3">
            <label for="inputValue" class="form-label">Text</label>
            <input type="text" class="form-control" id="inputValue" placeholder="Text" aria-describedby="inputHelp" required autofocus readonly />
            <div id="inputHelp" class="form-text">Teks yang akan di ubah menjadi QR Code.</div>
        </div>
    </form>

    <canvas id="qrcode" class="position-absolute"></canvas>
</div>

<script src="<?= site_url('assets/dist/generator.bundle.js') . $timeHelp ?>"></script>