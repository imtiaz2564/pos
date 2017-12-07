<?php
if(!function_exists('script_tag')){
	function script_tag($file){
		return '<script type="text/javascript" src="'.base_url($file).'"></script>';
	}
}
