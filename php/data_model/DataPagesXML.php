<?php
include_once 'DataPages.php';

class DataPagesXML extends DataPages
{

    public static $includeDir = "includes/";

    protected $filename;

    protected $doc;

    protected $isLoaded = false;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function load($source)
    {
        if ($this->isLoaded) {
            error_log("PagesXML::load() error : " . $this->filename . " already loaded");
            return $this->isLoaded;
        }
        
        if (! file_exists($this->filename)) {
            $this->filename = "../../" . $this->filename; // for ajax calls
            if (! file_exists($this->filename)) {
                error_log("PagesXML::load() error : " . $this->filename . " not found");
                return false;
            }
        }
        
        $this->doc = new DOMDocument();
        $this->doc->preserveWhiteSpace = false; // avoid whitespace #text node
        $this->isLoaded = $this->doc->load($this->filename);
        
        $root = $this->doc->documentElement;
        $pages = $root->childNodes;
        foreach ($pages as $page) {
            $pageXML = new DataPageXML();
            $pageXML->load($page);
            $this->addPage($pageXML);
        }
        
        return $this->isLoaded;
    }

    public function save()
    {}
}

class DataPageXML extends DataPage
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            $this->loadXML($source);
        }
    }

    protected function loadXML(DOMElement $node)
    {
        DataChapterXML::loadChildXML($this, $node);
        
        if ($node->hasAttribute("type")) {
            $type = $node->getAttribute("type");
            $this->setType($type);
        }
        
        $icon = $node->getElementsByTagName("icon");
        if ($icon->length > 0) {
            $iconStr = $icon->item(0)->nodeValue;
            $this->setIcon($iconStr);
        }
        
        $chapters = $node->getElementsByTagName("chapter");
        foreach ($chapters as $chapter) {
            $chapterXML = new DataChapterXML();
            $chapterXML->load($chapter);
            $this->addChapter($chapterXML);
        }
    }

    public function save()
    {}
}

class DataChapterXML extends DataChapter
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            $this->loadXML($source);
        }
    }

    protected function loadXML(DOMElement $node)
    {
        $title = $node->getElementsByTagName("title");
        if ($title->length > 0) {
            $titleStr = $title->item(0)->nodeValue;
            $this->setTitle($titleStr);
        }
        
        $author = $node->getElementsByTagName("author");
        if ($author->length > 0) {
            $authorStr = $author->item(0)->nodeValue;
            $this->setAuthor($authorStr);
        }
        
        $date = $node->getElementsByTagName("date");
        if ($date->length > 0) {
            $dateStr = $date->item(0)->nodeValue;
            $this->setDate($dateStr);
        }
        
        $image = $node->getElementsByTagName("image");
        if ($image->length > 0) {
            $imageStr = $image->item(0)->nodeValue;
            $this->setImage($imageStr);
        }
        
        $screens = $node->getElementsByTagName("screen");
        foreach ($screens as $screen) {
            $screenXML = new DataScreenXML();
            $screenXML->load($screen);
            $this->addScreen($screenXML);
        }
        
        $abstract = $node->getElementsByTagName("abstract");
        if ($abstract->length > 0) {
            $abstractStr = $abstract->item(0)->nodeValue;
            $this->setAbstract($abstractStr);
        }
        
        $comment = $node->getElementsByTagName("comment");
        if ($comment->length > 0) {
            $commentStr = $comment->item(0)->nodeValue;
            $this->setComment($commentStr);
        }
        
        $progress = $node->getElementsByTagName("progress");
        if ($progress->length > 0) {
            $progressStr = $progress->item(0)->nodeValue;
            $this->setProgress($progressStr);
        }
        
        $formulas = $node->getElementsByTagName("formula");
        foreach ($formulas as $formula) {
            $formulaXML = new DataFormulaXML();
            $formulaXML->load($formula);
            $this->addFormula($formulaXML);
        }
        
        $texts = $node->getElementsByTagName("text");
        foreach ($texts as $text) {
            $textStr = $text->nodeValue;
            $this->addText($textStr);
        }
        
        $include = $node->getElementsByTagName("include");
        if ($include->length > 0) {
            $includeStr = $include->item(0)->nodeValue;
            $this->setInclude(DataPagesXML::$includeDir . $includeStr);
        }
        
        $infos = $node->getElementsByTagName("info");
        foreach ($infos as $info) {
            $infoXML = new DataInfoXML();
            $infoXML->load($info);
            $this->addInfo($infoXML);
        }
    }

    public static function loadChildXML(DataPage $page, DOMElement $node)
    {
        $title = $node->getElementsByTagName("title");
        if ($title->length > 0) {
            $titleStr = $title->item(0)->nodeValue;
            $page->setTitle($titleStr);
        }
        
        $author = $node->getElementsByTagName("author");
        if ($author->length > 0) {
            $authorStr = $author->item(0)->nodeValue;
            $page->setAuthor(($authorStr));
        }
        
        $date = $node->getElementsByTagName("date");
        if ($date->length > 0) {
            $dateStr = $date->item(0)->nodeValue;
            $page->setDate($dateStr);
        }
        
        $image = $node->getElementsByTagName("image");
        if ($image->length > 0) {
            $imageStr = $image->item(0)->nodeValue;
            $page->setImage($imageStr);
        }
        
        $screens = $node->getElementsByTagName("screen");
        foreach ($screens as $screen) {
            $screenXML = new DataScreenXML();
            $screenXML->load($screen);
            $page->addScreen($screenXML);
        }
        
        $abstract = $node->getElementsByTagName("abstract");
        if ($abstract->length > 0) {
            $abstractStr = $abstract->item(0)->nodeValue;
            $page->setAbstract($abstractStr);
        }
        
        $comment = $node->getElementsByTagName("comment");
        if ($comment->length > 0) {
            $commentStr = $comment->item(0)->nodeValue;
            $page->setComment($commentStr);
        }
        
        $progress = $node->getElementsByTagName("progress");
        if ($progress->length > 0) {
            $progressStr = $progress->item(0)->nodeValue;
            $page->setProgress($progressStr);
        }
        
        $formulas = $node->getElementsByTagName("formula");
        foreach ($formulas as $formula) {
            $formulaXML = new DataFormulaXML();
            $formulaXML->load($formula);
            $page->addFormula($formulaXML);
        }
        
        $texts = $node->getElementsByTagName("text");
        foreach ($texts as $text) {
            $textStr = $text->nodeValue;
            $page->addText($textStr);
        }
        
        $include = $node->getElementsByTagName("include");
        if ($include->length > 0) {
            $includeStr = $include->item(0)->nodeValue;
            $page->setInclude(DataPagesXML::$includeDir . $includeStr);
        }
        
        $infos = $node->getElementsByTagName("info");
        if ($infos->length > 0) {
            foreach ($infos as $info) {
                $infoXML = new DataInfoXML();
                $infoXML->load($info);
                $page->addInfo($infoXML);
            }
        }
    }

    public function save()
    {}
}

/**
 * *
 * Screenshots
 */
class DataScreenXML extends DataScreen
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            $this->loadXML($source);
        }
    }

    protected function loadXML(DOMElement $node)
    {
        $thumb = $node->getElementsByTagName("thumb");
        if ($thumb->length > 0) {
            $thumbStr = $thumb->item(0)->nodeValue;
            $this->setThumb($thumbStr);
        }
        
        $big = $node->getElementsByTagName("big");
        if ($big->length > 0) {
            $bigStr = $big->item(0)->nodeValue;
            $this->setBig($bigStr);
        }
    }

    public function save()
    {}
}

class DataInfoXML extends DataInfo
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            $this->loadXML($source);
        }
    }

    protected function loadXML(DOMElement $node)
    {
        $infoStr = $node->nodeValue;
        $this->setText($infoStr);
        
        if ($node->hasAttribute("date")) {
            $dateStr = $node->getAttribute("date");
            $this->setDate($dateStr);
        }
        
        if ($node->hasAttribute("scale")) {
            $scaleStr = $node->getAttribute("scale");
            $this->setScale($scaleStr);
        }
    }

    public function save()
    {}
}

class DataFormulaXML extends DataFormula
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            $this->loadXML($source);
        }
    }

    protected function loadXML(DOMElement $node)
    {
        $quote = $node->getElementsByTagName("quote");
        if ($quote->length > 0) {
            $quoteStr = $quote->item(0)->nodeValue;
            $this->setQuote($quoteStr);
        }
        
        $cite = $node->getElementsByTagName("cite");
        if ($cite->length > 0) {
            $citeStr = $cite->item(0)->nodeValue;
            $this->setCite($citeStr);
        }
        
        $comment = $node->getElementsByTagName("comment");
        if ($comment->length > 0) {
            $commentStr = $comment->item(0)->nodeValue;
            $this->setComment($commentStr);
        }
        
        if ($node->hasAttribute("name")) {
            $name = $node->getAttribute("name");
            $this->setName($name);
        }
    }

    public function save()
    {}
}



?>