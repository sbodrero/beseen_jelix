<?php 

/**
*  commentaires formulaire
*/
class comsFormZone extends jZone
{
	protected $_tplname = 'comsForm.zone';

	protected function _prepareTpl() {

		$formsel = "comsform";
		$form = jForms::get($formsel);
		if($form === null) {
			$form = jForms::create($formsel);
		}
		$this->_tpl->assign('form', $form);
	}
}
?>