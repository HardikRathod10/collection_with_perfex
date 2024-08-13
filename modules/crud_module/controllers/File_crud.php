<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File_crud extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('crud_module/file_model', 'file_model');
        $this->load->library(['form_validation']);
        // $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
    }
    // Index method
    public function index()
    {
        $data['title'] = _l('crud_module');
        $this->load->view('crud_module/file_crud/crud_view', $data);
    }

    // Method to load form for create and edit customer
    public function load_form()
    {
        $data['title'] = _l('new_customer');
        $data['countries'] = get_all_countries();
        $this->load->view('crud_module/file_crud/save', $data);
    }

    // Method to save customer
    public function save()
    {
        $post_data = $this->input->post();
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('city', 'City', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required|numeric|exact_length[10]');

        if ($this->form_validation->run() === FALSE) {
            $errors = [
                'name' => form_error('cmp_nm'),
                'country' => form_error('country'),
                'city' => form_error('city'),
                'phone_no' => form_error('phone_no')
            ];
            echo json_encode(['status' => false, 'errors' => $errors]);
        }

        // Uploading file
        $config['upload_path'] = 'uploads/customers/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['file_ext_tolower'] = true;
        $config['max_size'] = 1024;
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);

        // If file uploads goes wrong exit with errors
        if (!$this->upload->do_upload('profile_pic')) {
            $error = [
                'status' => false,
                'errors' => [
                    'profile_pic' => $this->upload->display_errors()
                ]
            ];
            echo json_encode($error);
            exit;
        }
        // Inserting user on successful validations and file upload 
        else {
            $res = $this->file_model->save_customer($post_data, $this->upload->data()['file_name']);
            echo json_encode($res);
        }
    }

}