<?php 

class contactZone extends jZone {

	protected $_tplname='contact.zone';

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