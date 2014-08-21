<?php

include('Parsedown.php');

class mdfile{
    public $parse = null;
    public $mdconf = array(
        'nginx' => array(   
			'title' => 'NGINX简介',
            'markdown' => 'nginx.md',
        ),
        'life' => array(
			'title' => 'my life',
            'markdown' => 'life.md',
        ),
    ); 
    public function __construct()
    {
        $this->parse = new Parsedown();
    }
    
    public function getAllMdfiles()
    {
        return $this->mdconf;
    }
	
	public function getMdinfoByMdname($md_name)
	{
		if ( !isset($this->mdconf[$md_name]) )
            return false;
        return $this->mdconf[$md_name];
	}
    public function getMdtoHtml($md_name)
    { 
	    $md_info=$this->getMdinfoByMdname($md_name);
		if($md_info == false)
		{
			return false;
		}
		$md_file = $md_info['markdown'];
        $md_path = "../mdfile/$md_file";
        if ( !file_exists($md_path) )
            return false;

        $markdown = file_get_contents($md_path);
        return $this->parse->text($markdown);
    }
}
