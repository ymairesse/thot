<ul class="nav nav-pills">
  <li class="active"><a data-toggle="tab" href="#calendrier"><i class="fa fa-calendar text-danger"></i> JDC calendrier</a></li>
  <li><a data-toggle="tab" href="#journalier"><i class="fa fa-calendar-check-o text-success"></i> JDC journalier (expérimental)</a></li>
</ul>

<div class="tab-content">

	<div class="row tab-pane fade in active" id="calendrier">

		<div class="col-md-7 col-xs-12">

			{if $identite.type == 'eleve'}
			<h3>Journal de classe de {$identite.prenom} {$identite.nom}: {$identite.groupe}</h3>
			{else}
			<h3>Journal de classe de {$identite.prenomEl} {$identite.nomEl}: {$identite.groupe}</h3>
			{/if}

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

	<div class="row tab-pane fade" id="journalier">

		<div class="col-xs-12 selecteur">

            {include file="jdc/selectDatesCoursClasse.tpl"}

        </div>

		<div class="col-xs-12" id="jdcJournalier"  style="height: 35em; overflow: auto">

		</div>

    </div>

	</div>
	<!-- tab-content -->

</div>

<div class="row">

	{foreach from=$legendeCouleurs key=cat item=travail}
	<div class="col-md-1 col-sm-6">
		<div class="cat_{$cat} discret" title="{$travail.categorie}">{$travail.categorie|truncate:10}</div>
	</div>
	<!-- col-md-... -->
	{/foreach}

</div>
<!-- row -->

{include file="jdc/modal/modalDislike.tpl"}

{include file="jdc/modal/modalInfoLikes.tpl"}

{include file="jdc/modal/modalViewJDC.tpl"}


<script type="text/javascript">

	$(document).ready(function() {

        var formulaire = $('#selectDatesCours').serialize();
		$.post('inc/jdc/jdcJournalier.inc.php', {
			formulaire: formulaire
		}, function(resultat){
			$('#jdcJournalier').html(resultat);
		})

        $('#dateStart, #dateEnd, #coursGrpClasse, #categories').change(function(){
            var formulaire = $('#selectDatesCours').serialize();
            $.post('inc/jdc/jdcJournalier.inc.php', {
                formulaire: formulaire
            }, function(resultat){
                $('#jdcJournalier').html(resultat);
            })
        })

        $('body').on('click', '.btn-show', function(){
            var id = $(this).closest('tr').data('id');
            alert(id);
        })

		$('.datepicker').datepicker({
            format: "dd/mm/yyyy",
            clearBtn: true,
            language: "fr",
            calendarWeeks: true,
            autoclose: true,
            todayHighlight: true
            });

		$('#viewJDC').click(function(){
			$.post('inc/jdc/listeCoursClasseEleve.inc.php', {
			}, function(resultat){
				$('#listeCours').html(resultat);
			})
			$('#modalPrintJDC').modal('show');
		})

		$('#btnModalViewJDC').click(function(){
			var formulaire = $('#printForm').serialize();
			$.post('inc/jdc/viewJDC.inc.php', {
				formulaire: formulaire
			}, function(resultat){
				bootbox.alert(resultat);
			})
		})

		$('#unTravail').on('click', '#like', function(){
			var id = $(this).data('id');
			$.post('inc/jdc/saveLikes.inc.php', {
				mode: 'like',
				id: id,
				commentaire: ''
			}, function(resultat){
				comptes = JSON.parse(resultat);
				$('#like').find('.badge').text(comptes.like);
				$('#dislike').find('.badge').text(comptes.dislike);
			})
		})

		$('#unTravail').on('click', '#dislike', function(){
			var id = $(this).data('id');
			$('#confirmDislike').data('id', id);
			$.post('inc/jdc/retreiveDislike.inc.php', {
				id: id
			}, function(resultat){
				$('#commentaire').val(resultat);
			})
			$('#modalDislike').modal('show');
		})

		$('#confirmDislike').click(function(){
			var id = $(this).data('id');
			var commentaire = $('#commentaire').val();
			$.post('inc/jdc/saveLikes.inc.php', {
				mode: 'dislike',
				id: id,
				commentaire: commentaire
			}, function(resultat){
				comptes = JSON.parse(resultat);
				$('#like').find('.badge').text(comptes.like);
				$('#dislike').find('.badge').text(comptes.dislike);
				$('#modalDislike').modal('hide');
			})
		})

		$('#unTravail').on('click', '#info', function(){
			var id = $(this).data('id');
			$.post('inc/jdc/dislikesList.inc.php', {
				id: id
			}, function(resultat){
				$('#dislikes').html(resultat);
				$('#modalInfo').modal('show');
			})
		})

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
				$.post('inc/jdc/getTravail.inc.php', {
						id: id,
						origine: 'jdc'
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
			maxTime: "22:00:00",
			firstDay: 1,
			dayClick: function(date, event, view) {
				$('#calendar').fullCalendar('gotoDate', date);
				$('#calendar').fullCalendar('changeView', 'agendaDay');
			}
		});

	})
</script>
