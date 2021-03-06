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
			<h2>Commentaires({$comsCount})</h2>
			<hr>
			{zone 'news~comsForm'}
		</div>
		{if isset($comsCount) && $comsCount > 0 }
		<div id="newsCom" class="dotBkg">
			{foreach $comsList as $coms}
				<div class="comsBody">
					{$coms->pseudo}&nbsp;le&nbsp;{$coms->date}
					<hr>
					<p>{$coms->text}</p>
				</div>
			{/foreach}
		</div>
		{/if}			
	{/if}
</div>