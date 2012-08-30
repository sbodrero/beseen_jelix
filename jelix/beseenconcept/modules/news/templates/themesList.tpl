<div id="themesList">
	<h2>Themes</h2>
	<hr>
	<ul>
		{foreach $themesList as $theme}
			<li><a href="{jurl 'news~showNewsByTheme', array('theme'=>$theme->nom)}">{$theme->theme_name}()</a></li>
		{/foreach}
		{if $isConnected}
			<li><a href="{jurl 'news~newsCrud:index'}">Gérer les news</a></li>
		{/if}
	</ul>
</div>