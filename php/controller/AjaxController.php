<?php
if(session_id() === '' && !isset($_SESSION)) {
     session_start();
}
include_once 'LoginController.php';
include_once 'ViewController.php';
include_once 'XMLDataController.php';

if(!empty($_POST))
{
    if(isset($_POST['action'])){
        
        switch($_POST['action'])
        {
            case "login":
                LoginController::valid();
                break;
            case "logout":
                LoginController::logout();
                break;
            case "showLogin":
                ViewController::showStaticView(new Login());
                break;
            case "showExample":
                $isModal = true;
                ViewController::showDynamicView(
                    new ListV($isModal),
                    new XMLDataController(),
                    "Modal",
                    "examples",
                    "");
                break;
            case "showAbout":
                $isModal = true;
                ViewController::showDynamicView(
                    new ListV($isModal),
                    new XMLDataController(),
                    "About",
                    "pages",
                    "about");
                break;
            default:
                break;
        }                
    }
}


?>
