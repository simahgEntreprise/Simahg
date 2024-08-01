<?php  
class control_sesion extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
 
	public function removeCache()
	{
		// Headers para no-cache
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
	}
}
