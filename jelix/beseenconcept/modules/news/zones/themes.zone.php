<?php
/**
* @package   
* @subpackage   
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

class themesZone extends Jzone {
	
    protected $_tplname = 'news~themesList';

    protected function _prepareTpl() {

	$isConnected = false;

	if(jAuth::isConnected()) {
		$isConnected = true;
	}

    $factThemes = jDao::get('news~themes');
    $themesList = $factThemes->findAll();

    $this->_tpl->assign( 'isConnected', $isConnected);
    $this->_tpl->assign( 'themesList', $themesList);

    }
}