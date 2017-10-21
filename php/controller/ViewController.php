<?php


$g_phpDir = "php";
if(!is_dir($g_phpDir)) $g_phpDir = "../../" . $g_phpDir;  //for ajax calls 
if(!is_dir($g_phpDir)) error_log("Fatal error : php directory not found");
    
include_once $g_phpDir . '/controller/interface/IDataController.php';
include_once $g_phpDir . '/controller/LoginController.php';
include_once $g_phpDir . '/view_model/ViewMenu.php';
include_once $g_phpDir . '/view_model/ViewLayout.php';
include_once $g_phpDir . '/view_model/ViewError.php';
include_once $g_phpDir . '/view_model/ListV.php';
include_once $g_phpDir . '/view_model/Accordion.php';
include_once $g_phpDir . '/view_model/AccordionMagellan.php';
include_once $g_phpDir . '/view_model/Tabulation.php';
include_once $g_phpDir . '/view_model/Content.php';

class ViewController {
   
    /**
     * Show basic view with static content
     * @param IView $view
     */
    public static function showStaticView(IView $view){
        echo $view->build();
    }
    
    /**
     * Show dynamic view with imported content
     * @param IView $view
     * @param IDataController $dataController the data source controller to use
     * @param string $title the title of the page
     * @param string $page the data page name (or empty but action name)
     * @param string $action the action name (or empty but page name)
     */
    public static function showDynamicView(
        IView $view, 
        IDataController $dataController, 
        $title, 
        $page, 
        $action)
    {
        $menupage = $dataController->getMenuPage($title, $page, $action);
        if (empty($menupage)) {
            $view = ViewController::getErrorPage("error", "page not found");
            echo $view->build();
            return;
        }
        
        $pagedata = $dataController->getPage($menupage);
        $view->load($pagedata);
        echo $view->build();
    }
    
    /**
     * Show the full layout page including menu and page view
     * @param IView $layout the layout view model to use
     * @param IDataController $dataController the data source controller to use
     */
    public static function showLayout(IView $layout, IDataController $dataController){
        $menuData = $dataController->getMenu ();
        
        //set menu view
        $menuModel = new ViewMenu ();
        $menuModel->load ($menuData);
        
        //get page view
        $pageModel = ViewController::getPageContent ($dataController);
        
        //set layout
        $layout->setMenuModel ( $menuModel );
        $layout->setPageModel ( $pageModel );
        
        echo $layout->build();
    }
    
    
    public static function has_access(DataMenuSection $section){
    	
    	$access = $section->getAccess();    
    	
    	if(empty($access) || $access == MENU_ACCESS_ALL_VALUE){
    		return true;
    	}
    			
		if (! LoginController::isLogged () 
				&& !empty($access)
		    && $access != MENU_ACCESS_ALL_VALUE) {
			return false;
		}
		
		$userRole = LoginController::getRole ();
				
		switch ($access) {
		    case MENU_ACCESS_ADMIN_VALUE :
		        if($userRole == MENU_ACCESS_ADMIN_VALUE){
					return true;	
				}else{
					return false;
				}
		    case MENU_ACCESS_MANAGER_VALUE :
		        if( $userRole == MENU_ACCESS_ADMIN_VALUE ||
		              $userRole == MENU_ACCESS_MANAGER_VALUE){
					return true;	
				}else{
					return false;
				}
		    case MENU_ACCESS_USER_VALUE :
		        if( $userRole == MENU_ACCESS_ADMIN_VALUE ||
				    $userRole == MENU_ACCESS_MANAGER_VALUE ||
					$userRole == MENU_ACCESS_USER_VALUE ){
					return true;	
				}else{
					return false;
				}
		    case MENU_ACCESS_GUEST_VALUE :
		        if( $userRole == MENU_ACCESS_ADMIN_VALUE ||
		        $userRole == MENU_ACCESS_MANAGER_VALUE ||
		        $userRole == MENU_ACCESS_USER_VALUE ||
		        $userRole == MENU_ACCESS_GUEST_VALUE ){
					return true;	
				}else{
					return false;
				}
		    case MENU_ACCESS_ALL_VALUE :
				return true;
			default :
				return false;
		}    	
    }
    
  
    /***
     * @return IView content depending of GET HTTP response
     */
    private static function getPageContent(IDataController $dataController){       

        if(empty($_GET)){            
            return ViewController::getDefaultPage($dataController);
        }
        
        $title = "";
        $page = "";
        $action = "";
        $content = "";
        $pagedata = "";
        
        if (isset($_GET['title'])) {
            $title = $_GET['title'];
        }
        
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }
        
        $menupage = $dataController->getMenuPage($title, $page, $action);
        if (empty($menupage)) {                      
            return ViewController::getErrorPage("error", "page not found");
        }
        
        // map the contents
        // use getPage for a single page view (a DataPage extended view), getAllPages for a multi-pages view (a DataPages extended view)
        switch($title){
            case "List":
                $pagedata = $dataController->getPage($menupage);
                $content = new ListV();
                break;
            case "Tabulations":
                $pagedata = $dataController->getAllPages($menupage);
                $content = new Tabulation($title);
                break;
            case "Accordion":
                $pagedata = $dataController->getAllPages($menupage);
                $content = new Accordion();
                break;
            case "Magellan":
                $pagedata = $dataController->getPage($menupage);
                $content = new AccordionMagellan();                
                break;
            default:
                $pagedata = $dataController->getPage($menupage);
                $content = new ListV();
                break;            
        }        
        
        if (empty($pagedata)) { 
            return ViewController::getErrorPage("error", "page not found");
        }
        
        $content->load($pagedata);
        
        return $content;      
        
    }  
    
    private static function getDefaultPage(IDataController $dataController){    	
    	$page = $dataController->getDefaultPage();
    	$content = new ListV();   	
    	$content->load($page);    	
    	return $content;
    }
    
    private static function getErrorPage($errorTitle, $errorMsg){
        error_log($errorTitle . " : " . $errorMsg);     
        $content = new ViewError($errorTitle, $errorMsg);      
        return $content;
    }
  
}

?>
