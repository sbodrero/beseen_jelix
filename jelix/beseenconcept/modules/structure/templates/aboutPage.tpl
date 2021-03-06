<h1 class="indent">A propos</h1>
<blockquote>
<p>{@string.aboutText@}</p>	
</blockquote>
<div id="aboutPage">
	<div class="dotBkg bottom">
		<h2>Mentions légales<img src="{$j_themepath}Images/tip.png" alt="Bulle message"></h2>
		<hr>
		<p>Vous êtes sur le site <b>beseenconcept.fr</b><br>Nom de domaine possédé et représenté par Sébastien Bodrero.</p>
		<p>Ce site et l'ensemble des informations présentées sont soumis à la règlementation française en vigueur.</p>
		<p>Contenu du site non contractuel susceptible d'être enrichi, modifié et supprimé.</p>
		<p>J'exécute mes missions en freelance en tant que salarié porté (contrat tripartite), en CDD ou CDI au sein de votre structure et/ou en télétravail.</p>
		<p>N'hésitez pas à me poser des questions à ce sujet</p>
	</div>
	<div class="dotBkg bottom">
		<h2>Crédits<img src="{$j_themepath}Images/tip.png" alt="Bulle message"></h2>
		<hr>
		<h3>Police</h3>
		<ul>
			<li>'Tall Dark And Handsome' &copy;QuickStick Productions, 2005 <a href="mailto:mail@quickstick.com.au" title="email quickstick">mail@quickstick.com.au</a></li>
		</ul>
		 
		<h3>Photographies</h3>
		<ul>
			<li>Background pattern 'Furley' &copy;<a href="http://www.subtlepatterns.com" target="_blank" title="Url subtlepatterns">www.subtlepatterns.com</a></li>
		</ul>

		<h3>Infographies</h3>
		<ul>
			<li>Speech Bubble Icon &copy;<a href="http://graphicsfuel.com" target="_blank" title="Url graphicsfuel">graphicsfuel.com</a></li>
			<li>Satisfaction_Guaranteed_Badges &copy;<a href="http://freepsdfiles.net" target="_blank" title="Url freepsdfiles">freepsdfiles.net</a></li>
			<li>Vintage Web Badge &copy;<a href="http://psdblast.com" target="_blank" title="Url psdblast">psdblast.com</a></li>
		</ul>
	</div>
	<div class="dotBkg bottom">
		<h2>À propos du concepteur<img src="{$j_themepath}Images/tip.png" alt="Bulle message"></h2>
		<hr>
		<div class="floatLeft">
		<h3>Développeur web</h3>
		<ul>
			<li><a href="{jurl 'structure~cvDownload'}" title="Télécharger mon cv"><img class="alignMiddleAndMargin" src="{$j_themepath}Images/pdf.png" alt="Logo pdf">CV</a></li>
		</ul>		
		</div>
		<div class="floatLeft">
		<h3>Support technique</h3>
		<ul>
			<li><img src="{$j_themepath}Images/MCDST.png" alt="Certification Microsoft Support technique"></li>
		</ul>		
		</div>
		<div class="floatLeft">
		<h3>Administrateur système Windows</h3>
		<ul>
			<li><img src="{$j_themepath}Images/MCSA.png" alt="Certification Microsoft Administrateur"></li>	
		</ul>		
		</div>
		<div class="floatLeft">
		<h3>Expert bureautique</h3>
		<ul>
			<li><img src="{$j_themepath}Images/MOS.png" alt="Certification bureautique Microsoft Office"></li>
		</ul>		
		</div>
		<div class="floatLeft">
		<h3>Formateur</h3>
		<ul>
			<li><a href="https://www.mcpvirtualbusinesscard.com/VBCServer/beseenconcept.fr/GetTranscript.aspx?id=c43d8cd5-101a-4181-a8f4-77c107eced4f" title="Lien transcript" data-attr="{jurl 'structure~udpatePdfCount', array('name' => 'Transcript')}"><img class="alignMiddleAndMargin" src="{$j_themepath}Images/pdf.png" alt="Logo pdf">MCP Transcript</a></li>
		</ul>		
		</div>
		<div id="virtualCard">
		<h3>Carte virtuelle</h3>
		<ul>
			<li><iframe src="https://www.mcpvirtualbusinesscard.com/VBCServer/beseenconcept.fr/interactivecard"></iframe></li>
		</ul>		
		</div>
	</div>
</div>
<!-- To count transcript downloaded -->
<script type="text/javascript">
{literal}
	$('.floatLeft:eq(4) a').click(function(){
		var url = $(this).attr('data-attr');
		$(location).attr('href',url);
	})
{/literal}
</script>


