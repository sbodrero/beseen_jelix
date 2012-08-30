{assign $atLeast1Msg=false}

{foreach $arrayMsgs as $type=>$messages}
    {foreach $messages as $message}
    {assign $atLeast1Msg=true}
    <div class="noticeBox {$type}{if $type!=$arrayTypes[$type]} {$arrayTypes[$type]}{/if}">
        {if ! isset($nonClosable) || ! $nonClosable}
        <a href="{$currentUrl}" title="Fermer" class="closeCross"><span>Fermer</span></a>
        {/if}
        <p>{$message}</p>
    </div>
    {/foreach}
{/foreach}


{if $atLeast1Msg}
<script type="text/javascript">
    {literal}

    jQuery(function (){
            $('div.noticeBox a.closeCross').click( function() {
                $(this).parent('div.noticeBox').slideUp();
                return false;
                } );

            });

    {/literal}
</script>
{/if}

