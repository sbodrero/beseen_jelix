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

    public $pluginParams = array(
        '*'=>array('auth.required'=>false),
        'deleteNews'=>array('auth.required'=>true),
        'editNews'=>array('auth.required'=>true),
     );

    protected $isConnected = false;

    function index() {
        if (jAuth::isConnected()) {
           $this->isConnected = true; 
        }

    	$newsDao = jDao::get('newsdao');
    	$list = $newsDao->findAllAndOrder();

        $toolsSrv = jClasses::getService( 'tools' );
        $newsList = $toolsSrv->prepareArrayForNewslist($list);

    	$tpl = new jTpl();
    	$tpl->assign('newsList',$newsList);
        $tpl->assign('isConnected', $this->isConnected);

        $rep = $this->getResponse('html');
        $rep->title .=  jLocale::get('string.newsTitle');

        $frontImage = 'news';
        
        $rep->body->assign('frontImage', $frontImage);
        $rep->body->assign('MAIN', $tpl->fetch('newsPage'));

        return $rep;
    }

    function showNews() {

    }

    function showNewsByTheme() {

    }

    function prepareNewsForm() {
        jForms::clean('newsform');
        $form = jForms::create('newsform');
        $form = jForms::clear('newsform');

        jMessage::clear('msgNoticeContact');

        // redirect to step 1b
        $rep= $this->getResponse('redirect');
        $rep->action='default:showNewsForm';
        return $rep;
    }

    function showNewsForm() {
        $formSel = 'newsform' ;
        $form = jForms::get($formSel);
        if ($form === null) {
            $form = jForms::create($formSel);
        }
        
        $rep = $this->getResponse('html') ;
        $rep->title .=  jLocale::get('string.newsTitle');

        $tpl = new jTpl();
        $tpl->assign('form', $form);

        $frontImage = 'news';
        
        $rep->body->assign('frontImage', $frontImage);
        $rep->body->assign( 'MAIN', $tpl->fetch('addNewsForm') );

        return $rep; 
    }

    function saveNews() {

        $form = jForms::fill('newsform');
        if (!$form->check()) {
            // invalide : on redirige vers l'action d'affichage
            $rep = $this->getResponse('redirect');
            $rep->action='news~default:showNewsForm';
        return $rep;
        }
        if ($form->check()) {
            global $gJConfig;
            
            $uploadDir = JELIX_APP_WWW_PATH.$gJConfig->urlengine['basePath'].'themes/'.jApp::config()->theme.'/Images/news/';
            $uploadfile = $uploadDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);

            $data = $form->getAllData() ;

            $newsDao = jDao::get('newsdao');
            $record = jDao::createRecord('newsdao');
            $record->title = $data['title'];
            $record->theme_id = $data['theme_id'];
            $record->text = $data['text'];
            $record->image = $data['image'];
            $newsDao->insert($record);


            jForms::destroy('newsform');

            jMessage::add( jLocale::get('string.news.newsOk'), 'msgNoticeContact' );
            $rep= $this->getResponse('redirect');
            $rep->action='default:index';

            return $rep;
        }        
    }

    function editNews() {

    } 

    function deleteNews() {
        $id = $this->param('id');
        $newsDao = jDao::get('newsdao');

        if($deletingNews = $newsDao->delete($id)) {
            jMessage::add( jLocale::get('string.news.newsDelete'), 'msgNoticeContact' );
        } else {
            jMessage::add( jLocale::get('string.news.newsDeleteError'), 'msgErrorContact' );
        }

        $rep= $this->getResponse('redirect');
        $rep->action='default:index';
        return $rep;
    }

}

