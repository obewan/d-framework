<?php
$g_phpDir = "php";
if (! is_dir($g_phpDir))
    $g_phpDir = "../../" . $g_phpDir; // for ajax calls
if (! is_dir($g_phpDir))
    error_log("Fatal error : php directory not found");

include_once $g_phpDir . '/view_model/interface/IBuilder.php';
include_once $g_phpDir . '/data_model/interface/IMenuButton.php';
include_once 'TemplateBuilder.php';  

class MenuButtonBuilder implements IBuilder
{
    private $viewPage = "menuButton.phtml";
    private $button;
    
    public function __construct(IMenuButton $button)
    {
        $this->button = $button;
    }
    
    public function build()
    {
        // build menu button
        $href = "#";
        $aId = "a_";
        $liId = "li_";
        $title = $this->button->getTitle();
        $icon = $this->button->getIcon();
        $isActive = $this->button->isActive();
        $active = "";
        if($isActive){
            $active = "active";
        }
        
        if ($this->button->getWindow() == MENU_WINDOW_MODAL_VALUE) {
            // modal button, controlled with damsController.js and AjaxController.php
            $aId .= "modal_";
        } else {
            // classic button, controlled with ViewController.php
            $href = "?title=" . $this->button->getTitle();
            if ($this->button->getAction() != null) {
                $href .= "&action=" . $this->button->getAction();
            } elseif ($this->button->getSrc() != null) {
                $href .= "&page=" . $this->button->getSrc();
            }
        }
        
        if (LoginController::isLogged() && $this->button->getAction() == "login") {
            // special logout button
            $liId .= MENU_LOGOUT_TEXT;
            $aId .= MENU_LOGOUT_ACTION;
            $title = MENU_LOGOUT_TEXT;
            $icon = MENU_LOGOUT_ICON;
        } else {
            // common buttons
            $liId .= $this->button->getTitle();
            if ($this->button->getAction() != null) {
                $aId .= $this->button->getAction();
            } elseif ($this->button->getSrc() != null) {
                $aId .= $this->button->getSrc();
            }
        }
        
        // rendering
        $data = array(
            'li_id' => $liId,
            'active' => $active,
            'a_id' => $aId,
            'href' => $href,
            'icon' => $icon,
            'title' => $title
        );
        $template = new TemplateBuilder($this->viewPage, $data);
        return $template->build();
    }
    
    
}

?>