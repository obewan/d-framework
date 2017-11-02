<?php
include_once 'interface/IView.php';
include_once 'builder/TemplateBuilder.php';

/**
 * AccordionMagellan view model
 *
 * @author dams
 *        
 */
class AccordionMagellan extends DataPage implements IView
{
    protected $viewPage = "accordionMagellanMath.phtml";

    /**
     * 
     * {@inheritDoc}
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

    protected function loadPage(DataPage $page)
    {
        $chapters = $page->getChapters();
        $num = 0;
        foreach ($chapters as $chapter) {
            $accordionChapter = new AccordionChapter($num);
            $accordionChapter->load($chapter);
            $this->addChapter($accordionChapter);
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
        $chapters = $this->sortChapters();
        
        // / build magellan dd
        $magellan_dd = "";
        $id = 0;
        foreach ($chapters as $chapter) {
            $title = $chapter->getTitle();
            $div_id = "li_" . $id;
            $magellan_dd .= '<li><a href="#' . $div_id . '">' . $title . '</a></li>' . PHP_EOL;
            $id ++;
        }
        
        $accordion_lis = "";
        $id = 0;
        foreach ($chapters as $chapter) {
            $chapter->setNum($id);
            $accordion_lis .= $chapter->build();
            $id ++;
        }
        
        $data = array(
            'magellan_dd' => $magellan_dd,
            'accordion_li' => $accordion_lis
        );
        $template = new TemplateBuilder($this->viewPage, $data);
        
        return $template->build();
    }

    /**
     * *
     * Sort chapters by title
     * 
     * @return AccordionChapter array
     */
    protected function sortChapters()
    {
        $sortedChapters = array();
        $titles = array();
        $chapters = $this->getChapters();
        
        foreach ($chapters as $chapter) {
            $titles[] = $chapter->getTitle();
        }
        sort($titles, SORT_STRING);
        
        foreach ($titles as $title) {
            foreach ($chapters as $chapter) {
                if ($chapter->getTitle() == $title) {
                    $sortedChapters[] = $chapter;
                    break;
                }
            }
        }
        return $sortedChapters;
    }
}

class AccordionChapter extends DataChapter implements IView
{

    protected $chapter;
    protected $viewPage = "accordion_li.phtml";
    protected $num = 0;

    
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
        if ($source instanceof DataChapter) {
            return $this->loadChapter($source);
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

    protected function loadChapter(DataChapter $chapter)
    {
        $this->chapter = $chapter;
        
        $this->setTitle(ucfirst($chapter->getTitle()));
        
        return true;
    }

    public function setNum($num)
    {
        $this->num = $num;
    }

    public function build()
    {
        $a_id = "a_" . $this->num;
        $div_id = "div_" . $this->num;
        $li_id = "li_" . $this->num;
        
        $title = $this->getTitle();
        
        $formula = "";
        $formulas = $this->chapter->getFormulas();
        foreach ($formulas as $val) {
            $name = $val->getName();
            $quote = nl2br($val->getQuote());
            $cite = $val->getCite();
            $comment = nl2br($val->getComment());
            
            $data = array(
                'name' => $name,
                'quote' => $quote,
                'cite' => $cite,
                'comment' => $comment
            );
            $template = new TemplateBuilder("formula.phtml", $data);
            $formula .= $template->build();
        }
        
        $text = "";
        $texts = $this->chapter->getTexts();
        foreach ($texts as $textval) {
            $text .= "<p>" . nl2br($textval) . "</p>";
        }
        
        $date = $this->chapter->getDate();
        
        $li_data = "data-magellan-target='" . $li_id . "'";
        
        $data = array(
            'li_data' => $li_data,
            'li_id' => $li_id,
            'a_id' => $a_id,
            'div_id' => $div_id,
            'date' => $date,
            'title' => $title,
            'formula' => $formula,
            'text' => $text
        );
        $template = new TemplateBuilder($this->viewPage, $data);
        
        return $template->build();
    }
}

?>
