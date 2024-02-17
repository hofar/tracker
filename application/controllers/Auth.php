<?php

defined("BASEPATH") or exit("No direct script access allowed");

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->form_validation->set_rules("user_id", "User", "trim|required");
        $this->form_validation->set_rules("password", "Password", "trim|required");
        if ($this->form_validation->run() == false) {
            $array = array();

            $data = array(
                "title" => "Login",
                "metadesc" => "",
                "content" => $this->parser->parse("auth/login", $array, true)
            );

            $this->parser->parse("template_auth", $data);
        } else {
            // validasinya success
            $this->login();
        }
    }

    private function login()
    {
        $user_id = $this->input->post("user_id");
        $password = $this->input->post("password");

        $this->db->query("SET sql_mode = '' ");

        $this->db->from("user u");
        $this->db->join("user_role ur", "ur.id = u.role_id", "left");
        $this->db->where("u.user_id", $user_id);
        $r_user = $this->db->get();
        $user = $r_user->row_array();
        // jika usernya ada
        if ($user) {
            // jika usernya aktif
            if ($user["is_active"] == 1) {
                // cek password
                if (password_verify($password, $user["password"])) {
                    $data = array(
                        "user_id" => $user["user_id"],
                        "user_nama" => $user["nama"],
                        "is_super" => $user["is_super"],
                        // "user_agent" => get_user_agent()
                    );
                    $this->session->set_userdata($data);

                    redirect("dashboard");
                } else {
                    // $this->session->set_flashdata("message", `<div class="alert alert-danger" role="alert">Wrong password!</div>`);
                    $this->session->set_flashdata("message_type", "alert-danger");
                    $this->session->set_flashdata("message", "Wrong password!");
                }
            } else {
                // $this->session->set_flashdata("message", `<div class="alert alert-danger" role="alert">This user has not been activated!</div>`);
                $this->session->set_flashdata("message_type", "alert-danger");
                $this->session->set_flashdata("message", "This user has not been activated!");
            }
        } else {
            // $this->session->set_flashdata("message", `<div class="alert alert-danger" role="alert">User is not registered!</div>`);
            $this->session->set_flashdata("message_type", "alert-danger");
            $this->session->set_flashdata("message", "User is not registered!");
        }

        redirect("auth");
    }

    public function registration()
    {
        $this->form_validation->set_rules("name", "Name", "required|trim");
        $this->form_validation->set_rules("user_id", "User ID", "required|trim|is_unique[user.user_id]", array(
            "is_unique" => "This user_id has already registered!"
        ));
        $this->form_validation->set_rules("password1", "Password", "required|trim|min_length[3]|matches[password2]", array(
            "matches" => "Password not matches!",
            "min_length" => "Password to sort!"
        ));
        $this->form_validation->set_rules("password2", "Password", "required|trim|matches[password1]");

        if ($this->form_validation->run() == false) {
            $data["title"] = "User Registration";

            $array = array();

            $data = array(
                "title" => "Registration",
                "metadesc" => "",
                "content" => $this->parser->parse("auth/registration", $array, true)
            );

            $this->parser->parse("template_auth", $data);
        } else {
            //echo "Data berhasil ditambahkan";

            $data = array(
                "nama" => htmlspecialchars($this->input->post("name", true)),
                "user_id" => htmlspecialchars($this->input->post("user_id", true)),
                "gambar" => "default.jpg",
                "password" => password_hash($this->input->post("password1"), PASSWORD_DEFAULT),
                "role_id" => 2,
                "is_active" => 1,
                "create_at" => date("Y-m-d H:i:s")
            );

            $this->db->query("SET sql_mode = '' ");
            $this->db->insert("user", $data);
            // $this->session->set_flashdata("message", `<div class="alert alert-success" role="alert">Congratulation, your account has been created. Please Login</div>`);
            $this->session->set_flashdata("message_type", "alert-success");
            $this->session->set_flashdata("message", "Congratulation, your account has been created. Please Login");
            redirect("auth");
        }
    }

    public function logout()
    {
        $array_item = array(
            "user_id",
            "user_nama",
            "role_id",
            "is_super"
        );

        $this->session->unset_userdata($array_item);
        $this->session->sess_destroy(); // Menghapus semua data sesi

        // $this->session->set_flashdata("message", `<div class="alert alert-success" role="alert">You have been logged out!</div>`);
        $this->session->set_flashdata("message_type", "alert-success");
        $this->session->set_flashdata("message", "You have been logged out!");

        redirect("auth");
    }
}
