{meta_html css $j_basepath.'themes/default/Css/qtip.css'}
{meta_html css $j_basepath.'themes/default/Css/coms.css'}

<script type="text/javascript">//<![CDATA[
{literal}
jQuery(function (){
    manageFieldsFocus( 'jforms_news_comsform' );
});
{/literal}
//]]</script>

<div id="form">
	{form $form, 'news~default:saveComs'}
		{formcontrols array('pseudo','url','mail','text','news_id')}
                <div class="userFormItem {ifctrl 'message'}userFormMessage{/ifctrl}">
                    {ctrl_label}{ctrl_control}
                </div>
  		{/formcontrols}
  		<div> {formsubmit} </div>
  	{/form}
</div>