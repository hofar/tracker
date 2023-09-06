import jQuery from 'jquery';
import { Modal, Tooltip } from 'bootstrap';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
// import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-responsive-bs5';

let save_method; //for save method string
let table;
let baseUrl = document.getElementById("base_url").value;
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

let csrfName = document.getElementById("csrfName").value;
let csrfToken = document.getElementById("csrfToken").value;

jQuery(document).on('DOMContentLoaded', function () {
    //datatables
    table = new DataTable('#table', {
        "dom": "Blfrtip",
        "buttons": [{
            "extend": 'colvis',
            "columns": ':not(.noVis)',
            "className": 'btn-outline-info',
            "init": function (api, node, config) {
                jQuery(node).removeClass('btn-secondary')
            }
        }],
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": baseUrl + "/ProgramKerja/ajax_list",
            "type": "POST",
            "data": function (d) {
                // Tambahkan CSRF token ke dalam data permintaan
                d[csrfName] = csrfToken;
            }
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
    table.on('draw.dt', function () {
        console.log('DataTables selesai di-render.');

        jQuery('.btn-add-keterangan').on('click', (event) => {
            let data = jQuery(event.currentTarget).data();
            add_keterangan(data);
        });
        jQuery('.btn-edit').on('click', (event) => {
            let data = jQuery(event.currentTarget).data();
            ubah_data(data.id);
        });
        jQuery('.btn-hapus').on('click', (event) => {
            let data = jQuery(event.currentTarget).data();
            hapus_data(data.id);
        });

        // Temukan elemen dengan data-bs-toggle="tooltip" menggunakan jQuery
        jQuery('[data-bs-toggle="tooltip"]').each(function () {
            // Inisialisasi tooltip menggunakan Bootstrap
            new Tooltip(this);
        });
    });

    //set input/textarea/select event when change value, remove class error and remove text help block
    jQuery('input, select, textarea').on('change', function () {
        trigger_change(jQuery(this));
    });
    //check all
    jQuery("#check-all").on('click', function () {
        jQuery(".data-check").prop('checked', jQuery(this).prop('checked'));
    });
    jQuery("#tambah_data").on('click', function () {
        tambah_data();
    });
    jQuery("#reload_table").on('click', function () {
        reload_table();
    });
    jQuery("#bulk_delete").on('click', function () {
        bulk_delete();
    });
    jQuery("#btnSave").on('click', function () {
        save();
    });
    jQuery("#btnSaveKeterangan").on('click', function () {
        saveKeterangan();
    });
    jQuery("#import_excel").on('click', function () {
        import_excel();
    });

    const modalCrudDataEl = document.getElementById('modalCrudData');
    const modalCrudData = new Modal(modalCrudDataEl, {
        keyboard: false
    });
    const modalKeteranganDataEl = document.getElementById('modalKeteranganData');
    const modalKeteranganData = new Modal(modalKeteranganDataEl, {
        keyboard: false
    });
    const importExcelModalEl = document.getElementById('importExcelModal');
    const importExcelModal = new Modal(importExcelModalEl, {
        keyboard: false,
        backdrop: 'static'
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
        jQuery('#form')[0].reset(); // reset form on modals
        jQuery('#formTambahKeterangan')[0].reset(); // reset form on modals
        jQuery('input, select, textarea').removeClass('is-valid is-invalid'); // clear error class
        jQuery('.help-block').empty(); // clear error string
        modalCrudData.show(); // show bootstrap modal
        jQuery('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title

        // Format nilai datetime-local
        let formattedDateTime = fDT();

        // Set nilai pada elemen input menggunakan jQuery
        jQuery('#start_date').val(formattedDateTime);
    }

    function ubah_data(id) {
        save_method = 'update';
        jQuery('#form')[0].reset(); // reset form on modals
        jQuery('#formTambahKeterangan')[0].reset(); // reset form on modals
        jQuery('input, select, textarea').removeClass('is-valid is-invalid'); // clear error class
        jQuery('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        jQuery.ajax({
            url: baseUrl + "/ProgramKerja/ajax_edit/" + id,
            type: "GET",
            data: {
                [csrfName]: csrfToken,
            },
            dataType: "JSON",
            success: function (data) {
                jQuery('[name="id"]').val(data.id);
                jQuery('[name="id_user"]').val(data.id_user);
                jQuery('[name="name"]').val(data.name);
                jQuery('[name="value"]').val(data.value);
                jQuery('[name="type"]').val(data.type);
                jQuery('[name="status"]').val(data.status);
                jQuery('[name="start_date"]').val(data.start_date);
                jQuery('[name="end_date"]').val(data.end_date);
                jQuery('[name="keterangan"]').val(data.keterangan);
                modalCrudData.show(); // show bootstrap modal when Completed loaded
                jQuery('.modal-title').text('Ubah Data'); // Set title to Bootstrap modal title
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }

    function save() {
        jQuery('#btnSave').text('Menyimpan ...'); //change button text
        jQuery('#btnSave').attr('disabled', true); //set button disable
        let url;

        if (save_method === 'add') {
            url = baseUrl + "/ProgramKerja/ajax_add";
        } else {
            url = baseUrl + "/ProgramKerja/ajax_update";
        }

        // ajax adding data to database
        let formData = new FormData(jQuery('#form')[0]);
        formData.append(csrfName, csrfToken); // Menambahkan CSRF token ke dalam data permintaan
        jQuery.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    modalCrudData.hide();
                    reload_table();
                } else {
                    for (let i = 0; i < data.inputerror.length; i++) {
                        let currentElem = jQuery('[name="' + data.inputerror[i] + '"]');
                        currentElem.nextAll('.invalid-feedback').remove();
                        if (currentElem.nextAll('.invalid-feedback').length <= 0) {
                            currentElem.addClass('is-invalid').after('<div class="invalid-feedback">' + data.error_string[i] + '</div>');
                        }
                    }
                }
                jQuery('#btnSave').text('Simpan'); //change button text
                jQuery('#btnSave').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                jQuery('#btnSave').text('Simpan'); //change button text
                jQuery('#btnSave').attr('disabled', false); //set button enable
            }
        });
    }

    function saveKeterangan() {
        let url = baseUrl + "Keterangan/ajax_add_keterangan";

        // ajax adding data to database
        let formData = new FormData(jQuery('#formTambahKeterangan')[0]);
        formData.append(csrfName, csrfToken); // Menambahkan CSRF token ke dalam data permintaan
        jQuery.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    modalKeteranganData.hide();
                    reload_table();
                } else {
                    for (let i = 0; i < data.inputerror.length; i++) {
                        let currentElem = jQuery('#formTambahKeterangan [name="' + data.inputerror[i] + '"]');
                        currentElem.nextAll('.invalid-feedback').remove();
                        if (currentElem.nextAll('.invalid-feedback').length <= 0) {
                            currentElem.addClass('is-invalid').after('<div class="invalid-feedback">' + data.error_string[i] + '</div>');
                        }
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                jQuery('#btnSave').text('Simpan'); //change button text
                jQuery('#btnSave').attr('disabled', false); //set button enable
            }
        });
    }

    function hapus_data(id) {
        if (confirm('Are you sure delete this data?')) {
            // ajax delete data to database
            jQuery.ajax({
                url: baseUrl + "/ProgramKerja/ajax_delete" + id,
                type: "POST",
                data: {
                    [csrfName]: csrfToken,
                },
                dataType: "JSON",
                success: function (data) {
                    //if success reload ajax table
                    modalCrudData.hide();
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }

    function bulk_delete() {
        let list_id = [];
        jQuery(".data-check:checked").each(function () {
            list_id.push(this.value);
        });
        if (list_id.length > 0) {
            if (confirm('Are you sure delete this ' + list_id.length + ' data?')) {
                jQuery.ajax({
                    type: "POST",
                    data: {
                        [csrfName]: csrfToken,
                        id: list_id
                    },
                    url: baseUrl + "/ProgramKerja/ajax_bulk_delete",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) {
                            reload_table();
                        } else {
                            alert('Failed.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        } else {
            alert('No data selected');
        }
    }

    function import_excel() {
        jQuery('#form')[0].reset(); // reset form on modals
        jQuery('#formTambahKeterangan')[0].reset(); // reset form on modals
        importExcelModal.show();
    }

    function add_keterangan(data) {
        jQuery('#form')[0].reset(); // reset form on modals
        jQuery('#formTambahKeterangan')[0].reset(); // reset form on modals
        jQuery('#modalKeteranganData .form-control').removeClass('has-error'); // clear error class
        jQuery('#modalKeteranganData .help-block').empty(); // clear error string

        jQuery('#formTambahKeterangan #value_addKet').attr("data-value", data.value);
        jQuery('#formTambahKeterangan #status_addKet').attr("data-status", data.status);

        jQuery('#formTambahKeterangan #id_addKet').val(data.id);
        jQuery('#formTambahKeterangan #value_addKet').val(data.value);
        jQuery('#formTambahKeterangan #status_addKet').val(data.status);
        jQuery('#formTambahKeterangan #keterangan_addKet').val("");

        modalKeteranganData.show(); // show bootstrap modal
        jQuery('#modalKeteranganData .modal-title').text('Tambah Keterangan Data'); // Set Title to Bootstrap modal title
    }

    function fDT() {
        // Dapatkan waktu saat ini dalam format tipe datetime-local (YYYY-MM-DDTHH:mm)
        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
        let currentDay = String(currentDate.getDate()).padStart(2, '0');
        let currentHours = String(currentDate.getHours()).padStart(2, '0');
        let currentMinutes = String(currentDate.getMinutes()).padStart(2, '0');

        // Format nilai datetime-local
        let formattedDateTime = `${currentYear}-${currentMonth}-${currentDay}T${currentHours}:${currentMinutes}`;

        return formattedDateTime;
    }

    // Fungsi untuk mengatur nilai berdasarkan pilihan status
    function setValueBasedOnStatus() {
        let inputElement = jQuery('#value');
        let statusSelect = jQuery('#status');
        let value = parseInt(inputElement.val());

        // Format nilai datetime-local
        let formattedDateTime = fDT();

        // Mengatur nilai berdasarkan pilihan status
        let selectedStatus = statusSelect.val();
        if (selectedStatus === "<?= data_status()[1] ?>") {
            value = 100;

            // Set nilai pada elemen input menggunakan jQuery
            jQuery('#end_date').val(formattedDateTime);
        } else if (selectedStatus === "<?= data_status()[2] ?>") {
            value = 0;
            jQuery('#start_date').val("");
            jQuery('#end_date').val("");
        } else if (selectedStatus === "<?= data_status()[0] ?>") {
            if (value === "" || value === 0 || value === 100 || isNaN(value)) {
                value = 50;
            }
            // Set nilai pada elemen input menggunakan jQuery
            jQuery('#start_date').val(formattedDateTime);
            jQuery('#end_date').val("");
        }

        // Mengupdate nilai input berdasarkan pilihan status
        inputElement.val(value);
    }

    // Fungsi untuk mengatur pilihan status berdasarkan nilai
    function setStatusBasedOnValue() {
        let inputElement = jQuery('#value');
        let statusSelect = jQuery('#status');
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
        let inputElement = jQuery('#value');
        let statusSelect = jQuery('#status');
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
        let inputElement = jQuery('#value_addKet');
        let statusSelect = jQuery('#status_addKet');
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
        let inputElement = jQuery('#value_addKet');
        let statusSelect = jQuery('#status_addKet');
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
        let inputElement = jQuery('#value_addKet');
        let statusSelect = jQuery('#status_addKet');
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

    function get_csrf_token() {
        jQuery.ajax({
            url: baseUrl + '/ProgramKerja/get_csrf_token',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                csrfName = data.csrfName;
                csrfToken = data.csrfToken;
            },
            error: function () {
                console.warn('You not have accsess to get CSRF');
            }
        });
    }

    // Event listener untuk memantau perubahan pada elemen #value
    jQuery('#value').on('input change', function () {
        validateInput();
    });

    // Event listener untuk memantau perubahan pada elemen #status
    jQuery('#status').on('input change', function () {
        setValueBasedOnStatus();
    });

    // Event listener untuk memantau perubahan pada elemen #value
    jQuery('#value_addKet').on('input change', function () {
        validateInputAddKet();
    });

    // Event listener untuk memantau perubahan pada elemen #status
    jQuery('#status_addKet').on('input change', function () {
        setValueBasedOnStatusAddKet();
    });

    // Event listener untuk modalCrudData
    modalCrudDataEl.addEventListener('show.bs.modal', event => { });

    // Event listener untuk modalKeteranganData
    modalKeteranganDataEl.addEventListener('show.bs.modal', event => { });
});