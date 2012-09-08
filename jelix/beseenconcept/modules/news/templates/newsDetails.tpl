{zone 'structure~msgNotices', array('messageTypeRegex'=>'/^msg(Notice|Warning|Error)/')}
<div id="newsdetails" class="dotBkg">
	{if(isset($newsDetails))}
		{foreach $newsDetails as $details}
			<img src="{$j_themepath}Images/news/{$details->image}" width="250" alt="">
			<h1>{$details->title}</h1>
			Par Sébastien&nbsp;le&nbsp;{$details->date}&nbsp;dans&nbsp;<a href="">{$details->theme_name}</a>
			<hr>
			<div id="newsBody">{$details->text}</div>
		{/foreach}	
	{/if}

</div>