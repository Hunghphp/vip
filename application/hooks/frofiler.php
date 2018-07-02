<?php
function frofiler_hook(){

	$CI =& get_instance();
	$ip = $CI->input->ip_address();
/*	if($ip == '127.0.0.1'){
		echo '<hr><hr>';
		$CI->output->enable_profiler(TRUE);
	}*/
	

	
}
