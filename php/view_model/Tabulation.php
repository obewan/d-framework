<?php
$g_phpDir = "php";
if (! is_dir($g_phpDir))
    $g_phpDir = "../../" . $g_phpDir; // for ajax calls
if (! is_dir($g_phpDir))
    error_log("Fatal error : php directory not found");

include_once 'interface/IView.php';
include_once 'builder/TemplateBuilder.php';
include_once $g_phpDir . '/data_model/DataPages.php';

/**
 * Tabulation model for view
 *
 * @author dams
 *        
 */
class Tabulation extends DataPages implements IView
{

    private $title;
    protected $imagesPath = "images/";
    protected $viewTabContent = "tab_content.phtml";
    protected $viewTabScreen = "tab_screen.phtml";
    protected $viewTabTitle = "tab_title.phtml";
    protected $viewTab = "tabs.phtml";

    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * *
     * Load page
     * 
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataPages) {
            return $this->loadPages($source);
        }
        return false;
    }

    /**
     * *
     * (non-PHPdoc)
     * 
     * @see IData::save()
     */
    public function save()
    {}

    protected function loadPages(DataPages $pages)
    {
        $pagesdata = $pages->getPages();
        foreach ($pagesdata as $page) {
            $this->addPage($page);
        }
        
        return true;
    }

    public function build()
    {        
        $tab_titles = "";
        $tab_contents = "";
        
        $pages = $this->getPages();
        $num = 0;
        foreach ($pages as $page) {
            // set tab id
            $tab_id = "tab" . $num;
            
            // set active tab
            $title = $page->getTitle();
            $active = "";
            if ($title == $this->title) {
                $active = "active";
            }
            
            // build tabs titles
            $data = array(
                'active' => $active,
                'tab_id' => $tab_id,
                'title' => $title
            );
            
            $template = new TemplateBuilder($this->viewTabTitle, $data);
            $tab_titles .= $template->build();
            
            // build tabs bodies
            $screens = "";
            $length = 0;
            $text = "";
            $comment = "";
            $infos = "";
            $progress = NULL;
            $abstract = "";
            
            // abstract
            $abstractdata = $page->getAbstract();
            if (! empty($abstractdata)) {
                $abstract = $abstractdata;
            }
            
            // screenshots
            $screensdata = $page->getScreens();
            foreach ($screensdata as $screen) {
                $big_src = $this->imagesPath . $screen->getBig();
                $thumb_src = $this->imagesPath . $screen->getThumb();
                $data = array(
                    'big_src' => $big_src,
                    'thumb_src' => $thumb_src
                );
                $template = new TemplateBuilder($this->viewTabScreen, $data);
                $screens .= $template->build();
            }
            $length = count($screensdata);
            
            // main image
            $image = $page->getImage();
            if(! empty($image)) {
                $image = $this->imagesPath . $image;
            }
            
            // text
            $textsdata = $page->getTexts();
            foreach ($textsdata as $textval) {
                $text .= nl2br($textval) . "<br />";
            }
            
            // comment
            $commentdata = $page->getComment();
            if (! empty($commentdata)) {
                $comment = nl2br($commentdata);
            }
            
            // progress
            $progressdata = $page->getProgress();
            if (! empty($progressdata)) {
                $progress = $progressdata;
            }
            
            //infos
            $infosdata = $page->getInfos();
            $infoNum = 0;
            foreach ($infosdata as $infodata){
                $listVinfo = new ListVInfo();
                $listVinfo->load($infodata);
                if ($infoNum == 0) {
                    $listVinfo->setClass("alert-box success");
                } else {
                    $listVinfo->setClass("alert-box info");
                }
                
                $infos .= $listVinfo->build();
                $infoNum ++;
            }
            
            // body
            $data = array(
                'abstract' => $abstract,
                'active' => $active,
                'tab_id' => $tab_id,
                'screens' => $screens,
                'length' => $length,
                'comment' => $comment,
                'text' => $text,
                'image' => $image,
                'infos' => $infos,
                'progress' => $progress
            );
            $template = new TemplateBuilder($this->viewTabContent, $data);
            $tab_contents .= $template->build();
            
            $num ++;
        }
        
        $data = array(
            'tab_titles' => $tab_titles,
            'tab_contents' => $tab_contents
        );
        $template = new TemplateBuilder($this->viewTab, $data);
        
        return $template->build();
    }
 
}


?>
