<?php
/**
* @package   beseenconcept
* @subpackage news
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
    	$newsDao = jDao::get('newsdao');
    	$newsList = $newsDao->findAllAndOrder();
    	jLog::dump($newsList);
    	$tpl = new jTpl();
    	$tpl->assign('newsList',$newsList);

        $rep = $this->getResponse('html');
        $rep->title .=  jLocale::get('structure~string.newsTitle');

        $frontImage = 'news';
        
        $rep->body->assign('frontImage', $frontImage);
        $rep->body->assign('MAIN', $tpl->fetch('newsPage'));

        return $rep;
    }
}

