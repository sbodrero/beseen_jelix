<div id="wrapper">
	<div id="header">
		<div id="mainTitle" class="textShadow"><a href="{jUrl 'structure~index'}">be seen concept</a></div>
		<img src="{$j_themepath}Images/sticker.png" alt="Ruban rouge">
		<div id="nav">
			<ul>
				<li><a href="{jUrl 'structure~index'}">accueil</a></li>
				<li><a href="{jUrl 'news~index'}">news</a></li>
				<li><a href="{jUrl 'structure~prepareContactPage'}">contact</a></li>
				<li><a href="{jUrl 'structure~showAboutPage'}">à propos</a></li>
			</ul>
		</div>
	</div>
	<div id="picture">
	<img src="{$j_themepath}Images/{$frontImage}.png" alt="Bandeau">
	</div>
	{$MAIN}
</div>
<div id="footer"></div>

{*========================== analytics =======================*}
{if $useGoogleAnalytics}
{literal}
<script type="text/javascript">
jQuery(function (){
  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
  jQuery.getScript(gaJsHost + "google-analytics.com/ga.js", function(){
{/literal}
{foreach explode( ' ', $googleAnalyticsId ) as $analyticsId}
      _gat._getTracker("{$analyticsId}")._trackPageview();
{/foreach}
{literal}
  });
});
</script>
{/literal}
{/if}
