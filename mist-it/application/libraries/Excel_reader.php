<?php
class Excel_reader{

	var $file_handler = NULL;

	public function __construct($init_data)
	{
		
		$file_name = $init_data['file_name'];

		require_once APPPATH.'third_party/simplexlsx.class.php';

		$this->file_handler = new SimpleXLSX($file_name);

	}
}
?>

