{if count($waitingComs) > 0}
	<form action="{formurl 'news~deleteOrValidateWaitingComs'}" method="post">
	<h2>Commentaires en attente</h2>
	<hr>
	{foreach $waitingComs as $coms}
	<div class="comsToValidate dotBkg" >
		<p>Commentaire de&nbsp;{$coms->pseudo}&nbsp;dans&nbsp;{$coms->title}</p>
		<p>{$coms->text}</p>
		<div class="comsActions">
			<input type="radio" name="coms_{$coms->id}" value="valider" id="delete_{$coms->id}">
			<label for="delete_{$coms->id}">Valider</label>
			<input type="radio" name="coms_{$coms->id}" value="supprimer" id="delete_{$coms->id}">
			<label for="delete_{$coms->id}">Supprimer</label>
		</div>
	</div>
	{/foreach}
		<div class="centerMe">
		<input type="submit" class="buttonLike" value="Envoyer">
		</div>
	</form>
{else}
	<h2>Pas de commentaire en attente</h2> 
{/if}