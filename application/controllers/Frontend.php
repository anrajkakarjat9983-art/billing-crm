<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frontend extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_model');
        $this->load->helper('string');
    }

    public function view_invoice($id)
    {
        $data['title'] = lang('invoice_details');
        $id = url_decode($id);
        $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
        $data['client_info'] = $this->invoice_model->check_by(array('client_id' => $data['invoice_info']->client_id), 'tbl_client');

        $lang = $this->invoice_model->all_files();
        foreach ($lang as $file => $altpath) {
            $shortfile = str_replace("_lang.php", "", $file);
            //CI will record your lang file is loaded, unset it and then you will able to load another
            //unset the lang file to allow the loading of another file
            if (isset($this->lang->is_loaded)) {
                $loaded = sizeof($this->lang->is_loaded);
                if ($loaded < 3) {
                    for ($i = 3; $i <= $loaded; $i++) {
                        unset($this->lang->is_loaded[$i]);
                    }
                } else {
                    for ($i = 0; $i <= $loaded; $i++) {
                        unset($this->lang->is_loaded[$i]);
                    }
                }
            }
            if (!empty($data['client_info']->language)) {
                $language = $data['client_info']->language;
            } else {
                $language = 'english';
            }
            $data['language_info'] = $this->lang->load($shortfile, $language, TRUE, TRUE, $altpath);
        }

        $data['subview'] = $this->load->view('frontend/invoice/invoice_details', $data, TRUE);
        $this->load->view('frontend/_layout_main', $data);
    }

    public function pdf_invoice($id)
    {
        $data['title'] = "Invoice PDF"; //Page title
        // get all invoice info by id
        $data['invoice_info'] = $this->invoice_model->check_by(array('invoices_id' => $id), 'tbl_invoices');
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('frontend/invoice/invoice_pdf', $data, TRUE);
        pdf_create($viewfile, 'Invoice  # ' . $data['invoice_info']->reference_no);
    }


}
