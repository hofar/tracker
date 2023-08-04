<script nonce="scriptRAnd0m">
    window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.0.js"><\/script>');
</script>

<!-- DataTable -->
<link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/datatables.min.js"></script>

<div class="card border-primary mb-3">
    <div class="card-body">
        <!-- <button type="button" class="btn btn-outline-success" id="tambah_data"><i class="bi bi-plus-circle"></i> Tambah</button> -->
        <button type="button" class="btn btn-outline-secondary" id="reload_table"><i class="bi bi-arrow-repeat"></i> Segarkan</button>
        <!-- <button type="button" class="btn btn-outline-danger" id="bulk_delete"><i class="bi bi-trash"></i> Hapus Semua</button> -->

        <hr />

        <h5>Tabel</h5>
        <div class="table-responsive">
            <table id="table" class="table table-striped content-responsive">
                <thead>
                    <tr>
                        <!-- <th scope="col"><input type="checkbox" id="check-all"></th> -->
                        <th scope="col">No</th>
                        <th scope="col">Sasaran Program</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Keterangan Progress</th>
                        <th scope="col">Tanggal &amp; Waktu</th>
                        <!-- <th scope="col">Aksi</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">Loading...</td>
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
            "excel": '<i class="bi bi-file-earmark-excel"></i> Ekspor Excel'
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
                "className": 'btn-outline-info',
                "init": function(api, node, config) {
                    $(node).removeClass('btn-secondary')
                }
            }, {
                "extend": 'excel',
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
                "url": "<?= site_url('keterangan/ajax_list') ?>",
                "type": "POST",
                "data": function(d) {
                    // Tambahkan CSRF token ke dalam data permintaan
                    d['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column
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
        $('input, textarea, select').change(function() {
            trigger_change($(this));
        });
        $('[name="type"]').change(function() {
            get_program_kerja();
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
        $('input, select, textarea').removeClass('is-valid is-invalid'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Tambah keterangan'); // Set Title to Bootstrap modal title
    }

    function ubah_data(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('input, select, textarea').removeClass('is-valid is-invalid'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('keterangan/ajax_edit') ?>/" + id,
            type: "GET",
            data: {
                ['<?= $this->security->get_csrf_token_name(); ?>']: '<?= $this->security->get_csrf_hash(); ?>',
            },
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="type"]').val(data.type);
                $('[name="status"]').val(data.status);
                get_program_kerja(data.id_program_kerja);
                $('[name="keterangan"]').val(data.keterangan);
                $('[name="created_at"]').val(data.created_at);

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Ubah keterangan'); // Set title to Bootstrap modal title
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
            url = "<?php echo site_url('keterangan/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('keterangan/ajax_update') ?>";
        }

        // ajax adding data to database
        let formData = new FormData($('#form')[0]);
        formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>'); // Menambahkan CSRF token ke dalam data permintaan
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
                    $('#modal_form').modal('hide');
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
                $('#btnSave').text('Simpan'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            }
        });
    }

    function hapus_data(id) {
        if (confirm('Yakin ingin menghapus data ?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('keterangan/ajax_delete') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
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
            if (confirm('Yakin ingin menghapus ' + list_id.length + ' data ?')) {
                $.ajax({
                    type: "POST",
                    data: {
                        id: list_id
                    },
                    url: "<?php echo site_url('keterangan/ajax_bulk_delete') ?>",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) {
                            reload_table();
                        } else {
                            alert('Gagal hapus data yang di centang\nHarap hubungi administrator.');
                        }
                        $('#check-all').prop('checked', false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        } else {
            alert('Tidak ada data yang dicentang');
        }
    }

    function get_program_kerja(programKerjaID) {
        const prokerElem = $('[name="id_program_kerja"]');
        const typeElem = $('[name="type"]');

        prokerElem.html(""); // reset

        if (typeof typeElem.val() !== 'undefined' || typeElem.val() !== null) {
            let url = "";
            if (typeElem.val() === "Network") {
                url = "<?php echo site_url('ProgramKerja/getDataNetwork') ?>";
            } else {
                url = "<?php echo site_url('ProgramKerja/getDataAplikasi') ?>";
            }

            const defaultOption = $('<option></option>');
            defaultOption.val('');
            defaultOption.text('-- Pilih --');
            prokerElem.append(defaultOption);

            //Ajax Load data from ajax
            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    data.forEach((item) => {
                        const option = $('<option></option>');
                        option.val(item.id); // Anda bisa menggunakan properti lain sebagai value jika diperlukan
                        option.text(item.name);
                        prokerElem.append(option);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            }).done(function(data) {
                if (typeof programKerjaID !== 'undefined' || programKerjaID !== null) {
                    prokerElem.val(programKerjaID);
                }
            });
        } else {
            console.log("Type tidak boleh kosong");
        }
    }
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Form keterangan</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <!--
                            <div class="mb-3">
                                <label class="form-label" for="status">Type</label>
                                <select name="status" id="status" class="form-control form-control-user" autofocus>
                                    <option value="">-- Pilih --</option>
                                    <option value="Network">Network</option>
                                    <option value="Aplikasi">Aplikasi</option>
                                </select>
                            </div>
                        -->
                        <div class="mb-3">
                            <label class="form-label" for="type">Type</label>
                            <select name="type" id="type" class="form-control form-control-user" autofocus>
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
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="id_program_kerja">Sasaran Program</label>
                            <select name="id_program_kerja" id="id_program_kerja" class="form-control form-control-user" autofocus>
                                <option value="">-- Pilih --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-control form-control-user" autofocus>
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
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control form-control-user" placeholder="Isi keterangan baru" autofocus></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="created_at">Tanggal &amp; Waktu</label>
                            <input type="datetime-local" class="form-control form-control-user" id="created_at" name="created_at" placeholder="Tanggal &amp; Waktu" />
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
<!-- End Bootstrap modal -->