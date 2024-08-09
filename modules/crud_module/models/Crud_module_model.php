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
        return $this->db->get('tblcountries')->result();
    }

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
            $update = $this->db->update('tblclients', $data_arr,['userid' => $data['id']]);
            return ($this->db->affected_rows() > 0) ? ['status' => true, 'message' => _l('customer_added_successfully')] : ['status' => true, 'message' => _l('customer_not_added_successfully')];
        } else {
            $insert = $this->db->insert('tblclients', $data_arr);
            return ($insert) ? ['status' => true, 'message' => _l('customer_added_successfully')] : ['status' => true, 'message' => _l('customer_not_added_successfully')];
        }
    }

    // Fetching client using id
    public function fetch_client($id)
    {
        $client = $this->db->select('*')->from('tblclients')->where('userid', $id)->get()->result();
        return ($client) ? ['status' => true, 'client' => $client] : ['status' => false];
    }
    public function update_status($id, $status)
    {
        return $this->db->update('tblclients', ['active' => $status], ['userid' => $id]);
    }

    public function delete_client($id)
    {
        $this->db->delete('tblclients', ['userid' => $id]);

        return [
            'message' => ($this->db->affected_rows() > 0) ? _l('delete_successfully') : _l('something_went_wrong'),
        ];
    }
}