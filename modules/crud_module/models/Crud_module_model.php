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

    public function insert_client($data)
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
        return $this->db->insert('tblclients', $data_arr);
    }

    public function fetch_clients($id)
    {
        if ($id === null) {
            return $this->db->select('userid, company, phonenumber, country as country_id, tblcountries.short_name as country, city, zip, website, active')
                ->from('tblclients')
                ->join('tblcountries', 'tblclients.country=tblcountries.country_id')
                ->get()
                ->result_array();
        } else {
            return $this->db->select('userid, company, phonenumber, country as country_id, tblcountries.short_name as country, city, zip, website, active')
                ->from('tblclients')
                ->join('tblcountries', 'tblclients.country=tblcountries.country_id')
                ->where('userid', $id)
                ->get()
                ->result_array();
        }
    }

    public function update_client($data)
    {
        $id = $data['id'];
        $data_arr = [
            'company' => $data['cmp_nm'],
            'phonenumber' => $data['phone_no'],
            'country' => $data['country'],
            'city' => $data['city'],
            'zip' => $data['zip'],
            'website' => $data['website'],
            'active' => isset($data['is_active']) ? 1 : 0,
        ];
        return $this->db->update('tblclients', $data_arr, ['userid'=> $id]);

    }

    public function update_status($data){
        return $this->db->update('tblclients',['active'=>$data['status']], ['userid'=> $data['id']]);
    }

    public function delete_client($id)
    {
        return $this->db->delete('tblclients', ['userid' => $id]);
    }
}