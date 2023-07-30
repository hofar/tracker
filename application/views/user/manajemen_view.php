<script nonce="scriptRAnd0m">
    window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.0.js"><\/script>');
</script>

<!-- DataTable -->
<link href="https://cdn.datatables.net/v/bs5/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/datatables.min.js"></script>

<div class="card border-primary mb-3">
    <div class="card-body">
        <button type="button" class="btn btn-outline-success" id="tambah_data"><i class="bi bi-plus-circle"></i> Tambah</button>
        <button type="button" class="btn btn-outline-secondary" id="reload_table"><i class="bi bi-arrow-repeat"></i> Segarkan</button>
        <button type="button" class="btn btn-outline-danger" id="bulk_delete"><i class="bi bi-trash"></i> Hapus Semua</button>

        <hr />

        <h5>Tabel</h5>
        <div class="table-responsive">
            <table id="table" class="table table-striped content-responsive">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" id="check-all"></th>
                        <th scope="col">No.</th>
                        <th scope="col">Nama</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Role</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center">Loading...</td>
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
                "url": "<?= site_url('user/ajax_list') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [{
                "targets": [0], //first column
                "orderable": false, //set not orderable
                "className": 'noVis'
            }, {
                "targets": [1], //first column
                "orderable": false, //set not orderable
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
        $("input, textarea, select").change(function() {
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
        $('.modal-title').text('Tambah User'); // Set Title to Bootstrap modal title
        get_role();
    }

    function ubah_data(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('input, select, textarea').removeClass('is-valid is-invalid'); // clear error class
        $('.help-block').empty(); // clear error string
        get_role();

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('user/ajax_edit') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="name"]').val(data.nama);
                $('[name="user_id"]').val(data.user_id);
                $('[name="role_select"]').val(data.role_id);
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Ubah User'); // Set title to Bootstrap modal title
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
            url = "<?php echo site_url('user/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('user/ajax_update') ?>";
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
                url: "<?php echo site_url('user/ajax_delete') ?>/" + id,
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
                    url: "<?php echo site_url('user/ajax_bulk_delete') ?>",
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

    function get_role() {
        $.ajax({
            url: "<?= site_url('role/get_role') ?>",
            type: 'POST',
            data: {},
            dataType: 'json',
            beforeSend: function(xhr) {
                $('[name="role_select"]').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            },
            success: function(data, textStatus, jqXHR) {
                let opt = '<option value="">-- Silakan Pilih --</option>';
                if (data.status) {
                    $.each(data.opt, function(i, v) {
                        opt += '<option value="' + v.id + '">' + v.name + '</option>';
                    });
                }
                $('[name="role_select"]').html(opt);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
    }
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Form User</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Full Name</label>
                            <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Full Name" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="user_id">User ID</label>
                            <input type="text" class="form-control form-control-user" id="user_id" name="user_id" placeholder="User ID" />
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password" />
                            </div>
                            <div class="col-sm-6">
                                <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat Password" />
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="role_select">Role</label>
                            <select multiple class="form-control" id="role_select" name="role_select">
                            </select>
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