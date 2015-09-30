<?php
class tmp 
{
	private $path;
	private $variables = array();
	public function __construct() 
	{
		$this->path = ROOT. "/tmp";

	}
	//Assign values to variables 
	private function assign($name, $value) 
	{
		$this->variables[$name]=$value;
	}

	public function display($file_include)
	{
		if(!file_exists($this->path.'/'.$file_include))
		{
			
			throw new Exception("Template file not found");

		}
		require $this->path.'/'.$file_include;


	}

	public function __get($name)
	{
		if(isset($this->variables[$name]))
		{
			$variables = $this->variables[$name];
			return $variables;
		}
		return false;
	}


}