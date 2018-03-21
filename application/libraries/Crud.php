<?php
/*
┌────────────────────────────────────────────────────────────────────┐
│ CRUD Automation Library based on Codeigniter                       │
├────────────────────────────────────────────────────────────────────┤
│ Author: Muhitur Rahman © 2014                                      │
├────────────────────────────────────────────────────────────────────┤
│ Email: muhitbd@gmail.com                                           │
├────────────────────────────────────────────────────────────────────┤
│ Last Edit: 21 November 2015                                        │
└────────────────────────────────────────────────────────────────────┘
*/
class Crud{
	public $ci;
	
    private $fields;
	private $hidden_fields;
    private $default_fields = []; 
	private $display_fields;
	private $extra_fields;
	private $search_field = null;
	
    private $where;
	private $types;
	private $order;
	
    private $form_extra = null;
	
    private $relations;
	private $options = [];
    
    private $custom_list = null;
	private $custom_form = null;
	private $custom_view = null;
    
	private $child=null;

    private $before_save=null;
	private $after_save=null;
	private $before_update=null;
    private $after_update=null;
    private $after_delete=null;
	private $before_list=null;
    
		
	private $has_upload=false;
	private $upload_config = array(
        'upload_path'   => './uploads',
        'allowed_types' => 'gif|jpg|png|jpeg',
        'max_size'      => '2048',
        'encrypt_name'  => 'true'
    );
    private $sizes = null;
    private $use_modal = false;
    private $ajax_list = false;
	
    // frontend data
    
    private $data = [];
    
    
    
	function __construct(){
		$this->ci =& get_instance();
		$this->ci->load->helper('form');
		$this->ci->load->helper('url');
        $this->ci->load->database();
		$this->ci->load->model('crud_model');
		$this->ci->load->helper('crud');
		$this->ci->load->library('form_validation');
		$this->ci->load->library('session');

        $this->data['modal'] = false;
        $this->data['insert'] = true;
        $this->data['update'] = true;
        $this->data['delete'] = true;
        $this->data['hide_controls'] = false;
	}
	function init($table,$fields){
		$this->ci->crud_model->set_table($table);
		
        foreach($fields as $field => $label){
			$this->fields[$field] = $label;
			$this->types[$field] = 'text'; // all are text type first
		}
		$this->data['primary_key']=$this->ci->crud_model->getPrimaryKey();
	}
	function run($force_action=null){
		$action=$this->ci->uri->segment(3);
		
		if($action=='ajax'){
            return $this->ajax_data();
        }elseif($action=='insert'){
			return $this->form();
		}elseif($action=='edit'){
			return $this->form($this->ci->uri->segment(4));
		}elseif($action=='view'){
			return $this->view($this->ci->uri->segment(4));
		}elseif($action=='save'){
			return $this->save();
		}elseif($action=='update'){
			return $this->save($this->ci->uri->segment(4));
		}elseif($action=='delete'){
			return $this->delete($this->ci->uri->segment(4));
        }else{
			return $this->list_data();        
        }
	}
	function list_data(){
		// we will need the data to play with.
        $query = $this->ci->crud_model->get_data($this->fields,$this->relations,$this->options,$this->where,$this->search_field);
		$this->data['rows'] = $query->result_array();
        
        // get the core fields' label
        foreach($this->fields as $field => $label){
            $this->data['labels'][] = $label;
        }
        // get extra fields' label
        if(is_array($this->extra_fields)){
            foreach($this->extra_fields as $callback => $label){
                $this->data['labels'][] = $label;
            }
        }
        // if fields are defined        
        if(is_array($this->display_fields)){
            $this->data['labels'] = $this->display_fields;
        }
		
        //$this->data['extra_fields'] = $this->extra_fields;

        // lets push extra fields with each row
        if(is_array($this->extra_fields)){
            $tmp=[];
            foreach($this->data['rows'] as $row){
                foreach($this->extra_fields as $callback=>$label){
                    if(method_exists($this->child,$callback)){
                        $row[$label]=$this->child->$callback($row[$this->data['primary_key']]);
                    }
                    array_push($tmp,$row);
                }
            }
            $this->data['rows'] = $tmp;
        }
        
        if($this->custom_list==null){
            if($this->ajax_list == false){
                return $this->ci->load->view('crud/list',$this->data,true);
            }else{
                echo $this->ci->load->view('crud/list',$this->data,true); die();            
            }
        }else{
            if($this->ajax_list == false){
                return $this->ci->load->view($this->custom_list,$this->data,true);
            }else{
                echo $this->ci->load->view($this->custom_list,$this->data,true); die();            
            }
        }
	}
	function ajax_data(){
        // Formatted for DataTables
        $query = $this->ci->crud_model->get_data($this->fields,$this->relations,$this->options,$this->where,$this->search_field);
        $data_container = [];
        
        // get the core fields' label
        foreach($this->fields as $field => $label){
            $labels[] = $label;
        }
        // get extra fields' label
        if(is_array($this->extra_fields)){
            foreach($this->extra_fields as $callback => $label){
                $labels[] = $label;
            }
        }
        // if fields are defined        
        if(is_array($this->display_fields)){
            $labels = $this->display_fields;
        }
        $total=0;
        foreach($query->result_array() as $row){
            $tmp[] = $row[$this->data['primary_key']];
            foreach($labels as $label){
                if(array_key_exists($label,$row)){
                    $tmp[] = $row[$label];
                }else{ // it must be an extra field
                    if(is_array($this->extra_fields)){ // do we really have extra fields?
                        foreach($this->extra_fields as $callback => $extraLabel){ // loop through all extra fields and get the correct one
                            if($extraLabel == $label){
                                if(method_exists($this->child,$callback)){ // does the callback exists?
                                    $tmp[]=$this->child->$callback($row[$this->data['primary_key']]);
                                }
                            }
                        }
                    }
                }
            }
            array_push($data_container,$tmp);
            $tmp = []; // reset
            $total++;
        }
        
		$data['data'] = $data_container;
        $data['draw'] = $this->ci->input->get('draw');
		$data['recordsTotal'] = $total;
		$data['recordsFiltered'] = $this->ci->crud_model->get_total($this->relations,$this->where);
        echo json_encode($data);
        die();
	}
	function join($field,$table,$key,$value,$where=''){
		$this->relations[]=array(
            'field'     =>$field,
            'table'     =>$table,
            'key'       =>$key,
            'value'     =>$value,
            'label'     =>$this->fields[$field],
            'where'     =>$where,
        );
        unset($this->types[$field]); // remove this from field list
	}
	function set_option($field, $options){
		$this->options[]=[
            'field'=>$field,
            'label'=>$this->fields[$field],
            'options'=>$options
        ];
        unset($this->types[$field]); // remove this from field list
	}
	function set_rule($field,$rule){
		$this->ci->form_validation->set_rules($field,$this->fields[$field],$rule);
	}
	function set_message($rule,$message){
		$this->ci->form_validation->set_message($rule,$message);
	}
	function form($id=null){
		if($id!=null){
			$form_data = $this->ci->crud_model->getDataById($id);
		}else{
			foreach($this->fields as $field => $type){
				//$form_data[$field]=$this->ci->input->post($field);/* loads the previous input */
                if($this->ci->input->post( $field ) == ''){ # if there is no previous data
                    //if(array_key_exists($this->fields['defaults'][$field])) # if there is any default value
                    if(array_key_exists($field , $this->default_fields)) # if there is any default value
                        //$form_data[$field] = $this->fields['defaults'][$field];
                        $form_data[$field] = $this->default_fields[$field];
                    else
                        $form_data[$field] = '';
                }else{
				    $form_data[$field]=$this->ci->input->post($field); # loads the previous input
                }
			}
		}
		$i=0;
		/* generate fields for each field data */
		foreach($this->types as $field => $type){
            
            // skip, if it's a hidden field.
            if(is_array($this->hidden_fields)){
                if(array_key_exists($field,$this->hidden_fields)){
                    continue;
                }
            }
            
            $inputs[$i]['label']=$this->fields[$field];
            
            if($type=='text'){
                $inputs[$i]['html']=get_text_field($field,$form_data[$field],'placeholder="'.$this->fields[$field].'"');
            }elseif($type=='textarea'){
                $inputs[$i]['html']=get_textarea_field($field, $form_data[$field], 'placeholder="'.$this->fields[$field].'"');
            }elseif($type=='datetime'){
                $inputs[$i]['html']=get_datetime_field($field,$form_data[$field]);
            }elseif($type=='date'){
                $inputs[$i]['html']=get_date_field($field,$form_data[$field]);
            }elseif($type=='password'){
                $inputs[$i]['html']=get_password_field($field,$form_data[$field]);
            }elseif($type=='upload'){
                $inputs[$i]['html']=get_upload_field($field,$form_data[$field]);
            }
            $i++;
		}
		/* generate dropdown fields for each relation */
		if(is_array($this->relations)){
			foreach($this->relations as $relation){
				$options=$this->ci->crud_model->get_options($relation);
				$inputs[$i]['label']=$relation['label'];
				$inputs[$i]['html']=get_dropdown_field($relation['field'],$options,$form_data[$relation['field']]);
				$i++;
			}
		}
		/* generate dropdown fields for each given options */
        foreach($this->options as $option){
            $options=$option['options'];
            $inputs[$i]['label']=$this->fields[$option['field']];
            $inputs[$i]['html']=get_dropdown_field($option['field'],$options,$form_data[$option['field']]);
            $i++;
        }
		/* generate hidden fields */
		if(is_array($this->hidden_fields)){
			foreach($this->hidden_fields as $field => $value ){
				$inputs[$i]['label']='';
				$inputs[$i]['html']=get_hidden_field($field,$value);
				$i++;
			}
		}
        /* re-order */
        if(is_array($this->order)){
            $tmp=[];
            foreach($this->order as $i){
                $tmp[$i] = $inputs[$i];
            }
            $inputs=$tmp;
        }
        
        $uri = $this->ci->uri->segment(1).'/'.$this->ci->uri->segment(2);
		if($id==null){
			if($this->has_upload==false)
                $this->data['form_open']=form_open($uri.'/save/',$this->form_extra);
            else
                $this->data['form_open']=form_open_multipart($uri.'/save/',$this->form_extra);
		}else{
			if($this->has_upload==false)
                $this->data['form_open']=form_open($uri.'/update/'.$id,$this->form_extra);
            else
                $this->data['form_open']=form_open_multipart($uri.'/update/'.$id,$this->form_extra);            
		}
		$this->data['form_close']=form_close();
		if($id==null){
			$this->data['submit']=get_submit_button('Save');
		}else{
			$this->data['submit']=get_submit_button('Update');
		}
		$this->data['inputs']=$inputs;
		
		if(!isset($this->data['error'])) $this->data['error']='';
		
		if($this->custom_form==null){
            if($this->use_modal == false){
                return $this->ci->load->view('crud/form',$this->data,true);
            }else{ // we are using modal
                echo $this->ci->load->view('crud/modal_form',$this->data,true);
                die();
            }
		}else{
            if($this->use_modal == false ){
                return $this->ci->load->view($this->custom_form,$this->data,true);
            }else{
                echo $this->ci->load->view($this->custom_form,$this->data,true);
                die();
            }
		}
	}
    function view( $id ){
        $data['row'] = $this->ci->crud_model->getViewDataById( $this->fields, $this->relations, $this->options, $id )->row_array();
        
        // get the core fields' label
        foreach($this->fields as $field => $label){
            $data['labels'][] = $label;
        }
        // if fields are defined        
        if(is_array($this->display_fields)){
            $data['labels'] = $this->display_fields;
        }
        
        if($this->custom_view == null){
            return $this->ci->load->view('crud/view', $data, true);
        }else{
            return $this->ci->load->view($this->custom_view, $data, true);
        }
    }
	function save($id=null){
		if($this->ci->input->post()){
			$this->data = $this->ci->input->post();
			if($this->ci->form_validation->run()==true){
				if($id==null){//new entry
                    
                    // Check if uploads available
                    
                    if($this->has_upload == true){
                        foreach($this->fields as $fields => $type){
                            if($type == 'upload'){
                                $this->data[$field] = $this->upload($field);
                            }
                        }
                    }
                    
                    if($this->before_save!=null){
						$func=$this->before_save;
						if(method_exists($this->child,$func)){
							$this->data=$this->child->$func($this->data);
						}
					}
					
					if($this->ci->crud_model->saveData($this->data)){
						if($this->after_save!=null){
							$func=$this->after_save;
							if(method_exists($this->child,$func)){
								$this->data=$this->child->$func($this->data);
							}
						}
                        // Always throw JSON
                        //if($this->use_modal == true ){
                            $this->data['success'] = true;
                            echo json_encode($this->data); die();
                        //}else{
                        //    $this->ci->session->set_flashdata('success','Saved!');
                        //    redirect($this->ci->uri->segment(1).'/'.$this->ci->uri->segment(2));
                        //}
					}else{
                        $this->data['error']=$this->ci->db->_error_message();
                        
                        //if($this->use_modal == true ){
                            echo json_encode($this->data); die();
                        //}else{
                        //    return $this->form();
                        //}
					}
				}else{// update a data
                    
                    // check if uploads available
                    if($this->has_upload == true){
                        foreach($this->fields as $field => $type){
                            if($type == 'upload'){
                                if(!empty($_FILES[$field]['name'])) // did the user choose a new file?
                                    $this->data[$field] = $this->upload($field);
                            }
                        }
                    }
                    
					if($this->before_update!=null){
						$func=$this->before_update;
						if(method_exists($this->child,$func)){
							$this->data=$this->child->$func($this->data);
						}
					}
				
					if($this->ci->crud_model->updateData($id,$this->data)){
						if($this->after_update!=null){
							$func=$this->after_update;
							if(method_exists($this->child,$func)){
								$this->data=$this->child->$func($this->data);
							}
						}
                        //if($this->use_modal == true ){
                            $this->data['success'] = true;
                            echo json_encode($this->data); die();
                        //}else{
                        //    $this->ci->session->set_flashdata('success','Updated!');
                        //    redirect($this->ci->uri->segment(1).'/'.$this->ci->uri->segment(2));
                        //}
					}else{
						$this->data['error']=$this->ci->db->_error_message();

                        //if($this->use_modal == true ){
                            echo json_encode($this->data); die();
                        //}else{
                        //    return $this->form($id);
                        //}
					}
				}
			}else{
                $this->data['error'] = validation_errors();
                
                if($this->use_modal == true){
                    echo json_encode($this->data); die();
                }else{
                    return $this->form();
                }
			}
		}
	}
    function upload($field){
        if(!is_dir($this->upload_config['upload_path'])){
            mkdir($this->upload_config['upload_path'], 0755, true);
        }
        $this->ci->load->library('upload', $this->upload_config);
        if(!$this->ci->upload->do_upload($field)){
            $this->ci->session->set_flashdata('error',$this->ci->upload->display_errors());
        }else{
            $upload_data = $this->ci->upload->data();
            $this->ci->load->library('image_lib');
            foreach($this->sizes as $size){
                if(!is_dir($this->upload_config['upload_path'].'/'.$size['folder'])){
                    mkdir($this->upload_config['upload_path'].'/'.$size['folder'], 0755, true);
                }
                $master_dim = 'auto';
                if($size['crop'] == true){
                    $dim = (intval($upload_data["image_width"]) / intval($upload_data["image_height"]))
                            - ($size['width'] / $size['height']);
                    $master_dim = ($dim > 0)? "height" : "width";
                }
                $this->ci->image_lib->initialize(array(
                    'image_library' => 'gd2',
                    'source_image' => $upload_data['full_path'],
                    'new_image' => $this->upload_config['upload_path'].'/'.$size['folder'],
                    'maintain_ratio' => true,
                    'width' => $size['width'],
                    'height' => $size['height'],
                    'master_dim' => $master_dim,
                ));
                $this->ci->image_lib->resize();
                if($size['crop']==true){
                    $this->ci->image_lib->clear();
                    $this->ci->image_lib->initialize(array(
                        'image_library' => 'gd2',
                        'source_image' => $this->upload_config['upload_path'].'/'.$size['folder'].'/'.$upload_data['file_name'],
                        'new_image' => $this->upload_config['upload_path'].'/'.$size['folder'].'/'.$upload_data['file_name'],
                        'maintain_ratio' => false,
                        'width' => $size['width'],
                        'height' => $size['height']
                    ));
                    $this->ci->image_lib->crop();
                }
                $this->ci->image_lib->clear();
            }
            return $upload_data['file_name'];
        }
    }
	function delete($id){
		if($this->ci->crud_model->deleteData($id)){
           
            if($this->after_delete != null){
                $func=$this->after_delete;
                if(method_exists($this->child,$func)){
                    $this->data=$this->child->$func($this->data);
                }
            }
            redirect($this->ci->uri->segment(1).'/'.$this->ci->uri->segment(2));
		}
	}
	function push_data($array){
		foreach($array as $key=>$value)
			$this->data[$key]=$value;
	}
	function custom_list($file){
        $this->custom_list = $file;
	}
	function custom_form($file){
        $this->custom_form = $file;
	}
	function custom_view($file){
        $this->custom_view = $file;
	}
	function before_save($object,$function){
		$this->child=$object;
		$this->before_save=$function;
	}
	function after_save($object,$function){
		$this->child=$object;
		$this->after_save=$function;
	}
	function before_update($object,$function){
		$this->child=$object;
		$this->before_update=$function;
	}
	function after_update($object,$function){
		$this->child=$object;
		$this->after_update=$function;
    }
    function after_delete($object,$function){
		$this->child=$object;
		$this->after_delete=$function;
	}
	function change_type($field,$type){
		$this->types[$field]=$type;
        if($type == 'upload')
            $this->has_upload = true;
	}
	function set_hidden($field,$value){
		$this->hidden_fields[$field]=$value;
	}
    function set_default($field,$value){
        $this->default_fields[$field]=$value;
    }
	function where($conditions){
		$this->where=$conditions;
	}
	function order($order){
		$this->order=$order;
	}
	function display_fields($fields){
		$this->display_fields=$fields;
	}
	function extra_fields($object,$fields){
        $this->child = $object;
		$this->extra_fields=$fields;
	}
    function upload_config($key,$value){
        $this->upload_config[$key]=$value;
    }
    function add_size($folder,$width,$height,$crop=false){
        $this->sizes[]=array(
            'folder'=>$folder,
            'width'=>$width,
            'height'=>$height,
            'crop'=>$crop
        );
    }
    function use_modal(){
        // no need to use two variable. fix that.
        $this->use_modal = true;
        $this->data['modal'] = true;
    }
    function ajax_list(){
        $this->ajax_list = true;
    }
    function form_extra($extra){
        $this->form_extra = $extra;
    }
    function disable_insert(){
        $this->data['insert'] = false;
    }
    function disable_update(){
        $this->data['update'] = false;
    }
    function disable_delete(){
        $this->data['delete'] = false;
    }
    function set_search($field){
        $this->search_field = $field;
    }
    function hide_controls(){
        $this->data['hide_controls'] = true;
    }
	function extra_buttons($buttons){
		$this->data['extraButtons']=$buttons;
	}
    
}