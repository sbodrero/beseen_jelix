<div id="themesList">
	<img class="newsRibbon" src="{$j_themepath}Images/newsRibbon.png" alt="Ruban news">
	<h2>Themes</h2>
	<hr>
	<ul>
		{foreach $themesList as $theme}
			<li><a href="{jurl 'news~showNewsByTheme', array('theme'=>$theme->theme_name)}">{$theme->theme_name}&nbsp;({$theme->themes_news_count})</a></li>
		{/foreach}
		{if $isConnected}
			<li><a href="{jurl 'news~prepareNewsForm'}">Ajouter une news</a></li>
		{/if}
	</ul>
</div>