<?php 

class contactUsZone extends jZone {

	protected $_tplname='contactUs.zone';

   	protected function _prepareTpl(){

        $formSel = 'contact' ;
        $form = jForms::get($formSel);
        if ($form === null) {
            $form = jForms::create($formSel);
        }
       	$this->_tpl->assign('form', $form);
   }	
}
?>