<?php 

/**
 * ViewError view model
 *
 * @author dams
 *
 */
class ViewError implements IView
{
    protected $viewPage = "error.phtml";
    protected $errorTitle = "";
    protected $errorMsg = "";
    
    
    public function __construct($errorTitle, $errorMsg)
    {
        $this->errorTitle = $errorTitle;
        $this->errorMsg = $errorMsg;
    }
    
    public function build()
    {
        $data = array(
            'errorTitle' => $this->errorTitle,          
            'errorMsg' => $this->errorMsg
        );
        
        $template = new TemplateBuilder($this->viewPage, $data);
        
        
        return $template->build();
    }
}


?>