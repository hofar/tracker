<?php

if (!function_exists('badge_type')) {
    function badge_type($type)
    {
        $bg = "";

        switch ($type) {
            case 'In Progress':
                $bg = "text-bg-warning";
                break;
            case 'Complate':
                $bg = "text-bg-success";
                break;
            case 'Not Started':
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

if (!function_exists('action_button')) {
    function action_button($id, $update_name, $delete_name)
    {
        return '<div class="btn-group" role="group" aria-label="Group action">'
            . '<a href="#" title="Edit" onclick="' . $update_name . '(' . $id . ')" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-square"></i></i></a>'
            . '<a href="#" title="Hapus" onclick="' . $delete_name . '(' . $id . ')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>'
            . '</div>';
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

        return false; // Atau nilai lain yang sesuai dengan kebutuhan Anda jika tanggal tidak valid
    }
}

if (!function_exists('custom_function')) {
    function custom_function()
    {
        $CI = &get_instance();
        // Sekarang Anda dapat menggunakan instance CI untuk akses ke fitur dan library CodeIgniter
        $CI->load->library('session');
        $CI->session->set_userdata('key', 'value');
    }
}
