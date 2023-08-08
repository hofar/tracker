<link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.1/b-colvis-2.4.1/r-2.5.0/datatables.min.css" rel="stylesheet">

<div class="card mb-3">
    <div class="card-body">
        <button type="button" class="btn btn-outline-success me-1 mb-2" id="tambah_data" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tambah"><i class="bi bi-plus-circle"></i> Tambah</button>
        <button type="button" class="btn btn-outline-secondary me-1 mb-2" id="reload_table" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Segarkan"><i class="bi bi-arrow-repeat"></i> Segarkan</button>
        <button type="button" class="btn btn-outline-danger me-1 mb-2" id="bulk_delete" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Hapus Semua"><i class="bi bi-trash"></i> Hapus Semua</button>
        <button type="button" class="btn btn-outline-info me-1 mb-2" id="import_excel" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Import Excel"><i class="bi bi-file-earmark-excel"></i> Import Excel</button>

        <hr />

        <?php
        if ($this->session->flashdata('status')) {
        ?>
            <div class="alert alert-info mb-4">
                <?= str_replace('<p>', '<p class="mb-0"><i class="bi bi-info-square"></i> ', $this->session->flashdata('status')); ?>
            </div>
        <?php
        }
        ?>

        <h5>Tabel</h5>
        <div class="table-responsive">
            <table id="table" class="table table-striped content-responsive">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="check-all"></th>
                        <th scope="col">No.</th>
                        <th scope="col">User</th>
                        <th scope="col">Sasaran Program Kerja</th>
                        <th scope="col">Persentase (%)</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <!-- <th scope="col">Keterangan</th> -->
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="10" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= site_url('assets/dist/programkerjaData.bundle.js') ?>"></script>

<!-- Bootstrap modal -->
<!-- pop up data -->
<div class="modal fade" id="modalCrudData" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Form Input</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="id_user">User</label>
                            <div class="col-sm">
                                <select name="id_user" id="id_user" class="form-select" autofocus>
                                    <option value="">-- Pilih --</option>
                                    <?php
                                    $list = $user_list;
                                    foreach ($list as $key => $value) {
                                    ?>
                                        <option value="<?= $value->id ?>"><?= $value->nama ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="name">Name</label>
                            <div class="col-sm">
                                <input id="name" name="name" placeholder="Name" class="form-control" type="text" maxlength="100" min="3" autofocus>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="value">Value</label>
                            <div class="col-sm">
                                <input id="value" name="value" placeholder="Value" class="form-control" type="number" max="100" min="0" autofocus>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="type">Type</label>
                            <div class="col-sm">
                                <select name="type" id="type" class="form-select" autofocus>
                                    <option value="">-- Pilih --</option>
                                    <?php
                                    $list = data_type();
                                    foreach ($list as $key => $value) {
                                    ?>
                                        <option value="<?= $value ?>"><?= $value ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="status">Status</label>
                            <div class="col-sm">
                                <select name="status" id="status" class="form-select" autofocus>
                                    <option value="">-- Pilih --</option>
                                    <?php
                                    $list = data_status();
                                    foreach ($list as $key => $value) {
                                    ?>
                                        <option value="<?= $value ?>"><?= $value ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="start_date">Start Date</label>
                            <div class="col-sm">
                                <input id="start_date" name="start_date" placeholder="Start Date" class="form-control" type="datetime-local" autofocus>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="end_date">End Date</label>
                            <div class="col-sm">
                                <input id="end_date" name="end_date" placeholder="End Date" class="form-control" type="datetime-local" autofocus>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm">
                                <textarea name="keterangan" id="keterangan" rows="4" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- pop up keterangan -->
<div class="modal fade" id="modalKeteranganData" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Form Input</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="formTambahKeterangan">
                    <input type="hidden" value="" id="id_addKet" name="id_program_kerja" />
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="value_addKet">Value</label>
                        <div class="col-sm">
                            <input id="value_addKet" name="value" placeholder="Value" class="form-control" type="number" max="100" min="0" autofocus>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="status_addKet">Status</label>
                        <div class="col-sm">
                            <select name="status" id="status_addKet" class="form-select" autofocus>
                                <option value="">-- Pilih --</option>
                                <?php
                                $list = data_status();
                                foreach ($list as $key => $value) {
                                ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="keterangan_addKet">Keterangan</label>
                        <div class="col-sm">
                            <textarea name="keterangan" id="keterangan_addKet" rows="4" class="form-control" placeholder="Isi keterangan baru" autofocus></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSaveKeterangan">Simpan Keterangan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- pop up import -->
<div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <!-- import_form.php -->
        <form method="post" action="<?= base_url('import/do_import'); ?>" enctype="multipart/form-data">
            <?= '<input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '" />'; ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="importExcelModalLabel">Import Excel</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <p>Berikut adalah template file Excel yang bisa anda download sebelum anda melakukan import.</p>
                        <a href="<?= site_url('ProgramKerja/download_template') ?>" class="btn btn-outline-primary">
                            Download Template
                        </a>
                    </div>

                    <label class="form-label" for="excel_file">Upload</label>
                    <input type="file" class="form-control" id="excel_file" name="excel_file">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Bootstrap modal -->