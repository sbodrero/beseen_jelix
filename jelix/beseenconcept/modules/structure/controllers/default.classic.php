<?php
/**
* @package   beseenconcept
* @subpackage structure
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/
jClasses::inc('structure~defines');

class defaultCtrl extends jController {
    /**
    *   Prepare welcome page cause form must be initialized before
    */
    function index() {

        jForms::clean('contact');
        $form = jForms::create('contact');
        $form = jForms::clear('contact');

        jMessage::clear('msgNoticeContact');

        $rep= $this->getResponse('redirect');
        $rep->action='default:showWelcomePage';
        return $rep;
    }

    function showWelcomePage() {
        $rep = $this->getResponse('html') ;
        $rep->title .=  jLocale::get('string.siteTitle');

        $tpl = new jTpl();

        $frontImage = 'mainBan';
        jLog::dump($frontImage);

        $rep->body->assign('frontImage', $frontImage);
        $rep->body->assign( 'MAIN', $tpl->fetch('structure~welcomePage') );

        return $rep;
    }


    function prepareContactPage() {

        jForms::clean('contact');
        $form = jForms::create('contact');
        $form = jForms::clear('contact');

        jMessage::clear('msgNoticeContact');

        $rep= $this->getResponse('redirect');
        $rep->action='default:showContactPage';
        return $rep;        
    }

    function showContactPage() {
        $rep = $this->getResponse('html') ;
        $rep->title .=  jLocale::get('string.contactTitle');

        $tpl = new jTpl();

        $frontImage = 'callMe';
        
        $rep->body->assign('frontImage', $frontImage);
        $rep->body->assign( 'MAIN', $tpl->fetch('structure~contactPage') );

        return $rep;       
    }

    function showAboutPage() {
        $rep = $this->getResponse('html') ;
        $rep->title .=  jLocale::get('string.aboutTitle');

        $tpl = new jTpl();

        $frontImage = 'about';
        
        $rep->body->assign('frontImage', $frontImage);
        $rep->body->assign( 'MAIN', $tpl->fetch('structure~aboutPage') );

        return $rep;         
    }

    function save() {

    	$form = jForms::fill('structure~contact');
    	if (!$form->check()) {
        	// invalide : on redirige vers l'action d'affichage
        	$rep = $this->getResponse('redirect');
        	$rep->action='structure~default:showWelcomePage';
            return $rep;
    	}
        if ($form->check()) {
            $data = $form->getAllData() ;
            global $gJConfig;
            $destinataireAddress = $gJConfig->mailer['webmasterEmail'];

            $mail = new jMailer(); 
            $mail->FromName = jLocale::get('string.mail.new.from');
            $mail->Subject = jLocale::get('string.mail.new.sender') . ' ' . $data['name'];

            $tpl = new jTpl();
            $tpl->assign('nameMsg',$data['name']);
            $tpl->assign('textMsg',nl2br($data['message']));
            $tpl->assign('textMsgText',$data['message']);

            $author = $data['email'];

            $mail->From = $author;
            $mail->FromName = $author;

            $mail->Sender = $author;
            $tpl->assign('author', $author);

            $mailTemplate = 'mail.contact';
            $mail->MsgHTML ( $tpl->fetch($mailTemplate, 'html') );
            $mail->AltBody = $tpl->fetch($mailTemplate.'.text', 'text');

            $mail->AddAddress($destinataireAddress);

            $mailSent = true;
            try {
                if( ! $mail->Send() ) {
                    $mailSent = false;
                }
            }
            catch (Exception $e) {
                $mailSent = false;
            }

            if( ! $mailSent ) {
                jMessage::add( jLocale::get('string.contact.errorMail'), 'msgErrorContact' ); //modalHTMLContact
                $rep= $this->getResponse('redirect');
                $rep->action='default:showWelcomePage';
                return $rep;
            }

            jForms::destroy('contact');

            jMessage::add( jLocale::get('string.contact.mailOk'), 'msgNoticeContact' );
            $rep= $this->getResponse('redirect');
            $rep->action='default:index';

            return $rep;
        }
    
    }

    public function cvDownload() {

        global $gJConfig;  //TODO count downloads

        $name =  CV_NAME_FOR_DOWNLOADER;

        $outputFileName = CV_NAME_FOR_DOWNLOADER;
        $fileName = JELIX_APP_WWW_PATH.$gJConfig->AppSection['webmediaPath'].$gJConfig->AppSection['webmediaPathSuffixPdf'].CV;

        if( strlen($fileName) == 0 ||
            !file_exists($fileName) ) {
            trigger_error( "Troubles encountered when trying to have a user download $filename ", E_USER_WARNING );
            $rep = $this->getResponse('redirect');
            $rep->action='structure~error:notFound';
            return $rep;
        }
        $this->udpatePdfCount($name);
        $resp = $this->getResponse('binary');
        $resp->outputFileName = $outputFileName;
        $resp->mimeType = 'application/pdf';
        $resp->doDownload = true;
        $resp->fileName = $fileName;

        return $resp;
    }

    public function udpatePdfCount($name='') {

        if($this->param('name')) {
            $name = $this->param('name');
        }

        $daoPfd = jDao::get('pdfdao');

        $currentCount = $daoPfd->findCountByName($name);
        if(!$currentCount) {
            $record = jDao::createRecord('pdfdao');
            $record->name = $name;
            $record->count = 1;
            $daoPfd->insert($record);
        } else {
           $daoPfd->updateCountByName($name, $currentCount->count+1);
        }

        $rep = $this->getResponse('redirect');
        $rep->action = 'structure~showAboutPage';
        return $rep;
    }    
}

