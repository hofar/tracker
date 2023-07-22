<?php

function is_logged_in()
{
    $CI = get_instance();
    if (!$CI->session->userdata('user_id')) {
        redirect('auth');
    }
}

function is_user_id()
{
    $CI = get_instance();
    if (!$CI->session->userdata('user_id')) {
        return false;
    } else {
        return true;
    }
}

function get_super()
{
    $CI = get_instance();
    return $CI->session->userdata('is_super');
}

function is_super()
{
    $is_super_ = (get_super() == '1') ? true : false;
    return $is_super_;
}
