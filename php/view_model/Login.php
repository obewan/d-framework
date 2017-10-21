<?php
include_once 'builder/TemplateBuilder.php';
include_once 'interface/IView.php';

/**
 * Login view model
 *
 * @author dams
 *        
 */
class Login implements IView
{

    public function __construct()
    {}

    public function build()
    {
        $template = new TemplateBuilder('login.phtml');
        return $template->build();
    }
}


?>
