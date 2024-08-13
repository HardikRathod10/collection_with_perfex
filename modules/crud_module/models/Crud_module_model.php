<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crud_module_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Method to get all countries
    public function get_countries()
    {
        return $this->db->get('tblcountries')->result_array();
    }

    // Method to create or update client | will update if id provided
    public function save_client($data)
    {
        $data_arr = [
            'company' => $data['cmp_nm'],
            'phonenumber' => $data['phone_no'],
            'country' => $data['country'],
            'city' => $data['city'],
            'zip' => $data['zip'],
            'website' => $data['website'],
            'active' => isset($data['is_active']) ? 1 : 0,
        ];

        if (isset($data['id'])) {
            $update = $this->db->update('tblclients', $data_arr, ['userid' => $data['id']]);
            return ($this->db->affected_rows() > 0) ? ['status' => true, 'message' => _l('customer_updated_successfully')] : ['status' => true, 'message' => _l('customer_not_updated_successfully')];
        } else {
            $insert = $this->db->insert('tblclients', $data_arr);
            return ($insert) ? ['status' => true, 'message' => _l('customer_added_successfully')] : ['status' => true, 'message' => _l('customer_not_added_successfully')];
        }
    }

    // Fetching client using id
    public function fetch_client($id)
    {
        $client = $this->db->get_where('tblclients', ['userid' => $id]);
        return ($client->num_rows() > 0) ? ['status' => true, 'client' => $client->result()] : ['status' => false];
    }

    // Method to update client's active status
    public function update_status($id, $status)
    {
        return $this->db->update('tblclients', ['active' => $status], ['userid' => $id]);
    }

    // Method to delete client
    public function delete_client($id)
    {
        $this->db->delete('tblclients', ['userid' => $id]);

        return [
            'message' => ($this->db->affected_rows() > 0) ? _l('delete_successfully') : _l('something_went_wrong'),
        ];
    }

}