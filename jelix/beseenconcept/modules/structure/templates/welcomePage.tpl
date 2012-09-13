{meta_html description "beseenconcept.fr : Développeur web et applicatif sur Pau, Pyrénées-Atlantiques"}
<h1 class="indent">Accueil</h1>
<blockquote>
	<p>{@string.accroche@}</p>	
</blockquote>
{zone 'structure~msgNotices', array('messageTypeRegex'=>'/^msg(Notice|Warning|Error)/')}
<div id="leftSide">
	<h2>Des sites web de qualités !</h2>
	<hr>
	<div class="args">
		<img src="{$j_themepath}Images/guarantee.png" alt="medaille garantie">
		<h3>Sécurisés</h3>
		<p>Toute la puissance des frameworks PHP tels que Jelix, CodeIgniter, Cake.</p>
	</div>
	<div class="args">
		<img src="{$j_themepath}Images/guarantee.png" alt="medaille garantie">
		<h3>Modernes</h3>
		<p>Normes HTML5, nouveautés CSS3, la puissance du framework Jquery.</p>
	</div>
	<div class="args">
		<img src="{$j_themepath}Images/guarantee.png" alt="medaille garantie">
		<h3>Flexibles</h3>
		<p>Web évolutif pour s'adapter à vos exigences et à vos besoins.</p>
	</div>
	<div class="args">
		<img src="{$j_themepath}Images/guarantee.png" alt="medaille garantie">
		<h3>Standards</h3>
		<p>Codes validés par les standards W3C pour un meilleur référencement.</p>
	</div>
</div>
<div id="rightSide">
{zone 'structure~contact'}
</div>
<script type="text/javascript">
{literal}
	$('#jforms_structure_contact_name').attr('placeholder','Votre nom');
	$('#jforms_structure_contact_email').attr('placeholder','Votre adresse mail');
	$('#jforms_structure_contact_message').attr('placeholder','Votre message');
	$('#jforms_structure_contact_name, #jforms_structure_contact_email, #jforms_structure_contact_message').bind('click focus', function() {
		$(this).attr('placeholder','');
	});
{/literal}
</script>