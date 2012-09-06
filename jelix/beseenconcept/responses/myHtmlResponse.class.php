<?php
/**
* @package   beseenconcept
* @subpackage 
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/


require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class myHtmlResponse extends jResponseHtml {

    public $bodyTpl = 'structure~structure';

    function __construct() {
        parent::__construct();

        $this->setXhtmlOutput( false );

        $theme = jApp::config()->urlengine['basePath'].'themes/'.jApp::config()->theme.'/';
        $this->addCSSLink($theme.'Css/reset.css');
        $this->addCSSLink($theme.'Css/common.css');
        $this->addJSLink(jApp::config()->urlengine['jelixWWWPath'].'jquery/jquery.js');

        global $gJConfig;

        //$this->addHeadContent( '<link rel="icon" href="'. $gJConfig->urlengine['basePath'] .'favicon.ico" type="image/x-icon">' ) ;

        $script = $gJConfig->urlengine['jqueryPath'];

        $this->addHeadContent( '<!--[if lte IE 6]><script src="'. $script .'jquery.reject.min.js"></script>'.
            '<script>jQuery(function (){ $.reject({
                    imagePath: \''. $gJConfig->urlengine['jqueryPath'] .'jelix/jquery/jReject/' .'\',
                    header: \'Votre navigateur semble être Internet Explorer 5 ou 6\',
                    paragraph1: \'Ce site est compatible avec Internet Explorer 5 et 6 mais son utilisation serait bien plus agréable avec un navigateur récent.\',
                    paragraph2: \'Nous vous recommendons de mettre à jour votre navigateur. Voici une liste de navigateurs récents :\',
                    closeLink: \'Fermer cette fenêtre\',
                    closeMessage: \'\',
                    closeCookie: true
        });});  </script><![endif]-->' ) ;

        $this->addHeadContent('<!--[if lte IE 7]><body class="ie7"><![endif]-->');
        //$this->addHeadContent('<!--[if lte IE 7]><script src="'. $script .'ie7.js"></script><![endif]-->');
        //$this->addHeadContent('<!--[if lte IE 8]><script src="'. $script .'ie.js"></script><![endif]-->');
        $this->addHeadContent('<!--[if IE 8]><body class="ie8"><![endif]-->');
        $this->addHeadContent('<!--[if IE]><body class="ie"><![endif]-->');        
    }

    protected function outputDoctype (){
        echo '<!DOCTYPE html>', "\n";
        echo '<!--[if IE]><![endif]-->'; // see http://www.phpied.com/conditional-comments-block-downloads/
    }    

    protected function doAfterActions() {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..

        $this->title .=  (( $this->title != '' ) ? ' - ' : ''). jLocale::get('structure~string.app.title');        
        $this->body->assignIfNone('MAIN','<p>no content</p>');
    }
}
