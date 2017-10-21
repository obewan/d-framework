<?php


$g_phpDir = "php";
if(!is_dir($g_phpDir)) $g_phpDir = "../../" . $g_phpDir;  //for ajax calls
if(!is_dir($g_phpDir)) error_log("Fatal error : php directory not found");

include_once 'interface/IDataController.php';
include_once $g_phpDir . '/data_model/DataMenuXML.php';
include_once $g_phpDir . '/data_model/DataPagesXML.php';

class XMLDataController implements IDataController {      
    private $xpaths;
    private $menu = NULL;
    private $pages = NULL;
    
    public function __construct (){
    	$this->menu = new DataMenuXML();
    	$this->menu->load(NULL);
   	}

   /**
    * 
    * {@inheritDoc}
    * @see IDataController::getMenu()
    */
    public function getMenu(){    	
        return $this->menu;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see IDataController::getDefaultPage()
     */
    public function getDefaultPage(){
    	if(is_null($this->menu)){
    		error_log("DataController::getDefaultPage error : null menu");
    		return null;
    	}
    	$defaultSection = null;
    	$hasDefault = false;
    	$groups = $this->menu->getGroups();
    	foreach($groups as $group){
    		$sections = $group->getSections();    		
    		foreach($sections as $section){
    			if($section->isDefault()){
    				$defaultSection = $section;
    				$section->setActive ( true );
    				$hasDefault = true;
    				break;
    			}
    		}
    		if($hasDefault){
    			break;
    		}
    	}
    	if(!$hasDefault){
    		error_log("DataController::getDefaultPage error : default section not found");
    		return null;
    	}
    	
    	$page = $this->getPage($defaultSection);
    	
    	return $page;
    	   	
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see IDataController::getMenuPage()
     */
    public function getMenuPage($title, $src, $action){
    	if(empty($title) || (empty($src) && empty($action))){
    		return NULL;
    	}
    	
    	$groups = $this->menu->getGroups();
    	foreach($groups as $group){
			$sections = $group->getSections ();
			foreach ( $sections as $section ) {
				if ($section->getTitle () == $title && 
						(
						    (!empty($src) && $section->getSrc() == $src) || 
							(!empty ($action) && $section->getAction() == $action)
						 )
					)
				{
					$section->setActive ( true );
					return $section;
				} else {
					$subsections = $section->getSubSections ();
					foreach ( $subsections as $subsection ) {
						if (
						    $subsection->getTitle () == $title && 
						       (
								    (!empty($src) && $subsection->getSrc() == $src) || 
								    (!empty($action) && $subsection->getAction() == $action)
						       )
						    )
						{
							$section->setActive ( true );
							$subsection->setActive ( true );
							return $subsection;
						} else {
							$pages = $subsection->getPages ();
							foreach ( $pages as $page ) {
								if (
								    $page->getTitle () == $title && 
								        (
										  (!empty($src) && $page->getSrc() == $src) || 
										  (!empty($action) && $page->getAction() == $action)
								        )
								    )
								{
									$section->setActive(true);
									$page->setActive ( true );
									return $page;
								}
							}
						}
					}
				}
			}
		}
    	
    	return NULL;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see IDataController::getPage()
     */
    public function getPage(DataMenuPage $menupage){
    	$src = $menupage->getSrc();
    	
    	$filename = $src . ".xml";
    	if (!file_exists($filename)) {
    		$filename = "xml/".$filename;
    	}
    	
    	$this->pages = new DataPagesXML($filename);
    	$this->pages->load(NULL);
    	$pages = $this->pages->getPages();
    	foreach($pages as $page){
    		if($page->getTitle() == $menupage->getTitle()){    			
    			return $page;
    		}
    	}
    	return NULL;
    }
    
   /**
    * 
    * {@inheritDoc}
    * @see IDataController::getAllPages()
    */
    public function getAllPages(DataMenuPage $menupage){
    	$src = $menupage->getSrc();
    	 
    	$filename = $src . ".xml";
    	if (!file_exists($filename)) {
    		$filename = "xml/".$filename;
    	}
    	 
    	$menupage->setActive(true);
    	 
    	$this->pages = new DataPagesXML($filename);
    	$this->pages->load(NULL);
    	
    	return $this->pages;
    }    
}
?>
