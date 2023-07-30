<script nonce="scriptRAnd0m">
    window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.0.js"><\/script>');
</script>

<!-- DataTable -->
<link href="https://cdn.datatables.net/v/bs5/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/datatables.min.js"></script>

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
                        <th scope="col">No.</th>
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
                        <td colspan="9" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    let save_method; //for save method string
    let table;
    let base_url = '<?= base_url(); ?>';
    const languageOptions = {
        "search": "Cari:",
        "buttons": {
            "colvis": 'Ubah kolom',
            "excel": 'Ekspor Excel',
        },
        "lengthMenu": "Menampilkan _MENU_ rekaman per halaman",
        "zeroRecords": "Tidak ditemukan data - maaf",
        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
        "infoEmpty": "Tidak ada data tersedia",
        "infoFiltered": "(disaring dari total _MAX_ rekaman)",
        "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
        }
    };

    $(document).ready(function() {

        //datatables
        table = $('#table').DataTable({
            "dom": "Blfrtip",
            "buttons": [{
                "extend": 'colvis',
                "columns": ':not(.noVis)',
                "className": 'btn-outline-info',
                "init": function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                }
            }],
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
                "className": 'noVis'
            }, {
                "targets": [1],
                "orderable": false,
            }, {
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }],
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "language": languageOptions
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
        $('input, select, textarea').change(function() {
            trigger_change($(this));
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
        $("#btnSaveKeterangan").click(function() {
            saveKeterangan();
        });
        $("#import_excel").click(function() {
            import_excel();
        });

        function trigger_change(elem) {
            const value = elem.val();
            let isValid = true;

            if (elem.is('input[type="checkbox"]')) {
                isValid = elem.is(':checked');
            } else if (elem.is('select')) {
                isValid = value !== '';
            } else if (elem.is('input[type="text"], textarea')) {
                isValid = value.trim() !== '';
            }

            elem.toggleClass('is-valid', isValid).toggleClass('is-invalid', !isValid);
        }

        function tambah_data() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('#formTambahKeterangan')[0].reset(); // reset form on modals
            $('input, select, textarea').removeClass('is-valid is-invalid'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modalCrudData').modal('show'); // show bootstrap modal
            $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title

            // Dapatkan waktu saat ini dalam format tipe datetime-local (YYYY-MM-DDTHH:mm)
            let currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
            let currentDay = String(currentDate.getDate()).padStart(2, '0');
            let currentHours = String(currentDate.getHours()).padStart(2, '0');
            let currentMinutes = String(currentDate.getMinutes()).padStart(2, '0');

            // Format nilai datetime-local
            let formattedDateTime = `${currentYear}-${currentMonth}-${currentDay}T${currentHours}:${currentMinutes}`;

            // Set nilai pada elemen input menggunakan jQuery
            $('#start_date').val(formattedDateTime);
        }

        function ubah_data(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#formTambahKeterangan')[0].reset(); // reset form on modals
            $('input, select, textarea').removeClass('is-valid is-invalid'); // clear error class
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
            let url;

            if (save_method === 'add') {
                url = "<?php echo site_url('ProgramKerja/ajax_add') ?>";
            } else {
                url = "<?php echo site_url('ProgramKerja/ajax_update') ?>";
            }

            // ajax adding data to database
            let formData = new FormData($('#form')[0]);
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
                        for (let i = 0; i < data.inputerror.length; i++) {
                            let currentElem = $('[name="' + data.inputerror[i] + '"]');
                            currentElem.nextAll('.invalid-feedback').remove();
                            if (currentElem.nextAll('.invalid-feedback').length <= 0) {
                                currentElem.addClass('is-invalid').after('<div class="invalid-feedback">' + data.error_string[i] + '</div>');
                            }
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

        function saveKeterangan() {
            let url = "<?php echo site_url('Keterangan/ajax_add_keterangan') ?>";

            // ajax adding data to database
            let formData = new FormData($('#formTambahKeterangan')[0]);
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
                        $('#modalKeteranganData').modal('hide');
                        reload_table();
                    } else {
                        for (let i = 0; i < data.inputerror.length; i++) {
                            let currentElem = $('#formTambahKeterangan [name="' + data.inputerror[i] + '"]');
                            currentElem.nextAll('.invalid-feedback').remove();
                            if (currentElem.nextAll('.invalid-feedback').length <= 0) {
                                currentElem.addClass('is-invalid').after('<div class="invalid-feedback">' + data.error_string[i] + '</div>');
                            }
                        }
                    }
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
            let list_id = [];
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
            $('#formTambahKeterangan')[0].reset(); // reset form on modals
            $('#importExcelModal').modal('show');
        }

        function add_keterangan(data) {
            $('#form')[0].reset(); // reset form on modals
            $('#formTambahKeterangan')[0].reset(); // reset form on modals
            $('#modalKeteranganData .form-control').removeClass('has-error'); // clear error class
            $('#modalKeteranganData .help-block').empty(); // clear error string

            $('#formTambahKeterangan #value_addKet').attr("data-value", data.value);
            $('#formTambahKeterangan #status_addKet').attr("data-status", data.status);

            $('#formTambahKeterangan #id_addKet').val(data.id);
            $('#formTambahKeterangan #value_addKet').val(data.value);
            $('#formTambahKeterangan #status_addKet').val(data.status);
            $('#formTambahKeterangan #keterangan_addKet').val("");

            $('#modalKeteranganData').modal('show'); // show bootstrap modal
            $('#modalKeteranganData .modal-title').text('Tambah Keterangan Data'); // Set Title to Bootstrap modal title
        }

        // Fungsi untuk mengatur nilai berdasarkan pilihan status
        function setValueBasedOnStatus() {
            let inputElement = $('#value');
            let statusSelect = $('#status');
            let value = parseInt(inputElement.val());

            // Dapatkan waktu saat ini dalam format tipe datetime-local (YYYY-MM-DDTHH:mm)
            let currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
            let currentDay = String(currentDate.getDate()).padStart(2, '0');
            let currentHours = String(currentDate.getHours()).padStart(2, '0');
            let currentMinutes = String(currentDate.getMinutes()).padStart(2, '0');

            // Format nilai datetime-local
            let formattedDateTime = `${currentYear}-${currentMonth}-${currentDay}T${currentHours}:${currentMinutes}`;

            // Mengatur nilai berdasarkan pilihan status
            let selectedStatus = statusSelect.val();
            if (selectedStatus === "<?= data_status()[1] ?>") {
                value = 100;

                // Set nilai pada elemen input menggunakan jQuery
                $('#end_date').val(formattedDateTime);
            } else if (selectedStatus === "<?= data_status()[2] ?>") {
                value = 0;
                $('#start_date').val("");
                $('#end_date').val("");
            } else if (selectedStatus === "<?= data_status()[0] ?>") {
                if (value === "" || value === 0 || value === 100 || isNaN(value)) {
                    value = 50;
                }
                // Set nilai pada elemen input menggunakan jQuery
                $('#start_date').val(formattedDateTime);
                $('#end_date').val("");
            }

            // Mengupdate nilai input berdasarkan pilihan status
            inputElement.val(value);
        }

        // Fungsi untuk mengatur pilihan status berdasarkan nilai
        function setStatusBasedOnValue() {
            let inputElement = $('#value');
            let statusSelect = $('#status');
            let value = parseInt(inputElement.val());

            // Mengatur opsi "Completed" berdasarkan nilai
            let completedOption = statusSelect.find('option[value="<?= data_status()[1] ?>"]');
            if (value === 100) {
                if (completedOption.length) {
                    completedOption.prop('selected', true);
                }
            } else {
                if (completedOption.length && completedOption.prop('selected')) {
                    statusSelect.val(''); // Mengosongkan pilihan status
                }
            }
        }

        // Fungsi utama untuk validasi dan interaksi berdasarkan nilai input dan pilihan status
        function validateInput() {
            let inputElement = $('#value');
            let statusSelect = $('#status');
            let value = inputElement.val().trim(); // Mengambil nilai input dan menghapus spasi awal/akhir

            // Jika nilai #value kosong, kosongkan juga #status
            if (value === '') {
                statusSelect.val('');
            } else {
                // Jika nilai #value bukan kosong, lanjutkan dengan validasi lainnya
                value = parseInt(value); // Mengonversi input ke tipe data integer

                // Jika nilai #value berisi 0, set #status menjadi "Not Started"
                if (value === 0) {
                    statusSelect.val('<?= data_status()[2] ?>');
                } else if (value === 100) {
                    statusSelect.val('<?= data_status()[1] ?>');
                } else {
                    // Jika nilai #value di antara 0 dan 100 (tapi bukan 0 dan 100),
                    // set #status menjadi "In Progress" atau "<?= data_status()[0] ?>"
                    statusSelect.val('<?= data_status()[0] ?>');
                }
            }

            // Memastikan nilai tidak di bawah 0 atau di atas 100
            value = Math.min(Math.max(value, 0), 100);

            // Mendapatkan nilai sebelumnya dari #status
            let prevStatus = statusSelect.data('prevStatus');

            // Mengatur pilihan status berdasarkan nilai
            setStatusBasedOnValue();

            // Jika nilai #value berisi 0 atau 100 dan #status = "<?= data_status()[0] ?>", kosongkan #value
            let selectedStatus = statusSelect.val();
            if ((value === 0 || value === 100) && selectedStatus === "<?= data_status()[0] ?>") {
                inputElement.val('');
            } else {
                // Update nilai input
                inputElement.val(value);
            }

            // Menyimpan nilai status sebelumnya untuk digunakan pada iterasi selanjutnya
            statusSelect.data('prevStatus', selectedStatus);

            // Jika status berubah, panggil fungsi setValueBasedOnStatus
            if (selectedStatus !== prevStatus) {
                setValueBasedOnStatus();
            }
        }

        // Fungsi untuk mengatur nilai berdasarkan pilihan status
        function setValueBasedOnStatusAddKet() {
            let inputElement = $('#value_addKet');
            let statusSelect = $('#status_addKet');
            let value = parseInt(inputElement.val());
            let dataValue = parseInt(inputElement.attr('data-value'));
            let dataStatus = statusSelect.attr('data-status');

            // Mengatur nilai berdasarkan pilihan status
            let selectedStatus = statusSelect.val();
            if (selectedStatus === "<?= data_status()[1] ?>") {
                value = 100;
            } else if (selectedStatus === "<?= data_status()[2] ?>") {
                value = 0;
            } else if (selectedStatus === "<?= data_status()[0] ?>") {
                value = 50;
                if ((value !== "" || value !== 0 || value !== 100) && dataStatus !== "<?= data_status()[1] ?>") {
                    value = dataValue;
                }
            }

            // Mengupdate nilai input berdasarkan pilihan status
            inputElement.val(value);
        }

        // Fungsi untuk mengatur pilihan status berdasarkan nilai
        function setStatusBasedOnValueAddKet() {
            let inputElement = $('#value_addKet');
            let statusSelect = $('#status_addKet');
            let value = parseInt(inputElement.val());

            // Mengatur opsi "Completed" berdasarkan nilai
            let completedOption = statusSelect.find('option[value="<?= data_status()[1] ?>"]');
            if (value === 100) {
                if (completedOption.length) {
                    completedOption.prop('selected', true);
                }
            } else {
                if (completedOption.length && completedOption.prop('selected')) {
                    statusSelect.val(''); // Mengosongkan pilihan status
                }
            }
        }

        // Fungsi utama untuk validasi dan interaksi berdasarkan nilai input dan pilihan status
        function validateInputAddKet() {
            let inputElement = $('#value_addKet');
            let statusSelect = $('#status_addKet');
            let value = inputElement.val().trim(); // Mengambil nilai input dan menghapus spasi awal/akhir

            // Jika nilai #value kosong, kosongkan juga #status
            if (value === '') {
                statusSelect.val('');
            } else {
                // Jika nilai #value bukan kosong, lanjutkan dengan validasi lainnya
                value = parseInt(value); // Mengonversi input ke tipe data integer

                // Jika nilai #value berisi 0, set #status menjadi "Not Started"
                if (value === 0) {
                    statusSelect.val('<?= data_status()[2] ?>');
                } else if (value === 100) {
                    statusSelect.val('<?= data_status()[1] ?>');
                } else {
                    // Jika nilai #value di antara 0 dan 100 (tapi bukan 0 dan 100),
                    // set #status menjadi "In Progress" atau "<?= data_status()[0] ?>"
                    statusSelect.val('<?= data_status()[0] ?>');
                }
            }

            // Memastikan nilai tidak di bawah 0 atau di atas 100
            value = Math.min(Math.max(value, 0), 100);

            // Mendapatkan nilai sebelumnya dari #status
            let prevStatus = statusSelect.data('prevStatus');

            // Mengatur pilihan status berdasarkan nilai
            setStatusBasedOnValueAddKet();

            // Jika nilai #value berisi 0 atau 100 dan #status = "<?= data_status()[0] ?>", kosongkan #value
            let selectedStatus = statusSelect.val();
            if ((value === 0 || value === 100) && selectedStatus === "<?= data_status()[0] ?>") {
                inputElement.val('');
            } else {
                // Update nilai input
                inputElement.val(value);
            }

            // Menyimpan nilai status sebelumnya untuk digunakan pada iterasi selanjutnya
            statusSelect.data('prevStatus', selectedStatus);

            // Jika status berubah, panggil fungsi setValueBasedOnStatus
            if (selectedStatus !== prevStatus) {
                setValueBasedOnStatusAddKet();
            }
        }

        // Event listener untuk memantau perubahan pada elemen #value
        $('#value').on('input change', function() {
            validateInput();
        });

        // Event listener untuk memantau perubahan pada elemen #status
        $('#status').on('input change', function() {
            setValueBasedOnStatus();
        });

        // Event listener untuk memantau perubahan pada elemen #value
        $('#value_addKet').on('input change', function() {
            validateInputAddKet();
        });

        // Event listener untuk memantau perubahan pada elemen #status
        $('#status_addKet').on('input change', function() {
            setValueBasedOnStatusAddKet();
        });
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
                <form id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
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