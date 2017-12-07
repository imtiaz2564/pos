<?php
if(!function_exists('get_text_field')){
	function get_text_field($field,$value,$extra=''){
		return form_input($field,$value,'class="form-control" '.$extra);
	}
}

if(!function_exists('get_textarea_field')){
	function get_textarea_field($field,$value,$extra=''){
		return form_textarea($field,$value,'class="form-control" '.$extra);
	}
}

if(!function_exists('get_hidden_field')){
	function get_hidden_field($field,$value){
		return form_hidden($field,$value);
	}
}

if(!function_exists('get_date_field')){
	function get_date_field($field,$value){
		return form_input($field,$value,'class="form-control date" data-date-format="YYYY-MM-DD"');
	}
}

if(!function_exists('get_datetime_field')){
	function get_datetime_field($field,$value){
		return form_input($field,$value,'class="form-control datetime" data-date-format="YYYY-MM-DD hh:mm A"');
	}
}

if(!function_exists('get_password_field')){
	function get_password_field($field,$value){
		return form_password($field,$value,'class="form-control"');
	}
}

if(!function_exists('get_dropdown_field')){
	function get_dropdown_field($field,$options,$selected){
		return form_dropdown($field,$options,$selected,'class="form-control"');
	}
}

if(!function_exists('get_upload_field')){
	function get_upload_field($field,$value){
		return form_upload($field,$value,'class="form-control"');
	}
}

if(!function_exists('get_submit_button')){
	function get_submit_button($value){
		return form_submit('',$value,'class="btn btn-primary"');
	}
}

if(!function_exists('get_insert_button')){
	function get_insert_button($modal){
		$ci =& get_instance();
        if($modal == true)
		return anchor($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/insert/','<i class="fa fa-plus"></i> Add New','class="btn btn-default btn-sm" data-toggle="modal" data-target="#crudModal" id="crud-insert"');
		return anchor($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/insert/','<i class="fa fa-plus"></i> Add New','class="btn btn-default btn-sm" id="crud-insert"');
	}
}

if(!function_exists('get_edit_button')){
	function get_edit_button($id,$modal){
		$ci =& get_instance();
        if($modal == true)
		return anchor($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/edit/'.$id,'<i class="fa fa-pencil"></i> Edit','class="btn btn-default btn-sm" data-toggle="modal" data-target="#crudModal" id="crud-edit"');
		return anchor($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/edit/'.$id,'<i class="fa fa-pencil"></i> Edit','class="btn btn-default btn-sm" id="crud-edit"');
	}
}

if(!function_exists('get_delete_button')){
	function get_delete_button($id){
		$ci =& get_instance();
		return anchor($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/delete/'.$id,'<i class="fa fa-trash-o"></i> Delete','class="btn btn-danger btn-sm" id="crud-delete"');
	}
}
if(!function_exists('get_view_button')){
	function get_view_button($id,$modal){
		$ci =& get_instance();
		return anchor($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/view/'.$id,'<i class="fa fa-file"></i> View','class="btn btn-default btn-sm" id="crud-view"');
	}
}
if(!function_exists('get_insert_url')){
	function get_insert_url(){
		$ci =& get_instance();
		return site_url($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/insert/');
	}
}
if(!function_exists('get_edit_url')){
	function get_edit_url($id){
		$ci =& get_instance();
		return site_url($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/edit/'.$id);
	}
}
if(!function_exists('get_delete_url')){
	function get_delete_url($id){
		$ci =& get_instance();
		return site_url($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/delete/'.$id);
	}
}
if(!function_exists('get_view_url')){
	function get_view_url($id){
		$ci =& get_instance();
		return site_url($ci->uri->segment(1).'/'.$ci->uri->segment(2).'/view/'.$id);
	}
}
?>