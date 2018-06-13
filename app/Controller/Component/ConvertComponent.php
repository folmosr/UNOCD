<?php
App::uses('Component', 'Controller');
class ConvertComponent extends Component {
	
	var $encrypt;
	var $data; 
	
	function __construct()
	{
		  	 App::import('Vendor', 'encode', array('file' => 'encode'.DS.'class.encode.php')); 
			 $this->encrypt = new Encode();
	}
	
	function encode($a)
	{
		return($this->encrypt-> AsciiToHex(base64_encode($a)));	
	}

	function decode($a)
	{
		return(base64_decode($this->encrypt->HexToAscii($a)));	
	}
	
}
?>