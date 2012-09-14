{meta_html csstheme 'Css/contactUs.css'}
{meta_html css $j_basepath.'themes/default/Css/qtip.css'}
{meta_html keywords "be seen concept : contact"}
{meta_html description "Contacter"}

<script type="text/javascript">//<![CDATA[

{literal}
jQuery(function (){
    manageFieldsFocus( 'jforms_structure_contact' );
});
{/literal}
//]]</script>

<div id="mainFormtBlock" >
    <div class="userForm">
        {form $form, 'structure~default:save',
        array(),'', array('errorDecorator'=>'AppErrorDecorator', 'helpDecorator'=>'jFormsJQHelpDecoratorQtip')}
                
        <fieldset>
                <div class="contactContent">
            {formcontrols array('name', 'email', 'message')}
                    <div class="userFormItem {ifctrl 'message'}userFormMessage{/ifctrl}">
                        {ctrl_label}{ctrl_control}
                    </div>
            {/formcontrols}
                </div>       
                <div class="actionsFormItem sendMsgContact validate">{formsubmit}</div>
        </fieldset>            
        {/form}
    </div>
    <div class="infoTile">
        <div id="accntInfo"><span class="controlHelper controlRequired">*</span>&nbsp;: {@structure~string.forms.requiredFields@}</div>
    </div>
</div>
