<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {
    function __construct(){
        parent::__construct();        

        $this->load->library('ion_auth');
		
        if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
        $this->load->library('crud');
        $this->load->helper(['html_helper','item_helper','common_helper']);
        $this->load->library('crud','','crud');
        //$this->output->enable_profiler(TRUE);
    }
	public function index(){
        $data['title'] = 'Delivery Type';
        $this->crud->init('uom',[
            'uom' => 'Delivery Type',
            'labourCost' => 'Labour Cost',
        ]);
        $this->crud->use_modal();
        $this->crud->set_rule('uom','required');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }

}