<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
    function __construct(){
        parent::__construct();        

        $this->load->library('ion_auth');
		
        if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
        $this->load->library('crud');
        $this->load->helper('html_helper');
        $this->load->helper('common_helper');
        $this->load->library('crud','','crud');
        $this->load->model('item_model');
    }
    public function index(){
        redirect('item/index');
    }
    function paymentreport() {
        $this->load->model('item_model');
        $data['title'] = ' ';
        $data['people'] = $this->item_model->getPeople();
        $data['content'] = $this->load->view('Reports/paymentReport.php',$data,true);
        $this->load->view('template',$data);

    }
    function getpaymentReport($fromDate , $toDate){
        $this->load->model('item_model');
        $data['paymentData'] = $this->item_model->getPaymentData($fromDate , $toDate); 
        $this->load->view('Reports/PaymentReportDetails.php',$data);
    }
    function customertransaction() {
        $data['title'] = ' ';
        $data['content'] = $this->load->view('Reports/TransactionReport.php',$data,true);
        $this->load->view('template',$data);
    }
    function getCustomerTransactionData($fromDate , $toDate){
        $this->load->model('item_model');
        $data['paymentData'] = $this->item_model->getCustomerTransaction($fromDate , $toDate); 
        $this->load->view('Reports/CustomerTransactionReport.php',$data);
    }
    function importreport(){
        $data['title'] = ' ';
        $data['content'] = $this->load->view('Reports/paymentReport.php',$data,true);
        $this->load->view('template',$data);
    }
    function getimportreport($fromDate , $toDate){
        $this->load->model('item_model');
        $data['importData'] = $this->item_model->getImportData($fromDate , $toDate); 
        $this->load->view('Reports/ImportReport.php',$data);
        
    }
    function salesreport(){
        $data['title'] = ' ';
        $data['content'] = $this->load->view('Reports/paymentReport.php',$data,true);
        $this->load->view('template',$data);
    }
    function getsalesreport($fromDate , $toDate){
        $this->load->model('item_model');
        $data['salesData'] = $this->item_model->getsalesreportData($fromDate , $toDate); 
        $data['salesItemData'] = $this->item_model->getsalesItemData($fromDate , $toDate); 
        $this->load->view('Reports/SalesReport.php',$data);
        
    }
}
 