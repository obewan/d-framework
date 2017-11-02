<?php
$g_phpDir = "php";
if (! is_dir($g_phpDir))
    $g_phpDir = "../../" . $g_phpDir; // for ajax calls
if (! is_dir($g_phpDir))
    error_log("Fatal error : php directory not found");

include_once 'builder/TemplateBuilder.php';
include_once 'interface/IView.php';
include_once $g_phpDir . '/controller/tools/Tools.php';

/**
 * Accordion view model
 *
 * @author dams
 *        
 */
class Accordion extends DataPages implements IView
{
    protected $viewPage = "accordion.phtml";
    /**
     * 
     * {@inheritDoc}
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
     * 
     * {@inheritDoc}
     * @see IData::save()
     */
    public function save()
    {}

    protected function loadPages(DataPages $pages)
    {
        $datapages = $pages->getPages();
        $num = 0;
        foreach ($datapages as $datapage) {
            $accordionPage = new AccordionPage($num);
            $accordionPage->load($datapage);
            $this->addPage($accordionPage);
            $num ++;
        }
        
        return true;
    }

   /**
    * 
    * {@inheritDoc}
    * @see IView::build()
    */
    public function build()
    {
        $accordion_lis = "";
        
        $pages = $this->getPages();
        foreach ($pages as $page) {
            $accordion_lis .= $page->build();
        }
        
        $data = array(
            'accordion_li' => $accordion_lis
        );
        $template = new TemplateBuilder($this->viewPage, $data);
        
        return $template->build();
    }
}

class AccordionPage extends DataPage implements IView
{

    protected $page;

    protected $num = 0;
    protected $viewPage = "accordion_li.phtml";

    public function __construct($num)
    {
        $this->num = $num;
    }

    /**
     * *
     * Load menu
     * 
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataPage) {
            return $this->loadPage($source);
        }
        return false;
    }

   /**
    * 
    * {@inheritDoc}
    * @see IData::save()
    */
    public function save()
    {}

    public function setNum($num)
    {
        $this->num = $num;
    }

    protected function loadPage(DataPage $page)
    {
        $this->page = $page;
        
        return true;
    }

    public function build()
    {
        $a_id = "a_" . $this->num;
        $div_id = "div_" . $this->num;
        $li_id = "li_" . $this->num;
        
        $title = ucfirst($this->page->getTitle());
        
        $text = "";
        $texts = $this->page->getTexts();
        $inc = 0;
        $tot = count($texts);
        foreach ($texts as $textval) {
            $text .= nl2br($textval);
            if ($inc < $tot - 1) {
                $text .= "<hr>";
            }
            $inc ++;
        }
        
        $date = $this->page->getDate();
        
        //try to parse date in PHP 5.1.3 format, using french order (TODO : configure this)
        if(!empty($date)){
            $dateEng = Tools::FormatDateFrToEng($date);
            if(!empty($dateEng)){
                $date = $dateEng;
            }
        }
        
        $type = $this->page->getType();
        
        $data = array(
            'a_id' => $a_id,
            'li_id' => $li_id,
            'div_id' => $div_id,
            'date' => $date,
            'type' => $type,
            'title' => $title,
            'text' => $text        
        );
        $template = new TemplateBuilder($this->viewPage, $data);
        
        return $template->build();
    }
}

?>
