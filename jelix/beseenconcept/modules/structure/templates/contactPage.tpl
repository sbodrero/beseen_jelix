<h1 class="indent">Contact</h1>
<blockquote>
	<p>{@string.contactText@}</p>	
</blockquote>
<div id="formZone" class="dotBkg">
	<h2><img class="contactImg" src="{$j_themepath}Images/formulaire.png" alt="Bulle message">Formulaire</h2>
	<hr>
	<p>Remplissez ce questionnaire et je vous contacterais dans les plus brefs délais.</p>
	{zone 'structure~contactUs'}
</div>
<div id="mailZone" class="dotBkg">
	<h2><img class="contactImg" src="{$j_themepath}Images/email.png" alt="Bulle message">Mail</h2>
	<hr>
	<p>Envoyer moi directement un mail.</p>
	<ul>
		<li><a href="mailto:contact@beseenconcept.fr">contact@beseenconcept.fr</a></li>
		<li><a href="mailto:sbodereo@beseenconcept.fr">sbodrero@beseenconcept.fr</a></li>
	</ul>
</div>
<div id="addressZone" class="dotBkg">
	<h2><img class="contactImg" src="{$j_themepath}Images/ecrire.png" alt="Bulle message">Courrier</h2>
	<hr>
	<div itemscope itemtype="http://schema.org/Organisation">
		<p itemprop="name"><b>Be Seen Concept</b></p>
	</div>
	<div itemscope itemtype="http://schema.org/Person">
		<p itemprop="name">Sébastien Bodrero</p>
		<div itemprop="address">
			<div itemscope itemtype="http://schema.org/PostaleAddress">
				<p>11 chemin Labriart C33</p>
				<p ><span itemprop="addressLocality">Pau</span>&nbsp;<span itemprop="addressRegion">Aquitaine</span></p>		
				<p itemprop="addressCountry">France</p>
			</div>
		</div>
	</div>
</div>
<div id="telephoneZone" class="dotBkg">
	<h2><img class="contactImg" src="{$j_themepath}Images/telephone.png" alt="Bulle message">Téléphone</h2>
	<hr>
	<p><a class="listButton" href="tel:+33649676333">0649676333</a></p>
</div>
