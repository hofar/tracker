<?php
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->network();
    }

    public function home()
    {
        $data = array(
            "title" => "Tracker Graph",
            "metadesc" => "Sistem Informasi Grafik Jaringan dan Aplikasi",
            "content" => $this->load->view("home", "", TRUE)
        );

        $this->parser->parse("template", $data);
    }

    public function network()
    {
        $data = array(
            "title" => "Tracker Graph",
            "metadesc" => "Sistem Informasi Grafik Jaringan dan Aplikasi",
            "content" => $this->load->view("dashboard/network", "", TRUE)
        );

        $this->parser->parse("template", $data);
    }

    public function aplikasi()
    {
        $data = array(
            "title" => "Tracker Graph",
            "metadesc" => "Sistem Informasi Grafik Jaringan dan Aplikasi",
            "content" => $this->load->view("dashboard/aplikasi", "", TRUE)
        );

        $this->parser->parse("template", $data);
    }
}
