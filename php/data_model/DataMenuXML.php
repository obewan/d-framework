<?php
include_once 'DataMenu.php';

class DataMenuXML extends DataMenu
{

    private $filename = "xml/menu.xml";

    private $doc;

    private $isLoaded = false;

    public function load($source)
    {
        if (! file_exists($this->filename)) {
            $this->filename = "../../" . $this->filename; // for ajax calls
            if (! file_exists($this->filename)) {
                error_log("MenuXML::load() error : " . $this->filename . " not found");
                return false;
            }
        }
        $this->isLoaded = $this->loadXML($this->filename);
        
        return $this->isLoaded;
    }

    public function isLoaded()
    {
        return $this->isLoaded;
    }

    protected function loadXML($xmlFile)
    {
        $this->doc = new DOMDocument();
        $this->doc->preserveWhiteSpace = false; // avoid whitespace #text node
        if (! $this->doc->load($this->filename)) {
            return false;
        }
        
        $menu = $this->doc->documentElement;
        
        $title = $menu->getElementsByTagName(MENU_TITLE_ELT);
        if ($title->length > 0) {
            $titleStr = $title->item(0)->nodeValue;
            $this->setTitle($titleStr);
        }
        
        $groups = $menu->getElementsByTagName(MENU_GROUP_ELT);
        foreach ($groups as $group) {
            if ($group->nodeType == XML_ELEMENT_NODE) {
                $groupXML = new DataMenuGroupXML();
                $groupXML->load($group);
                $this->addGroup($groupXML);
            }
        }
        
        return true;
    }

    public function save()
    {}
}

class DataMenuGroupXML extends DataMenuGroup
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            return $this->loadXML($source);
        }
        return NULL;
    }

    protected function loadXML(DOMElement $node)
    {
        $title = $node->getElementsByTagName(MENU_TITLE_ELT);
        if ($title->length > 0) {
            $titleStr = $title->item(0)->nodeValue;
            $this->setTitle($titleStr);
        }
                
        $sections = $node->getElementsByTagName(MENU_SECTION_ELT);
        foreach ($sections as $section) {
            if ($section->nodeType == XML_ELEMENT_NODE) {
                $sectionXML = new DataMenuSectionXML();
                $sectionXML->load($section);
                $this->addSection($sectionXML);
            }
        }
    }

    public function save()
    {}
}

class DataMenuPageXML extends DataMenuPage
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            return $this->loadXML($source);
        }
        return NULL;
    }

    protected function loadXML(DOMElement $node)
    {
        $title = $node->getElementsByTagName(MENU_TITLE_ELT);
        if ($title->length > 0) {
            $titleStr = $title->item(0)->nodeValue;
            $this->setTitle($titleStr);
        }
        
        $src = $node->getElementsByTagName(MENU_SRC_ELT);
        if ($src->length > 0) {
            $srcStr = $src->item(0)->nodeValue;
            $this->setSrc($srcStr);
        }
        
        $action = $node->getElementsByTagName(MENU_ACTION_ELT);
        if ($action->length > 0) {
            $actionStr = $action->item(0)->nodeValue;
            $this->setAction($actionStr);
        }
        
        $icon = $node->getElementsByTagName(MENU_ICON_ELT);
        if ($icon->length > 0) {
            $iconStr = $icon->item(0)->nodeValue;
            $this->setIcon($iconStr);
        }
        
        if ($node->hasAttribute(MENU_WINDOW_ATTR)) {
            $window = $node->getAttribute(MENU_WINDOW_ATTR);
            $this->setWindow($window);
        }
    }

    public static function loadChildXML(DataMenuPage $page, DOMElement $node)
    {
        $title = $node->getElementsByTagName(MENU_TITLE_ELT);
        if ($title->length > 0) {
            $titleStr = $title->item(0)->nodeValue;
            $page->setTitle($titleStr);
        }
        
        $src = $node->getElementsByTagName(MENU_SRC_ELT);
        if ($src->length > 0) {
            $srcStr = $src->item(0)->nodeValue;
            $page->setSrc($srcStr);
        }
        
        $action = $node->getElementsByTagName(MENU_ACTION_ELT);
        if ($action->length > 0) {
            $actionStr = $action->item(0)->nodeValue;
            $page->setAction($actionStr);
        }
        
        $icon = $node->getElementsByTagName(MENU_ICON_ELT);
        if ($icon->length > 0) {
            $iconStr = $icon->item(0)->nodeValue;
            $page->setIcon($iconStr);
        }
    }

    public function save()
    {}
}

class DataMenuSectionXML extends DataMenuSection
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            return $this->loadXML($source);
        }
        return NULL;
    }

    protected function loadXML(DOMElement $node)
    {
        DataMenuPageXML::loadChildXML($this, $node);
        
        if ($node->hasAttribute(MENU_ACCESS_ATTR)) {
            $access = $node->getAttribute(MENU_ACCESS_ATTR);
            $this->setAccess($access);
        }
        if ($node->hasAttribute(MENU_WINDOW_ATTR)) {
            $window = $node->getAttribute(MENU_WINDOW_ATTR);
            $this->setWindow($window);
        }
        if ($node->hasAttribute(MENU_DEFAULT_ATTR)) {
            $default = ($node->getAttribute(MENU_DEFAULT_ATTR) === MENU_TRUE_VALUE);
            $this->setDefault($default);
        }
        
        $subsections = $node->getElementsByTagName(MENU_SUBSECTION_ELT);
        foreach ($subsections as $subsection) {
            if ($subsection->nodeType == XML_ELEMENT_NODE) {
                $subsectionXML = new DataMenuSubSectionXML();
                $subsectionXML->load($subsection);
                $this->addSubSection($subsectionXML);
            }
        }
    }

    public function save()
    {}
}

class DataMenuSubSectionXML extends DataMenuSubSection
{

    public function load($source)
    {
        if ($source instanceof DOMElement) {
            return $this->loadXML($source);
        }
        return NULL;
    }

    protected function loadXML(DOMElement $node)
    {
        DataMenuPageXML::loadChildXML($this, $node);
        
        if ($node->hasAttribute(MENU_ACCESS_ATTR)) {
            $access = $node->getAttribute(MENU_ACCESS_ATTR);
            $this->setAccess($access);
        }
        if ($node->hasAttribute(MENU_DIRECTION_ATTR)) {
            $direction = $node->getAttribute(MENU_DIRECTION_ATTR);
            $this->setDirection($direction);
        }
        if ($node->hasAttribute(MENU_WINDOW_ATTR)) {
            $window = $node->getAttribute(MENU_WINDOW_ATTR);
            $this->setWindow($window);
        }
        
        $pages = $node->getElementsByTagName(MENU_PAGE_ELT);
        foreach ($pages as $page) {
            if ($page->nodeType == XML_ELEMENT_NODE) {
                $pageXML = new DataMenuPageXML();
                $pageXML->load($page);
                $this->addPage($pageXML);
            }
        }
        
        $subsections = $node->getElementsByTagName(MENU_SUBSECTION_ELT);
        foreach ($subsections as $subsection) {
            if ($subsection->nodeType == XML_ELEMENT_NODE) {
                $subsectionXML = new DataMenuSubSectionXML();
                $subsectionXML->load($subsection);
                $this->addSubSection($subsectionXML);
            }
        }
    }

    public function save()
    {}
}


?>
