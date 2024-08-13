<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save_customer($data, $file)
    {
        $data_arr = [
            'name' => $data['name'],
            'country' => $data['country'],
            'city' => $data['city'],
            'phone' => $data['phone_no'],
            'profile_pic' => $file
        ];

        $insert = $this->db->insert(db_prefix().'customers', $data_arr);
        return ($insert) ? ['status' => true, 'message' => _l('customer_added_successfully')] : ['status' => true, 'message' => _l('customer_not_added_successfully')];

    }
}
?>