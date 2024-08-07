<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Collection_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Method to get all invoices with client name by joining clients table.
    public function get_invoices()
    {
        $this->db->select('id,date,duedate,total,status, tblclients.company as client')
        ->join('tblclients','tblinvoices.clientid = tblclients.userid');
        $query = $this->db->get('tblinvoices');
        return $query->result_array();    
    }
}