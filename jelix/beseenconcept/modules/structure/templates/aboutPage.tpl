<div id="about">
	<div class="dotBkg bottom">
		<h1 class="indent">A propos</h1>
		<h2>Mentions légales<img src="{$j_themepath}Images/tip.png" alt="Bulle message"></h2>
		<hr>
		<p>Vous êtes sur le site <b>beseenconcept.fr</b><br>Nom de domaine possèdé et représenté par Sébastien Bodrero.</p>
		<p>Ce site et l'ensemble des informations présentées sont soumis à la règlementation française en vigueur.</p>
		<p>Contenu du site non contractuel suceptible d'être enrichi, modifié et supprimé.</p>
		<p>J'exécute mes missions en freelance en tant que salarié portée (contrat tripartite), en cdd ou cdi au sein de votre structure et/ou en télétravail.<br>
			N'hesitez pas à me poser des questions à ce sujet</p>
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
			<li>Background pattern 'Furley' &copy;<a href="http://www.subtlepatterns.com" target="_blanck" title="Url subtlepatterns">www.subtlepatterns.com</a></li>
		</ul>

		<h3>Infographies</h3>
		<ul>
			<li>Speech Bubble Icon &copy;<a href="http://graphicsfuel.com" target="_blanck" title="Url graphicsfuel">graphicsfuel.com</a></li>
			<li>Satisfaction_Guaranteed_Badges &copy;<a href="http://freepsdfiles.net" target="_blanck" title="Url freepsdfiles">freepsdfiles.net</a></li>
			<li>Vintage Web Badge &copy;<a href="http://psdblast.com" target="_blanck" title="Url psdblast">psdblast.com</a></li>
		</ul>
	</div>
	<div class="dotBkg bottom">
		<h2>A propos du concepteur<img src="{$j_themepath}Images/tip.png" alt="Bulle message"></h2>
		<hr>
		<div class="floatLeft">
		<h3>Développeur web</h3>
		<ul>
			<li><a href="{jUrl 'structure~cvDownload'}" title="Télécharger mon cv"><img class="alignMiddleAndMargin" src="{$j_themepath}Images/pdf.png" alt="Logo pdf">CV</a></li>
		</ul>		
		</div>
		<div class="floatLeft">
		<h3>Support technique</h3>
		<ul>
			<li><img src="{$j_themepath}Images/MCDST.png" alt="Certification Microsoft Support technique"></li>
		</ul>		
		</div>
		<div class="floatLeft">
		<h3>Administrateur system Windows</h3>
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
			<li><a href="https://www.mcpvirtualbusinesscard.com/VBCServer/beseenconcept.fr/GetTranscript.aspx?id=c43d8cd5-101a-4181-a8f4-77c107eced4f" alt="Lien transcript" data-attr="{jUrl 'structure~udpatePdfCount', array('name' => 'Transcript')}"><img class="alignMiddleAndMargin" src="{$j_themepath}Images/pdf.png" alt="Logo pdf">MCP Transcript</a></li>
		</ul>		
		</div>
		<div id="virtualCard">
		<h3>Carte virtuelle</h3>
		<ul>
			<li><iframe frameborder="0" scrolling="no" width="392px" height="177px" src="https://www.mcpvirtualbusinesscard.com/VBCServer/beseenconcept.fr/interactivecard"></iframe></li>
		</ul>		
		</div>
	</div>
</div>

<script type="text/javascript">
{literal}
	$('.floatLeft:eq(4) a').click(function(){
		var url = $(this).attr('data-attr');
		$(location).attr('href',url);
	})
{/literal}
</script>


