<?php

if (!function_exists('badge_type')) {
    function badge_type($type)
    {
        $bg = "";
        $status = data_status();

        switch ($type) {
            case $status[0]:
                $bg = "text-bg-warning";
                break;
            case $status[1]:
                $bg = "text-bg-success";
                break;
            case $status[2]:
                $bg = "text-bg-danger";
                break;
        }

        $badge = '<span class="badge ' . $bg . '">' . $type . '</span>';

        return $badge;
    }
}

if (!function_exists('checkbox')) {
    function checkbox($id)
    {
        return '<input type="checkbox" class="data-check" value="' . $id . '">';
    }
}

if (!function_exists('textarea')) {
    function textarea($keterangan)
    {
        return '<textarea readonly class="form-control" row="4">'
            . $keterangan
            . '</textarea>';
    }
}

if (!function_exists('custom_div')) {
    function custom_div($keterangan)
    {
        return '<div class="custom-div">'
            . $keterangan
            . '</div>';
    }
}

if (!function_exists('action_button')) {
    function action_button($id, $update_name, $delete_name)
    {
        return '<div class="btn-group" role="group" aria-label="Group action">'
            . '<a title="Edit" data-id="' . $id . '" class="btn btn-sm btn-outline-secondary btn-edit">Edit <i class="bi bi-pencil-square"></i></i></a>'
            . '<a title="Hapus" data-id="' . $id . '" class="btn btn-sm btn-outline-danger btn-hapus">Hapus <i class="bi bi-trash"></i></a>'
            . '</div>';
    }
}

if (!function_exists('action_button_v2')) {
    function action_button_v2($item, $array)
    {
        // data-status="' . $item->status . '"

        $group_button = '<div class="btn-group" role="group" aria-label="Group action">'
            . '<a title="Keterangan" data-id="' . $item->id . '" data-value="' . $item->value . '" data-status="' . $item->status . '" class="btn btn-sm btn-outline-primary btn-add-keterangan" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tambah Keterangan"><i class="bi bi-journal-plus"></i></a>'
            . '<a title="Edit" data-id="' . $item->id . '" class="btn btn-sm btn-outline-secondary btn-edit" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Edit Data"><i class="bi bi-pencil-square"></i></i></a>'
            . '<a title="Hapus" data-id="' . $item->id . '" class="btn btn-sm btn-outline-danger btn-hapus" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Hapus Data"><i class="bi bi-trash"></i></a>'
            . '</div>';

        return $group_button;
    }
}

if (!function_exists('custom_date')) {
    function custom_date($date)
    {
        $months = array(
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );

        $timestamp = ($date !== null) ? strtotime($date) : false;

        if ($timestamp !== false) {
            $day = date('d', $timestamp);
            $month = $months[date('n', $timestamp) - 1];
            $year = date('Y', $timestamp);
            $time = date('H:i:s', $timestamp);

            $custom_date = $day . ' ' . $month . ' ' . $year . ' ' . $time;
            return $custom_date;
        }

        return '<i class="text-body-secondary">not set</i>'; // Atau nilai lain yang sesuai dengan kebutuhan Anda jika tanggal tidak valid
    }
}

if (!function_exists('custom_function')) {
    function custom_function()
    {
        $CI = get_instance();
        // Sekarang Anda dapat menggunakan instance CI untuk akses ke fitur dan library CodeIgniter
        $CI->load->library('session');
        $CI->session->set_userdata('key', 'value');
    }
}

if (!function_exists('get_user_agent')) {
    function get_user_agent()
    {
        $CI = get_instance();
        $CI->load->library('user_agent');

        // Mendapatkan browser yang digunakan oleh user
        // $browser = $CI->agent->browser();

        // Mendapatkan versi dari browser yang digunakan oleh user
        // $browser_version = $CI->agent->version();

        // Mendapatkan sistem operasi (platform) yang digunakan oleh user
        // $platform = $CI->agent->platform();

        // Mendapatkan referer (halaman sebelumnya) dari user
        // $referer = $CI->agent->referrer();

        // Mendapatkan bahasa yang digunakan oleh user
        // $language = $CI->agent->languages();

        // Mendapatkan User Agent string lengkap
        // $user_agent_string = $CI->agent->agent_string();

        return $CI->agent;
    }
}

if (!function_exists('data_type')) {
    function data_type()
    {
        return array("Aplikasi", "Network");
    }
}

if (!function_exists('data_status')) {
    function data_status()
    {
        return array("In Progress", "Completed", "Not Started");
    }
}
