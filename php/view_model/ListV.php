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
 * List Vertical view model
 * texts and infos are listed with boxes
 * an included view file can be set and show on the top
 *
 * @author dams
 *        
 */
class ListV extends DataPage implements IView
{

    protected $page;

    protected $isModal = false;

    protected $viewPage = "listV.phtml";

    protected $viewModal = "listVModal.phtml";
    
    protected $viewPanel = "panelSimple.phtml";
        

    public function __construct($isModal = false)
    {
        $this->isModal = $isModal;
    }

    /**
     *
     * {@inheritdoc}
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataPage) {
            return $this->loadPage($source);
        }
        return false;
    }

    protected function loadPage(DataPage $page)
    {
        $this->page = $page;
    }

    /**
     *
     * {@inheritdoc}
     * @see IData::save()
     */
    public function save()
    {}

    /**
     *
     * {@inheritdoc}
     * @see IView::build()
     */
    public function build()
    {
        $includeData = "";
        $include = $this->page->getInclude();
        if ($include != null && $include != "") {
            $templateInclude = new TemplateBuilder($include);
            $includeData = $templateInclude->build();
        }
        
        $textsData = "";
        $texts = $this->page->getTexts();
        foreach ($texts as $text) {
            $data = array(
                'content' => $text                
            );
            $template = new TemplateBuilder($this->viewPanel, $data);
            $textsData .= $template->build();           
        }
        
        $infosData = "";
        $infos = $this->page->getInfos();
        $infoNum = 0;
        foreach ($infos as $info) {
            $listVinfo = new ListVInfo();
            $listVinfo->load($info);
            
            if ($infoNum == 0) {
                $listVinfo->setClass("alert-box success");
            } else {
                $listVinfo->setClass("alert-box info");
            }
            
            $infosData .= $listVinfo->build();
            $infoNum ++;
        }
        
        $title = $this->page->getTitle();
        $icon = $this->page->getIcon();
      
        
        $data = array(
            'include' => $includeData,
            'title' => $title,
            'icon'  => $icon,
            'texts' => $textsData,
            'infos' => $infosData
        );
        if ($this->isModal) {
            $template = new TemplateBuilder($this->viewModal, $data);
        } else {
            $template = new TemplateBuilder($this->viewPage, $data);
        }
        
        return $template->build();
    }
}

/**
 * ListVInfo class for info alert boxes in ListV
 * 
 * @author dams
 *        
 */
class ListVInfo extends DataInfo implements IView
{

    protected $class;

    protected $viewAlert = 'alert.phtml';

    /**
     *
     * {@inheritdoc}
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataInfo) {
            $this->loadInfo($source);
        }
    }

    protected function loadInfo(DataInfo $info)
    {
        $date = $info->getDate();
        if (!empty($date)){
            $this->setDate($date);
        }
        
        $this->setScale($info->getScale());
        
        $this->setText($info->getText());
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    /**
     *
     * {@inheritdoc}
     * @see IData::save()
     */
    public function save()
    {}

    /**
     *
     * {@inheritdoc}
     * @see IView::build()
     */
    public function build()
    {
        $data = array(
            'info' => $this
        );
        $template = new TemplateBuilder($this->viewAlert, $data);
        
        return $template->build();
    }
}

?>
