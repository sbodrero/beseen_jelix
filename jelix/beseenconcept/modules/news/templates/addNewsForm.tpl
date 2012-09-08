{meta_html csstheme 'Css/newsForm.css'}
{meta_html css $j_basepath.'themes/default/Css/qtip.css'}
{meta_html js $j_basepath."jelix/jquery/MultipleFiles/jquery.MultiFile.js"}
<script type="text/javascript">//<![CDATA[

{literal}
jQuery(function (){
    manageFieldsFocus( 'jforms_news_newsform' );
});

{/literal}
//]]</script>
<h2>Un projet ? Une question ?</h2>
<hr>
<div id="form">
	{form $form, 'news~default:saveNews'}
		{formcontrols array('title','theme_id','image','text')}
                <div class="userFormItem {ifctrl 'message'}userFormMessage{/ifctrl}">
                    {ctrl_label}{ctrl_control}
                </div>
  		{/formcontrols}
  		<div> {formsubmit} </div>
  	{/form}
</div>
