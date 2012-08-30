<?php
/**
* @package     
* @subpackage  
* @author      Brice Tencé
* @contributor 
* @copyright   2011 RDE
* @link        
* @licence     
*/

include_once(JELIX_LIB_PATH.'plugins/jforms/html/html.jformsbuilder.php');

/**
 * HTML form builder
 * @package     jelix
 * @subpackage  jelix-plugins
 */
class dynhtmlJformsBuilder extends htmlJformsBuilder {


    public function outputAllControls() {

        echo '<table class="jforms-table" border="0">';
        foreach( $this->_form->getRootControls() as $ctrlref=>$ctrl){
            if($ctrl->type == 'submit' || $ctrl->type == 'reset' || $ctrl->type == 'hidden') continue;
            if(!$this->_form->isActivated($ctrlref)) continue;
            if($ctrl->type == 'group') {
                echo '<tr><td colspan="2"><div class="userFormItem">';
                $this->outputControl($ctrl);
                echo '</div></td></tr>';
            }else{
                echo '<tr><th scope="row">';
                $this->outputControlLabel($ctrl);
                echo '</th><td class="userFormItemCel"><div class="userFormItem">';
                $this->outputControl($ctrl);
                echo "</div></td></tr>\n";
            }
        }
        echo '</table> <div class="jforms-submit-buttons">';
        if ( $ctrl = $this->_form->getReset() ) {
            if(!$this->_form->isActivated($ctrl->ref)) continue;
            $this->outputControl($ctrl);
            echo ' ';
        }
        foreach( $this->_form->getSubmits() as $ctrlref=>$ctrl){
            if(!$this->_form->isActivated($ctrlref)) continue;
            $this->outputControl($ctrl);
            echo ' ';
        }
        echo "</div>\n";
    }


    public function outputMetaContent($t) {
        global $gJCoord, $gJConfig;
        $resp= jApp::coord()->response;
        if($resp === null || $resp->getType() !='html'){
            return;
        }
        $www = $gJConfig->urlengine['jelixWWWPath'];
        $jq = $gJConfig->urlengine['jqueryPath'];
        $bp = $gJConfig->urlengine['basePath'];
        $resp->addJSLink($jq.'jquery.viewport.js');
        $resp->addJSLink($jq.'scrollTo/jquery.scrollTo.js');
        $resp->addJSLink($jq.'qtip/jquery.qtip.js');
        $resp->addJSLink($jq.'include/jquery.include.js');
        $resp->addJSLink($www.'js/jforms_jquery.js');
        $resp->addCSSLink($www.'design/jform.css');

        // specific meta for controls 
        foreach($t->_vars as $k=>$v){
            if(!$v instanceof jFormsBase)
                continue;
            foreach($v->getHtmlEditors() as $ed) {
                if(isset($gJConfig->htmleditors[$ed->config.'.engine.file'])){
                    if(is_array($gJConfig->htmleditors[$ed->config.'.engine.file'])){
                        foreach($gJConfig->htmleditors[$ed->config.'.engine.file'] as $url) {
                            $resp->addJSLink($bp.$url);
                        }
                    }else
                        $resp->addJSLink($bp.$gJConfig->htmleditors[$ed->config.'.engine.file']);
                }
                if(isset($gJConfig->htmleditors[$ed->config.'.config']))
                    $resp->addJSLink($bp.$gJConfig->htmleditors[$ed->config.'.config']);
                $skin = $ed->config.'.skin.'.$ed->skin;
                if(isset($gJConfig->htmleditors[$skin]) && $gJConfig->htmleditors[$skin] != '')
                    $resp->addCSSLink($bp.$gJConfig->htmleditors[$skin]);
            }
            
            $datepicker_default_config = $gJConfig->forms['datepicker'];
            foreach($v->getControls() as $ctrl){
                if($ctrl instanceof jFormsControlDate || get_class($ctrl->datatype) == 'jDatatypeDate' || get_class($ctrl->datatype) == 'jDatatypeLocaleDate'){
                    $config = isset($ctrl->datepickerConfig)?$ctrl->datepickerConfig:$datepicker_default_config;
                    $resp->addJSLink($bp.$gJConfig->datepickers[$config]);
                }
                if($ctrl instanceof jFormsControlEmail || get_class($ctrl->datatype) == 'jDatatypeEmail'){
                    $resp->addJSLink($jq.'jquery.mailcheck.js');
                }
            }

            foreach($v->getWikiEditors() as $ed) {
                if(isset($gJConfig->wikieditors[$ed->config.'.engine.file']))
                    $resp->addJSLink($bp.$gJConfig->wikieditors[$ed->config.'.engine.file']);
                if(isset($gJConfig->wikieditors[$ed->config.'.config.path'])) {
                    $p = $bp.$gJConfig->wikieditors[$ed->config.'.config.path'];
                    $resp->addJSLink($p.$GLOBALS['gJConfig']->locale.'.js');
                    $resp->addCSSLink($p.'style.css');
                }
                if(isset($gJConfig->wikieditors[$ed->config.'.skin']))
                    $resp->addCSSLink($bp.$gJConfig->wikieditors[$ed->config.'.skin']);
            }
        }
    }

    /**
     * output the header content of the form
     * @param array $params some parameters <ul>
     *      <li>"errDecorator"=>"name of your javascript object for error listener"</li>
     *      <li>"method" => "post" or "get". default is "post"</li>
     *      </ul>
     */
    public function outputHeader($params){
        $this->options = array_merge(array('errorDecorator'=>'AppErrorDecorator',
                'helpDecorator'=>'jFormsJQHelpDecoratorQtip', 'jsonFieldCheckerUrl'=>'', 'jsonCheckedFields'=>'',
                'method'=>'post', 'autocomplete'=>''), $params);

        if (isset($params['attributes']))
            $attrs = $params['attributes'];
        else
            $attrs = array();

        echo '<form';
        if (preg_match('#^https?://#',$this->_action)) {
            $urlParams = $this->_actionParams;
            $attrs['action'] = $this->_action;
        } else {
            $url = jUrl::get($this->_action, $this->_actionParams, 2); // retourne le jurl correspondant
            $urlParams = $url->params;
            $attrs['action'] = $url->getPath();
        }
        $attrs['method'] = $this->options['method'];
        $attrs['id'] = $this->_name;

        if($this->_form->hasUpload())
            $attrs['enctype'] = "multipart/form-data";

        $this->_outputAttr($attrs);
        echo '>';

        $this->outputHeaderScript();

        $hiddens = '';
        foreach ($urlParams as $p_name => $p_value) {
            $hiddens .= '<input type="hidden" name="'. $p_name .'" value="'. htmlspecialchars($p_value). '"'.$this->_endt. "\n";
        }

        foreach ($this->_form->getHiddens() as $ctrl) {
            if(!$this->_form->isActivated($ctrl->ref)) continue;
            $hiddens .= '<input type="hidden" name="'. $ctrl->ref.'" id="'.$this->_name.'_'.$ctrl->ref.'" value="'. htmlspecialchars($this->_form->getData($ctrl->ref)). '"'.$this->_endt. "\n";
        }

        if($this->_form->securityLevel){
            $tok = $this->_form->createNewToken();
            $hiddens .= '<input type="hidden" name="__JFORMS_TOKEN__" value="'.$tok.'"'.$this->_endt. "\n";
        }

        if($hiddens){
            echo '<div class="jforms-hiddens">',$hiddens,'</div>';
        }

        $errors = $this->_form->getContainer()->errors;
        if(count($errors)){
            $ctrls = $this->_form->getControls();
            echo '<ul id="'.$this->_name.'_errors" class="jforms-error-list">';
            $errRequired='';
            foreach($errors as $cname => $err){
                if(!$this->_form->isActivated($ctrls[$cname]->ref)) continue;
                if ($err === jForms::ERRDATA_REQUIRED) {
                    if ($ctrls[$cname]->alertRequired){
                        echo '<li>', $ctrls[$cname]->alertRequired,'</li>';
                    }
                    else {
                        echo '<li>', jLocale::get('jelix~formserr.js.err.required', $ctrls[$cname]->label),'</li>';
                    }
                }else if ($err === jForms::ERRDATA_INVALID) {
                    if($ctrls[$cname]->alertInvalid){
                        echo '<li>', $ctrls[$cname]->alertInvalid,'</li>';
                    }else{
                        echo '<li>', jLocale::get('jelix~formserr.js.err.invalid', $ctrls[$cname]->label),'</li>';
                    }
                }
                elseif ($err === jForms::ERRDATA_INVALID_FILE_SIZE) {
                    echo '<li>', jLocale::get('jelix~formserr.js.err.invalid.file.size', $ctrls[$cname]->label),'</li>';
                }
                elseif ($err === jForms::ERRDATA_INVALID_FILE_TYPE) {
                    echo '<li>', jLocale::get('jelix~formserr.js.err.invalid.file.type', $ctrls[$cname]->label),'</li>';
                }
                elseif ($err === jForms::ERRDATA_FILE_UPLOAD_ERROR) {
                    echo '<li>', jLocale::get('jelix~formserr.js.err.file.upload', $ctrls[$cname]->label),'</li>';
                }
                elseif ($err != '') {
                    echo '<li>', $err,'</li>';
                }
            }
            echo '</ul>';
        }
    }


    protected function outputHeaderScript(){
        global $gJConfig, $gJCoord;
        // no scope into an anonymous js function, because jFormsJQ.tForm is used by other generated source code
        $resp= jApp::coord()->response;
        echo '<script type="text/javascript">
jFormsJQ.config = {locale:'.$this->escJsStr($gJConfig->locale).
    ',basePath:'.$this->escJsStr($gJConfig->urlengine['basePath']).
    ',jqueryPath:'.$this->escJsStr($gJConfig->urlengine['jqueryPath']).
    ',jelixWWWPath:'.$this->escJsStr($gJConfig->urlengine['jelixWWWPath']).'};
jFormsJQ.tForm = new jFormsJQForm(\''.$this->_name.'\',\''.$this->_form->getSelector().'\',\''.$this->_form->getContainer()->formId.'\');
jFormsJQ.tForm.setErrorDecorator(new '.$this->options['errorDecorator'].'());
jFormsJQ.tForm.setJsonFieldCheckerUrl("'.$this->options['jsonFieldCheckerUrl'].'");
jFormsJQ.tForm.setJsonCheckedFields("'.$this->options['jsonCheckedFields'].'");
'.(array_key_exists('helpDecorator', $this->options) && $this->options['helpDecorator'] ? 'jFormsJQ.tForm.setHelpDecorator(new '.$this->options['helpDecorator'].'());' : '').'
jFormsJQ.declareForm(jFormsJQ.tForm);
</script>';
    }

    protected function jsInput($ctrl) {

        $datatype = array('jDatatypeBoolean'=>'Boolean','jDatatypeDecimal'=>'Decimal','jDatatypeInteger'=>'Integer','jDatatypeHexadecimal'=>'Hexadecimal',
                        'jDatatypeDateTime'=>'Datetime','jDatatypeDate'=>'Date','jDatatypeTime'=>'Time',
                        'jDatatypeUrl'=>'Url','jDatatypeEmail'=>'Email','jDatatypeIPv4'=>'Ipv4','jDatatypeIPv6'=>'Ipv6');
        $isLocale = false;
        $data_type_class = get_class($ctrl->datatype);
        if(isset($datatype[$data_type_class]))
            $dt = $datatype[$data_type_class];
        else if ($ctrl->datatype instanceof jDatatypeLocaleTime)
            { $dt = 'Time'; $isLocale = true; }
        else if ($ctrl->datatype instanceof jDatatypeLocaleDate)
            { $dt = 'LocaleDate'; $isLocale = true; }
        else if ($ctrl->datatype instanceof jDatatypeLocaleDateTime)
            { $dt = 'LocaleDatetime'; $isLocale = true; }
        else
            $dt = 'String';

        $this->jsContent .="c = new ".$this->jFormsJsVarName."Control".$dt."('".$ctrl->ref."', ".$this->escJsStr($ctrl->label).", ".$this->escJsStr($ctrl->hint).");\n";
        if ($isLocale)
            $this->jsContent .="c.lang='".$GLOBALS['gJConfig']->locale."';\n";

        $maxl= $ctrl->datatype->getFacet('maxLength');
        if($maxl !== null)
            $this->jsContent .="c.maxLength = '$maxl';\n";

        $minl= $ctrl->datatype->getFacet('minLength');
        if($minl !== null)
            $this->jsContent .="c.minLength = '$minl';\n";
        $re = $ctrl->datatype->getFacet('pattern');
        if($re !== null)
            $this->jsContent .="c.regexp = ".$re.";\n";

        $this->commonJs($ctrl);
    }


    public function outputControlLabel($ctrl){
        if($ctrl->type == 'hidden' || $ctrl->type == 'group') return;
        $required = ($ctrl->required == false || $ctrl->isReadOnly()?'':' jforms-required');
        $reqhtml = ($required?'<span class="jforms-required-star">*</span>':'');
        $inError = (isset($this->_form->getContainer()->errors[$ctrl->ref]) ?' jforms-error':'');
        $hint = ($ctrl->hint == ''?'':' title="'.htmlspecialchars($ctrl->hint).'"');
        $id = $this->_name.'_'.$ctrl->ref;
        $idLabel = ' id="'.$id.'_label"';
        //$label = htmlspecialchars($ctrl->label) ; //should be more secure and cleaner but how could we put entities ?
        $label = $ctrl->label ;
        if($ctrl->type == 'output' || $ctrl->type == 'checkboxes' || $ctrl->type == 'radiobuttons' || $ctrl->type == 'date' || $ctrl->type == 'datetime' || $ctrl->type == 'choice'){
            echo '<span class="jforms-label',$required,$inError,'"',$idLabel,$hint,'>',$label,$reqhtml,"</span>\n";
        }else if($ctrl->type != 'submit' && $ctrl->type != 'reset'){
            echo '<label class="jforms-label',$required,$inError,'" for="',$id,'"',$idLabel,$hint,'>',$label,$reqhtml,"</label>\n";
        }
    }


    protected function jsTextarea($ctrl, $withjsobj=true) {
        if ($withjsobj)
            $this->jsContent .="c = new ".$this->jFormsJsVarName."ControlString('".$ctrl->ref."', ".$this->escJsStr($ctrl->label).", ".$this->escJsStr($ctrl->hint).");\n";

        $maxl= $ctrl->datatype->getFacet('maxLength');
        if($maxl !== null)
            $this->jsContent .="c.maxLength = '$maxl';\n";

        $minl= $ctrl->datatype->getFacet('minLength');
        if($minl !== null)
            $this->jsContent .="c.minLength = '$minl';\n";

        $this->commonJs($ctrl);
    }


    protected function jsDate($ctrl){
        $this->jsContent .= "c = new ".$this->jFormsJsVarName."ControlDate('".$ctrl->ref."', ".$this->escJsStr($ctrl->label).", ".$this->escJsStr($ctrl->hint).");\n";
        $this->jsContent .= "c.multiFields = true;\n";
        $minDate = $ctrl->datatype->getFacet('minValue');
        $maxDate = $ctrl->datatype->getFacet('maxValue');
        if($minDate)
            $this->jsContent .= "c.minDate = '".$minDate->toString(jDateTime::DB_DFORMAT)."';\n";
        if($maxDate)
            $this->jsContent .= "c.maxDate = '".$maxDate->toString(jDateTime::DB_DFORMAT)."';\n";
        $this->commonJs($ctrl);
    }


    protected function outputDate($ctrl, &$attr){
        $attr['id'] = $this->_name.'_'.$ctrl->ref.'_';
        $v = array('year'=>'','month'=>'','day'=>'');
        if(preg_match('#^(\d{4})?-(\d{2})?-(\d{2})?$#',$this->_form->getData($ctrl->ref),$matches)){
            if(isset($matches[1]))
                $v['year'] = $matches[1];
            if(isset($matches[2]))
                $v['month'] = $matches[2];
            if(isset($matches[3]))
                $v['day'] = $matches[3];
        }
        $f = jLocale::get('jelix~format.date');
        $separator = '';
        if($GLOBALS['gJConfig']->forms['controls.datetime.input'] == 'textboxes') {
            $separator = '<span class="dtSep">/</span>';
        }

        echo '<span class="clearfix jforms-ctl-date-wrapper jforms-ctl-'.$ctrl->ref.'">';
        for($i=0;$i<strlen($f);$i++){
            if($f[$i] == 'Y') {
                if( $i > 0 ) { echo $separator; }
                $this->_outputDateControlYear($ctrl, $attr, $v['year']);
            } else if($f[$i] == 'm') {
                if( $i > 0 ) { echo $separator; }
                $this->_outputDateControlMonth($ctrl, $attr, $v['month']);
            } else if($f[$i] == 'd') {
                if( $i > 0 ) { echo $separator; }
                $this->_outputDateControlDay($ctrl, $attr, $v['day']);
            } else
                echo ' ';
        }
        echo '</span>';
    }


    protected function _outputDateControlDay($ctrl, $attr, $value){
        $attr['name'] = $ctrl->ref.'[day]';
        $attr['id'] .= 'day';
        if($GLOBALS['gJConfig']->forms['controls.datetime.input'] == 'textboxes'){
            $attr['value'] = $value;
            echo '<input type="text" size="2" maxlength="2" placeholder="jj" pattern="[0-3]?[0-9]"';
            $this->_outputAttr($attr);
            echo $this->_endt;
        }
        else{
            echo '<select';
            $this->_outputAttr($attr);
            echo '><option value="">'.htmlspecialchars(jLocale::get('jelix~jforms.date.day.label')).'</option>';
            for($i=1;$i<32;$i++){
                $k = ($i<10)?'0'.$i:$i;
                echo '<option value="'.$k.'"'.($k == $value?' selected="selected"':'').'>'.$k.'</option>';
            }
            echo '</select>';
        }
    }


    protected function _outputDateControlMonth($ctrl, $attr, $value){
        $attr['name'] = $ctrl->ref.'[month]';
        $attr['id'] .= 'month';
        if($GLOBALS['gJConfig']->forms['controls.datetime.input'] == 'textboxes') {
            $attr['value'] = $value;
            echo '<input type="text" size="2" maxlength="2" placeholder="mm" pattern="[0-1]?[0-9]"';
            $this->_outputAttr($attr);
            echo $this->_endt;
        }
        else{
            $monthLabels = $GLOBALS['gJConfig']->forms['controls.datetime.months.labels'];
            echo '<select';
            $this->_outputAttr($attr);
            echo '><option value="">'.htmlspecialchars(jLocale::get('jelix~jforms.date.month.label')).'</option>';
            for($i=1;$i<13;$i++){
                $k = ($i<10)?'0'.$i:$i;
                if($monthLabels == 'names')
                    $l = htmlspecialchars(jLocale::get('jelix~date_time.month.'.$k.'.label'));
                else if($monthLabels == 'shortnames')
                    $l = htmlspecialchars(jLocale::get('jelix~date_time.month.'.$k.'.shortlabel'));
                else
                    $l = $k;
                echo '<option value="'.$k.'"'.($k == $value?' selected="selected"':'').'>'.$l.'</option>';
            }
            echo '</select>';
        }
    }

    protected function _outputDateControlYear($ctrl, $attr, $value){
        $attr['name'] = $ctrl->ref.'[year]';
        $attr['id'] .= 'year';
        if($GLOBALS['gJConfig']->forms['controls.datetime.input'] == 'textboxes') {
            $attr['value'] = $value;
            echo '<input type="text" size="4" maxlength="4" placeholder="aaaa" pattern="[12][90][0-9][0-9]"';
            $this->_outputAttr($attr);
            echo $this->_endt;
        }
        else{
            $minDate = $ctrl->datatype->getFacet('minValue');
            $maxDate = $ctrl->datatype->getFacet('maxValue');
            if($minDate && $maxDate){
                echo '<select';
                $this->_outputAttr($attr);
                echo '><option value="">'.htmlspecialchars(jLocale::get('jelix~jforms.date.year.label')).'</option>';
                for($i=$maxDate->year;$i>=$minDate->year;$i--)
                    echo '<option value="'.$i.'"'.($i == $value?' selected="selected"':'').'>'.$i.'</option>';
                echo '</select>';
            }
            else{
                $attr['value'] = $value;
                echo '<input type="text" size="4" maxlength="4" placeholder="aaaa" pattern="[0-9][0-9][0-9][0-9]"';
                $this->_outputAttr($attr);
                echo $this->_endt;
            }
        }
    }

    protected function outputRadiobuttons($ctrl, &$attr) {
        echo '<span class="jforms-radios jforms-wrapper-'.$ctrl->ref.'">';
        parent::outputRadiobuttons($ctrl, $attr);
        echo '</span>';
    }


    protected function outputCheckboxes($ctrl, &$attr) {
        echo '<span class="jforms-checkboxes">';
        parent::outputCheckboxes($ctrl, $attr);
        echo '</span>';
    }


    protected function jsMenulist($ctrl) {

        $this->jsContent .="c = new ".$this->jFormsJsVarName."ControlString('".$ctrl->ref."', ".$this->escJsStr($ctrl->label).", ".$this->escJsStr($ctrl->hint).");\n";

        $this->commonJs($ctrl);
    }

    protected function jsRadiobuttons($ctrl) {

        $this->jsContent .="c = new ".$this->jFormsJsVarName."ControlString('".$ctrl->ref."', ".$this->escJsStr($ctrl->label).", ".$this->escJsStr($ctrl->hint).");\n";

        $this->commonJs($ctrl);
    }




    public function outputFooter(){
        global $gJCoord;
        $resp= jApp::coord()->response;
        echo '<script type="text/javascript">
(function(){var c, c2;
'.$this->jsContent.$this->lastJsContent.'
})();
</script>';
        echo '</form>';
    }


    protected function outputSubmit($ctrl, $attr) {
        unset($attr['readonly']);
        $attr['class'] = 'jforms-submit';
        $attr['type'] = 'submit';

        if($ctrl->standalone){
            $attr['value'] = $ctrl->label;
            echo '<input';
            $this->_outputAttr($attr);
            echo $this->_endt;
            if( array_key_exists('safeEnter', $this->options) && $this->options['safeEnter'] == 'on' ) {
                global $gJCoord, $gJConfig;
                $resp = $gJCoord->response;
                $www = $gJConfig->urlengine['basePath'];
                $resp->addJSLink($www.'js/jquery/jquery.safeEnter.js');
                echo '<script type="text/javascript">' . "jQuery(function (){ $('form#".$this->_name." input, form#".$this->_name." select')".
                    ".listenForEnter().bind('pressedEnter', function() { $('form#".$this->_name."').submit(); });".
                    "});</script>";
            }
        }else{
            $id = $this->_name.'_'.$ctrl->ref.'_';
            $attr['name'] = $ctrl->ref;
            foreach($ctrl->datasource->getData($this->_form) as $v=>$label){
                // because IE6 sucks with <button type=submit> (see ticket #431), we must use input :-(
                $attr['value'] = $label;
                $attr['id'] = $id.$v;
                echo ' <input';
                $this->_outputAttr($attr);
                echo $this->_endt;
            }
        }
    }



    protected function jsSecret($ctrl) {
        $this->jsContent .="c = new ".$this->jFormsJsVarName."ControlSecret('".$ctrl->ref."', ".$this->escJsStr($ctrl->label).", ".$this->escJsStr($ctrl->hint).");\n";

        $maxl= $ctrl->datatype->getFacet('maxLength');
        if($maxl !== null)
            $this->jsContent .="c.maxLength = '$maxl';\n";

        $minl= $ctrl->datatype->getFacet('minLength');
        if($minl !== null)
            $this->jsContent .="c.minLength = '$minl';\n";
        $re = $ctrl->datatype->getFacet('pattern');
        if($re !== null)
            $this->jsContent .="c.regexp = ".$re.";\n";
        $this->commonJs($ctrl);
    }

}

