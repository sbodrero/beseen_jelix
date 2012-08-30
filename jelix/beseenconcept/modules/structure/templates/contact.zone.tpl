{meta_html csstheme 'Css/contact.css'}
{meta_html css $j_basepath.'themes/default/Css/qtip.css'}
<script type="text/javascript">//<![CDATA[

{literal}
jQuery(function (){
    manageFieldsFocus( 'jforms_structure_contact' );
});
{/literal}
//]]</script>
<h2>Un projet ? Une question ?</h2>
<hr>
<div id="form">
	{form $form, 'structure~default:save'}
		{formcontrols array('name','email','message')}
                <div class="userFormItem {ifctrl 'message'}userFormMessage{/ifctrl}">
                    {ctrl_control}
                </div>
  		{/formcontrols}
  		<div> {formsubmit} </div>
  	{/form}
</div>