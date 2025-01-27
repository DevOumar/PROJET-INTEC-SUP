$(function () {
	$("#reportrange").hide();
	moment.locale("fr");
	let defaultStartDate = $("#start_date").val().length == 10 ? $("#start_date").val() : moment().startOf('year');
	let defaultEndDate = $("#end_date").val().length == 10 ? $("#end_date").val() : moment().endOf('year');
	var start = moment(defaultStartDate);
	var end = moment(defaultEndDate);
	end.locale("fr");


	function cb(start, end) {
		$('#reportrange span').html(start.format('DD MMM, YYYY') + ' - ' + end.format('DD MMM, YYYY'));
	}

	$('#reportrange').daterangepicker({
		"locale": {
			"format": "DD/MM/YYYY",
			"separator": " - ",
			"applyLabel": "Appliquer",
			"cancelLabel": "Annuler",
			"fromLabel": "De",
			"toLabel": "A",
			"customRangeLabel": "Personnaliser",
			"weekLabel": "S",
			"daysOfWeek": [
			"Di",
			"Lu",
			"Ma",
			"Me",
			"Je",
			"Ve",
			"Sa"
			],
			"monthNames": [
			"Janvier",
			"Février",
			"Mars",
			"Avri",
			"Mai",
			"Juin",
			"Juillet",
			"Août",
			"Septembre",
			"Octobre",
			"Novembre",
			"Décembre"
			],
			"firstDay": 1
		},
		startDate: start,
		endDate: end,
		ranges: {
			'Aujourd\'hui': [
			moment(), moment()
			],
			'Hier': [
			moment().subtract(1, 'days'),
			moment().subtract(1, 'days')
			],
			'Il y\'a 7 jours': [
			moment().subtract(6, 'days'),
			moment()
			],
			'Il y\'a 1 mois': [
			moment().subtract(29, 'days'),
			moment()
			],
			'Ce mois': [
			moment().startOf('month'), moment().endOf('month')
			],
			'Le mois passé': [
			moment().subtract(1, 'month').startOf('month'),
			moment().subtract(1, 'month').endOf('month')
			]
		}
	}, cb);

	cb(start, end);

});

$('#reportrange-toggler').click((e) => {
	let displayState = $("#reportrange").css('display');
	if (displayState == 'none') {
		$("#reportrange").show(200)
	} else {
		$("#reportrange").hide(200)
	}
});

$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
	let startDate = moment(picker.startDate._d).format("YYYY-MM-DD");
	let endDate = moment(picker.endDate._d).format("YYYY-MM-DD")
	$("#start_date").val(startDate);
	$("#end_date").val(endDate);
	$("#date_filter_chosen_label").val(picker.chosenLabel);
	$("#date-filter").trigger("submit");
});
$('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
	let startDate = "";
	let endDate = "";
	$("#start_date").val(startDate);
	$("#end_date").val(endDate);
	$("#date_filter_chosen_label").val("");
	$("#date-filter").trigger("submit");
});