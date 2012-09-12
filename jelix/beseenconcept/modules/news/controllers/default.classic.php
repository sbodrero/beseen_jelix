<?php
/**
* @package   beseenconcept
* @subpackage news
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/
jClasses::inc( 'news~defines' );

class defaultCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'=>array('auth.required'=>false),
        'deleteNews'=>array('auth.required'=>true),
        'editNews'=>array('auth.required'=>true),
        'waitingComs'=>array('auth.required'=>true)
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

    function prepareComsForm() {
        $newsId = $this->param('id');
        jForms::clean('comsform');
        $form = jForms::create('comsform');
        $form = jForms::clear('comsform');

        $form->setData('news_id', $newsId);

        jMessage::clear('msgNoticeContact');

        $rep= $this->getResponse('redirect');
        $rep->params = array('id' => $newsId );
        $rep->action='default:showNews';
        return $rep;
    }

    function showNews() {

        $newsId = $this->param('id');
        $newsDetails = '';
        $comsList = '';
        $comsCount = '';            

        if(!empty($newsId)) {
            $newsDao = jDao::get('newsdao');
            $comsDao = jDao::get('comsdao');
            $newsDetails = $newsDao->findNewsDetails($newsId);
            $comsList = $comsDao->findComsByNews($newsId);
            $comsCount = count($comsList);
        }

        $tpl = new jTpl();
        $tpl->assign(array('newsDetails' => $newsDetails,
                            'comsList' => $comsList,
                            'comsCount' => $comsCount));

        $frontImage = 'news';

        $rep = $this->getResponse('html');
        $rep->body->assign('frontImage', $frontImage);
        $rep->body->assign( 'MAIN', $tpl->fetch('newsDetails') );
        return $rep;
    }

    function saveComs() {

        $form = jForms::fill('comsform');
        $data = $form->getAllData();

        if (!$form->check()) {
            $rep = $this->getResponse('redirect');
            $rep->params = array('id' => $data['news_id']);
            $rep->action='news~default:showNews';
            return $rep;
        }
        if ($form->check()) {
            $data = $form->getAllData();
            $comsDao = jDao::get('comsdao');
            $record = jDao::createRecord('comsdao');
            $record->pseudo = $data['pseudo'];
            $record->url = $data['url'];
            $record->mail = $data['mail'];
            $record->text = $data['text'];
            $record->news_id = $data['news_id'];
            $comsDao->insert($record);

            jForms::destroy('comsform');
            jMessage::add( jLocale::get('string.news.comsOk'), 'msgNoticeContact' );
            $rep = $this->getResponse('redirect');
            $rep->params = array('id' => $data['news_id']);
            $rep->action ='default:showNews';

            return $rep;
        }
    }

    function waitingComs() {

        $comsDao = jDao::get('comsdao');
        $waitingComs = $comsDao->findWaitingComs();

        $tpl = new jTpl();
        $tpl->assign('waitingComs', $waitingComs);
        $mainContent = $tpl->fetch('waitingComs');
        $rep = $this->getResponse('htmlfragment');
        $rep->addContent($mainContent);
        return $rep;
    }

    function deleteOrValidateWaitingComs() {

        $comsDao = jDao::get('comsdao');
        $toolsSrv = jClasses::getService( 'tools' );
        jLog::dump($_POST);
        jLog::dump(count($_POST));
        $deletedComs = 0;
        $validatedComs = 0;
        foreach ($_POST as $key => $value) {
            switch ($value) {
                case ACTION_VALIDATE:
                    $idToValidate = $toolsSrv->extractComsId($key);
                    jLog::dump($idToValidate);
                    $comsDao->validateComs($idToValidate);
                    $validatedComs +=1;
                    break;
                case ACTION_DELETE:
                    $idToDelete = $toolsSrv->extractComsId($key);
                    $comsDao->delete($idToDelete);
                    $deletedComs +=1;
                    break;
            }
        }

        if( $deletedComs > 0 || $validatedComs > 0) {
            jMessage::add( $deletedComs.' supprimé(s) '.$validatedComs.' validé(s)', 'msgNoticeContact' );
        } else {
            jMessage::add( jLocale::get('string.news.comsProblem'), 'msgErrorContact' );
        }

        $rep = $this->getResponse('redirect');
        $rep->action = 'default:index';
        return $rep;

    }

    function showNewsByTheme() {

    }

    function prepareNewsForm() {

        jForms::clean('newsform');
        $form = jForms::create('newsform');
        $form = jForms::clear('newsform');

        jMessage::clear('msgNoticeContact');

        $rep= $this->getResponse('redirect');
        $rep->action ='default:showNewsForm';
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
            $rep = $this->getResponse('redirect');
            $rep->action='news~default:showNewsForm';
            return $rep;
        }
        if ($form->check()) {

            global $gJConfig;
            $uploadDirThumbs = JELIX_APP_WWW_PATH.$gJConfig->urlengine['basePath'].'themes/'.jApp::config()->theme.'/Images/news/thumbs/';
            $uploadDirRealSize = JELIX_APP_WWW_PATH.$gJConfig->urlengine['basePath'].'themes/'.jApp::config()->theme.'/Images/news/';

            if(!empty($_FILES['image'])) {

                $imageNewsInfos = getimagesize($_FILES['image']['tmp_name']);
                $newImageWidth = 100;
                $imagereduction = ( ($newImageWidth * 100)/$imageNewsInfos[0] );
                $newImageHeight = ( ($imageNewsInfos[1] * $imagereduction)/100 );
                $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);    

                if($imageNewsInfos['mime'] == 'image/jpeg' || $imageNewsInfos['mime'] == 'image/pjpeg') {
                    // Creating thumbs
                    $chosenImage = imagecreatefromjpeg($_FILES['image']['tmp_name']);                    
                    imagecopyresampled($newImage, $chosenImage, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $imageNewsInfos[0], $imageNewsInfos[1]);
                    imagedestroy($chosenImage);
                    imagejpeg($newImage, $uploadDirThumbs.$_FILES['image']['name'], 100);
                    // Create light picture
                    $chosenImage = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                    $newImage = imagecreatetruecolor($imageNewsInfos[0], $imageNewsInfos[1]);
                    imagecopyresampled($newImage, $chosenImage, 0, 0, 0, 0, $imageNewsInfos[0], $imageNewsInfos[1], $imageNewsInfos[0], $imageNewsInfos[1]);
                    imagejpeg($newImage, $uploadDirRealSize.$_FILES['image']['name'], 60);
                }

                if($imageNewsInfos['mime'] == 'image/png') {
                    // Creating thumbs
                    $chosenImage = imagecreatefrompng($_FILES['image']['tmp_name']);
                    imagecolortransparent($chosenImage);
                    imagecopyresampled($newImage, $chosenImage, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $imageNewsInfos[0], $imageNewsInfos[1]);
                    imagedestroy($chosenImage);
                    // Move originally png to server
                    imagepng($newImage, $uploadDirThumbs.$_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirRealSize);
                }                
            }

            $data = $form->getAllData();
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

