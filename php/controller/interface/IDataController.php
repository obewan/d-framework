<?php 

interface IDataController {
	/***
	 * @return NULL | (IData)DataMenu loaded concret instance
	 */
	function getMenu();
	
	/***
	 * @return the default (IData)DataPage content
	 */
	function getDefaultPage();
	
	/***
	 * 
	 * @param DataMenuPage $menupage the menu page selection
	 * @return the selected (IData)DataPage content
	 */
	function getPage(DataMenuPage $menupage);
	
	/***
	 * 
	 * @param DataMenuPage $menupage
	 * @return the (IData)DataPages data
	 */
	function getAllPages(DataMenuPage $menupage);
	
	/***
	 * Get menupage matching (title and src) or (title and action)
	 * @param string $title
	 * @param string $src
	 * @param string $action
	 * @return NULL | (IData)DataMenuPage
	 */
	function getMenuPage($title, $src, $action);
	

}


?>