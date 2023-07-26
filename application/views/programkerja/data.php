<script nonce="scriptRAnd0m">
    window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.0.js"><\/script>');
</script>

<!-- DataTable -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<div class="card mb-3">
    <div class="card-body">
        <button type="button" class="btn btn-outline-success" id="tambah_data"><i class="bi bi-plus-circle"></i> Tambah</button>
        <button type="button" class="btn btn-outline-secondary" id="reload_table"><i class="bi bi-arrow-repeat"></i> Segarkan</button>
        <button type="button" class="btn btn-outline-danger" id="bulk_delete"><i class="bi bi-trash"></i> Hapus Semua</button>
        <button type="button" class="btn btn-outline-info" id="import_excel"><i class="bi bi-file-earmark-excel"></i> Import Excel</button>

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
                        <th scope="col">Sasaran Program Kerja</th>
                        <th scope="col">Persentase (%)</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var save_method; //for save method string
    var table;
    var base_url = '<?= base_url(); ?>';

    $(document).ready(function() {

        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?= site_url('ProgramKerja/ajax_list') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [{
                    "targets": [0], //first column
                    "orderable": false, //set not orderable
                },
                {
                    "targets": [-2],
                    "orderable": false,
                },
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
        });

        // Mendengarkan event draw.dt untuk mendeteksi ketika DataTables selesai di-render
        table.on('draw.dt', function() {
            console.log('DataTables selesai di-render.');

            $('.btn-add-keterangan').on('click', (event) => {
                let data = $(event.currentTarget).data();
                add_keterangan(data);
            });
            $('.btn-edit').on('click', (event) => {
                let data = $(event.currentTarget).data();
                ubah_data(data.id);
            });
            $('.btn-hapus').on('click', (event) => {
                let data = $(event.currentTarget).data();
                hapus_data(data.id);
            });
        });

        //set input/textarea/select event when change value, remove class error and remove text help block
        $("input").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        $("textarea").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        $("select").change(function() {
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });

        //check all
        $("#check-all").click(function() {
            $(".data-check").prop('checked', $(this).prop('checked'));
        });

        $("#tambah_data").click(function() {
            tambah_data();
        });
        $("#reload_table").click(function() {
            reload_table();
        });
        $("#bulk_delete").click(function() {
            bulk_delete();
        });
        $("#btnSave").click(function() {
            save();
        });
        $("#import_excel").click(function() {
            import_excel();
        });

        function tambah_data() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modalCrudData').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
        }

        function ubah_data(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('ProgramKerja/ajax_edit') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.id);
                    $('[name="name"]').val(data.name);
                    $('[name="value"]').val(data.value);
                    $('[name="type"]').val(data.type);
                    $('[name="status"]').val(data.status);
                    $('[name="start_date"]').val(data.start_date);
                    $('[name="end_date"]').val(data.end_date);
                    $('[name="keterangan"]').val(data.keterangan);
                    $('#modalCrudData').modal('show'); // show bootstrap modal when Completed loaded
                    $('.modal-title').text('Ubah Data'); // Set title to Bootstrap modal title
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function save() {
            $('#btnSave').text('Menyimpan ...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('ProgramKerja/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('ProgramKerja/ajax_update') ?>";
            }

            // ajax adding data to database
            var formData = new FormData($('#form')[0]);
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function(data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modalCrudData').modal('hide');
                        reload_table();
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        }
                    }
                    $('#btnSave').text('Simpan'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function hapus_data(id) {
            if (confirm('Are you sure delete this data?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('ProgramKerja/ajax_delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        //if success reload ajax table
                        $('#modalCrudData').modal('hide');
                        reload_table();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

        function bulk_delete() {
            var list_id = [];
            $(".data-check:checked").each(function() {
                list_id.push(this.value);
            });
            if (list_id.length > 0) {
                if (confirm('Are you sure delete this ' + list_id.length + ' data?')) {
                    $.ajax({
                        type: "POST",
                        data: {
                            id: list_id
                        },
                        url: "<?php echo site_url('ProgramKerja/ajax_bulk_delete') ?>",
                        dataType: "JSON",
                        success: function(data) {
                            if (data.status) {
                                reload_table();
                            } else {
                                alert('Failed.');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Error deleting data');
                        }
                    });
                }
            } else {
                alert('No data selected');
            }
        }

        function import_excel() {
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#importExcelModal').modal('show');
        }

        function validateInput() {
            var inputElement = $('#value');
            var value = parseInt(inputElement.val()); // Mengonversi input ke tipe data integer

            // Memastikan nilai tidak di bawah 0 atau di atas 100
            value = Math.min(Math.max(value, 0), 100);

            // Update nilai input
            inputElement.val(value);

            // Cek apakah nilai input adalah 100
            if (value === 100) {
                // Jika nilai adalah 100, pilih opsi "Completed" secara otomatis
                var statusSelect = $('#status');
                var CompletedOption = statusSelect.find('option[value="Completed"]');
                if (CompletedOption.length) {
                    CompletedOption.prop('selected', true);
                }
            } else {
                // Jika nilai bukan 100, pastikan opsi "Completed" tidak dipilih
                var statusSelect = $('#status');
                var CompletedOption = statusSelect.find('option[value="Completed"]');
                if (CompletedOption.length && CompletedOption.prop('selected')) {
                    statusSelect.val(''); // Mengosongkan pilihan status
                }
            }
        }

        function add_keterangan(data) {
            $('#formTambahKeterangan')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            $('[name="name"]').val(data.id);
            $('[name="status_keterangan"]').val(data.status);
            $('[name="keterangan"]').val("");

            $('#modalKeteranganData').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Keterangan Data'); // Set Title to Bootstrap modal title
        }

        // Tambahkan event listener untuk input
        $('#value').on('keyup change', validateInput);
    });
</script>

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
                <form id="form">
                    <input type="hidden" value="" name="id" />
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
                                <option value="Network">Network</option>
                                <option value="Aplikasi">Aplikasi</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="status">Status</label>
                        <div class="col-sm">
                            <select name="status" id="status" class="form-select" autofocus>
                                <option value="">-- Pilih --</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Not Started">Not Started</option>
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
                    <!--
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="keterangan">Keterangan</label>
                        <div class="col-sm">
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control"></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- pop up data -->
<div class="modal fade" id="modalKeteranganData" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Form Input</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form id="formTambahKeterangan">
                    <input type="hidden" value="" name="id" />
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="status_keterangan">Status Keterangan</label>
                        <div class="col-sm">
                            <input id="status_keterangan" name="status_keterangan" placeholder="Status Keterangan" class="form-control" type="text" readonly autofocus>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label" for="keterangan">Keterangan</label>
                        <div class="col-sm">
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control" placeholder="Isi keterangan baru" autofocus></textarea>
                            <span class="help-block"></span>
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

<!-- pop up import -->
<div class="modal fade" id="importExcelModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <!-- import_form.php -->
        <form method="post" action="<?= base_url('import/do_import'); ?>" enctype="multipart/form-data">
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