<?php
/**
 * @package   rbac
 * @subpackage   rbac
 * @author    yourname
 * @copyright 2010 yourname
 * @link      http://www.yourwebsite.undefined
 * @license    All right reserved
 */

class msgNoticesZone extends jZone {

    protected $_tplname = 'structure~msgNotices';

    /*public function __construct($params=array()){
        parent::__construct($params);
    }*/

    protected function _prepareTpl(){

        $msgTypeRegex = $this->param( 'messageTypeRegex' );

        $arrayMsgs = array();

        $arrayTypes = array();

        if( $msgTypeRegex ) {
            $jMsgHlperSrv = jClasses::getService( 'structure~jMessageHelper' );
            $arrayMsgs = $jMsgHlperSrv->getJMessagesRegex( $msgTypeRegex );
            foreach( array_keys($arrayMsgs) as $type ) {
                if( ! isset( $arrayTypes[$type] ) ) {
                    preg_match( $msgTypeRegex, $type, $matches );
                    $arrayTypes[$type] = $matches[0];
                }
            }
        }

        $this->_tpl->assign( 'arrayMsgs', $arrayMsgs );
        $this->_tpl->assign( 'arrayTypes', $arrayTypes );
        $this->_tpl->assign( 'currentUrl',
            'http'.(isset($_SERVER['HTTPS']) && strtolower($_SERVER["HTTPS"]) == "on"?'s':'').'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
    }

}
