{if $identite.type == 'eleve'}
<h2>Journal de classe de {$identite.prenom} {$identite.nom}: {$identite.groupe}</h2>
{else}
<h2>Journal de classe de {$identite.prenomEl} {$identite.nomEl}: {$identite.groupe}</h2>
{/if}

<h3></h3>

<div class="row">

	<div class="col-md-7 col-xs-12">
		<div id="calendar"></div>
	</div>
	<div class="col-md-5 col-xs-12">
		<div class="encadre">
			<form action="index.php" method="POST" name="detailsJour" id="detailsJour" role="form" class="form-vertical">
				<input type="hidden" name="ladate" id="ladate" class="ladate" value="">
				<input type="hidden" name="view" id="view" value="">
				<div id="unTravail">
					<strong>Sélectionner un événement dans le calendrier</strong>
				</div>
			</form>
		</div>
	</div>

</div>
<!-- row -->

<div class="row">

	{foreach from=$legendeCouleurs key=cat item=travail}
	<div class="col-md-1 col-sm-6">
		<div class="cat_{$cat} discret" title="{$travail.categorie}">{$travail.categorie|truncate:10}</div>
	</div>
	<!-- col-md-... -->
	{/foreach}

</div>
<!-- row -->


<script type="text/javascript">
	$(document).ready(function() {

		$("#calendar").fullCalendar({
			events: {
				url: 'inc/events.json.php'
			},
			eventLimit: 2,
			header: {
				left: 'prev, today, next',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			eventClick: function(calEvent, jsEvent, view) {
				var id = calEvent.id; // l'id de l'événement
				$.post('inc/getTravail.inc.php', {
						event_id: id
					},
					function(resultat) {
						$("#unTravail").fadeOut(400, function() {
							$("#unTravail").html(resultat);
						});
						$("#unTravail").fadeIn();
						// $("#unTravail").html(resultat)
					}
				)
			},
			defaultTimedEventDuration: '00:50',
			businessHours: {
				start: '08:15',
				end: '16:25',
				dow: [1, 2, 3, 4, 5]
			},
			minTime: "08:00:00",
			maxTime: "18:00:00",
			firstDay: 1,
			dayClick: function(date, event, view) {
				$('#calendar').fullCalendar('gotoDate', date);
				$('#calendar').fullCalendar('changeView', 'agendaDay');
			}

		});

	})
</script>
