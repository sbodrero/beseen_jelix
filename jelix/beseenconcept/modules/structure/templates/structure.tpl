 {meta_html description "beseenconcept.fr : Développeur web et applicatif sur Pau, Pyrénées-Atlantiques"}
 {meta_html keywords "beseenconcept.fr, sites web, site internet, applications web, développeur, open source, jelix, html5, css3, jquery, dolibarr, pau"}
{meta_html autor "Sébastien Bodrero"}
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
<div id="footer">
	<a href="http://www.viadeo.com/profile/002hv75fne0n73r?ga_from=Fu:/tableaudebord/accueil/;Fb%3AN-topmenu%3BFe%3AL1%3B" title="Viadeo" target="_blanck">
		<img src="{$j_themepath}Images/vimeo.png" alt="Logo Viadeo">
	</a>
	<a href="http://www.facebook.com/sebastien.bodrero" title="Facebook" target="_blanck">
		<img src="{$j_themepath}Images/facebook.png" alt="Logo Facebook">
	</a>
	<a href="http://beknown.com/sebastien-bodrero" title="Beknown" target="_blanck">
		<img src="{$j_themepath}Images/beknown.png" alt="Logo BeKnown">
	</a>
	<a class="twitter-timeline"  href="https://twitter.com/sbodrero" data-widget-id="243695131553841154" title="Twitter">
		<img src="{$j_themepath}Images/twitter.png" alt="Logo Twitter">
	</a>
	<a href="mailto:sebdebillere@hotmail.fr" titile="email"><img src="{$j_themepath}Images/email-front.png" alt="Logo Email">
	</a>
	<a a href="{jurl 'jauth~login:form'}" title="Login">
		<img src="{$j_themepath}Images/user.png" alt="Authentification">
	</a>
</div>

{*========================== analytics and twitter=======================*}
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
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script>
{/literal}
{/if}
