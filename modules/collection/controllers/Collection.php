<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Collection extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('collection_model');
        $this->load->helper('collection');
    }

    // Method to loading initial collection view
    public function collection_view()
    {
        $data['title'] = _l('invoices');
        $invoices = $this->collection_model->get_invoices();
        $data["invoices"] = $invoices;
        $this->load->view('filter_view', $data);
    }

    // Method to filter invoices based on spcefied action using collection and collection methods
    public function apply_collection_action()
    {
        $action = $this->input->post('action');
        $invoices = collect($this->collection_model->get_invoices());
        if ($invoices->isNotEmpty()) {
            // switch ($action) {
            //     case 'filter':
            //         echo json_encode(['status' => true, 'invoices' => $invoices->filter(fn($invoice) => $invoice['status'] == 2), 'action' => $action]);
            //         return;

            //     case 'where':
            //         echo json_encode(['status' => true, 'invoices' => $invoices->where('status', 1), 'action' => $action]);
            //         return;

            //     case 'where-more':
            //         echo json_encode(['status' => true, 'invoices' => $invoices->where('total', '>=', 1000), 'action' => $action]);
            //         return;

            //     case 'where-in':
            //         echo json_encode(['status' => true, 'invoices' => $invoices->whereIn('total', [500, 700]), 'action' => $action]);
            //         return;

            //     case 'where-between':
            //         echo json_encode(['status' => true, 'invoices' => $invoices->whereBetween('total', [400, 800]), 'action' => $action]);
            //         return;

            //     case 'first':
            //         echo json_encode(['status' => true, 'invoices' => $invoices->first(), 'action' => $action]);
            //         return;

            //     case 'last':
            //         echo json_encode(['status' => true, 'invoices' => $invoices->last(), 'action' => $action]);
            //         return;

            //     default:
            //         echo json_encode(['status' => false]);
            //         return;
            // }

            // Short-hand for switch and if-elseif 
            echo match ($action) {
                'filter' => json_encode(['status' => true, 'invoices' => $invoices->filter(fn($invoice) => $invoice['status'] == 2), 'action' => $action]),
                'where' => json_encode(['status' => true, 'invoices' => $invoices->where('status', 1), 'action' => $action]),
                'where-more' => json_encode(['status' => true, 'invoices' => $invoices->where('total', '>=', 1000), 'action' => $action]),
                'where-in' => json_encode(['status' => true, 'invoices' => $invoices->whereIn('total', [500, 700]), 'action' => $action]),
                'where-between' => json_encode(['status' => true, 'invoices' => $invoices->whereBetween('total', [400, 800]), 'action' => $action]),
                'first' => json_encode(['status' => true, 'invoices' => $invoices->first(), 'action' => $action]),
                'last' => json_encode(['status' => true, 'invoices' => $invoices->last(), 'action' => $action])
            };
        } else {
            echo json_encode(['status' => false]);
        }
    }
}

/* End of file Collection.php */