<div id="newsPage">
	<h1 class="indent">News</h1>
	{if(isset($newsList))}
		{foreach $newsList as $news}
			<div class="news dotBkg">
				<img class="newsRibbon" src="{$j_themepath}Images/newsRibbon.png" alt="Ruban news">
				<h2>{$news->title}</h2>
				Par Sébastien&nbsp;le&nbsp;{$news->date}&nbsp;dans&nbsp;<a href="">{$news->theme_name}</a><a href=""><img class="smallImage" src="{$j_themepath}Images/bulle.png" alt="">100&nbsp;commentaires</a>
				<hr>
				<img class="mainPicture" src="{$j_themepath}Images/news/{$news->image}.png" alt="{$news->image}"><p>{$news->text}</p>
				<img src="{$j_themepath}Images/bottomNewsRibbon.png" alt="Ruban news bas" class="bottomRibbon">
			</div>
		{/foreach}
	{/if}
</div>
{zone 'news~themes'}