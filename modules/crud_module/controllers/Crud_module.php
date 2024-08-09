<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crud_module extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('crud_module_model');
        $this->load->library(['form_validation', 'app']);
        $this->load->helper('crud_module');
    }

    public function link2()
    {
        $data['title'] = _l('crud_module');
        $data['countries'] = $this->crud_module_model->get_countries();
        $this->load->view('crud_view', $data);
    }

    public function create_client()
    {
        $post_data = $this->input->post();
        $this->form_validation->set_rules('cmp_nm', 'Company name', 'required');
        $this->form_validation->set_rules('phone_no', 'Phone number', 'required|numeric|exact_length[10]');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('city', 'City', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('zip', 'Zip', 'required|exact_length[6]');
        $this->form_validation->set_rules('website', 'Website', 'required|valid_url');

        if ($this->form_validation->run() === FALSE) {
            $errors = [
                'cmp_nm' => form_error('cmp_nm'),
                'phone_no' => form_error('phone_no'),
                'country' => form_error('country'),
                'city' => form_error('city'),
                'zip' => form_error('zip'),
                'website' => form_error('website'),
            ];
            echo json_encode(['status' => false, 'errors' => $errors]);
        } else {
            $res = $this->crud_module_model->save_client($post_data);
            echo json_encode($res);
        }
    }

    // Showing clients
    public function show_clients()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('crud_module', 'datatable'));
        }
    }

    // Public method to fetch client for update
    public function edit_fetch_client()
    {
        $id = $this->input->post('id');
        echo json_encode($this->crud_module_model->fetch_client($id));
    }

    // Changing user active status
    public function change_status($id, $status)
    {
        $this->crud_module_model->update_status($id, $status);
    }

    // Deleting clients
    public function delete_client()
    {
        $id = $this->input->post('id');
        $res = $this->crud_module_model->delete_client($id);
        echo json_encode($res);

    }
}

/* End of file Crud_module.php */