<?php

class jMessageHelper {

    /*
     * Returns an array of jMessages which type mathes argument
     * Those jMessages are consumed
     */
    public function getJMessagesRegex( $regex ) {
        
        $arrayMsgs = array();
        $arrayAllMsgs = jMessage::getAll();

        if( ! $arrayAllMsgs ) {
            return array();
        }

        foreach( $arrayAllMsgs as $messageType=>$arrayTypeMsgs )
        {
            if( preg_match($regex, $messageType) ) { //message type matches $regex
                if( $arrayTypeMsgs !== null && count($arrayTypeMsgs) > 0 ) { //test because of jMessage::clear setting to an empty array
                    $arrayMsgs[$messageType] = $arrayTypeMsgs ;
                }

                jMessage::clear($messageType);
            }
        }

        return $arrayMsgs;
   }

}
