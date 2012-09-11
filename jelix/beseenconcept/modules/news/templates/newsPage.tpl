{zone 'structure~msgNotices', array('messageTypeRegex'=>'/^msg(Notice|Warning|Error)/')}
<h1 class="indent">News</h1>
<blockquote>
	<p>{@string.news.blockQuote@}</p>	
</blockquote>
<div id="newsWrapper">
{if(isset($newsList))}
	{foreach $newsList as $news}
		<div class="news dotBkg">
			<img class="newsRibbon" src="{$j_themepath}Images/newsRibbon.png" alt="Ruban news">
			<h2>{$news->title}</h2>
			Par Sébastien&nbsp;le&nbsp;{$news->date}&nbsp;dans&nbsp;<a href="">{$news->theme_name}</a>
			<a href="{jUrl 'news~showNews',array('id'=>$news->id)}" title="Voir les commentaires">
				<img class="smallImage" src="{$j_themepath}Images/bulle.png" alt="">&nbsp;commentaires
			</a>
			<hr>
			<img class="mainPicture" src="{$j_themepath}Images/news/thumbs/{$news->image}" alt="{$news->imageName}">
			<p>{$news->text}{if isset($news->textShort)}...{/if}</p>
			<div class="toolsBar">
				{if ($isConnected)}
				<a href="{jUrl 'news~editNews',array('id'=>$news->id)}" alt="Editer la news">Editer</a>
				<a href="{jUrl 'news~deleteNews',array('id'=>$news->id)}" alt="Supprimer la news">Supprimer</a>
				{/if}
				{if (isset($news->textShort))}	
				<a href="{jUrl 'news~prepareComsForm',array('id' => $news->id)}"><img src="{$j_themepath}Images/bottomNewsRibbon.png" alt="Voir le détail de la news"></a>
				{/if}
			</div>
		</div>
	{/foreach}
{/if}
<div id="newsMenuContainer" class="dotBkg">
	{zone 'news~themes'}
</div>
</div>
<script type="text/javascript">
{literal}

	$('#validateComs').bind('click', function(){
		refUrl = $(this).attr('href');
		$('#newsWrapper').append('<div id="showWaitingComsBox" class="dotBkg"><div id="ajaxLoader"></div></div>');
		//var waitingComsBoxTop = $(this).waitingComsBoxPosition();
		$('div#showWaitingComsBox').css({'top':'-20px','left':'25%'});
		$.get(refUrl, function(data) {
			$('div#showWaitingComsBox').find('div#ajaxLoader').remove();
			$('div#showWaitingComsBox').empty().append('<a class="closeCross"><span>X</span></a>');
            $('div#showWaitingComsBox').append(data).find('a.closeCross').bind("click", function(){
                $('div#showWaitingComsBox').remove();
            });
		})
		return false;
	});
{/literal}
</script>

