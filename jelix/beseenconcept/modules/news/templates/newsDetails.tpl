{zone 'structure~msgNotices', array('messageTypeRegex'=>'/^msg(Notice|Warning|Error)/')}
<div id="newsDetails">
	{if(isset($newsDetails))}
		{foreach $newsDetails as $details}
			<div id="newsBody" class="dotBkg">
				<img src="{$j_themepath}Images/news/{$details->image}" width="250" alt="">
				<h1>{$details->title}</h1>
				Par Sébastien&nbsp;le&nbsp;{$details->date}&nbsp;dans&nbsp;<a href="">{$details->theme_name}</a>
				<hr>
				{$details->text}
			</div>
		{/foreach}
		<div id="comsForm" class="dotBkg">
			{zone 'news~comsForm'}
		</div>
		<div id="newsCom" class="dotBkg">
			
		</div>			
	{/if}
</div>