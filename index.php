<?php session_start();

include_once 'php/controller/XMLDataController.php';
include_once 'php/controller/ViewController.php';
include_once 'php/view_model/ViewLayout.php';



ViewController::showLayout(new ViewLayout(), new XMLDataController());

?>

