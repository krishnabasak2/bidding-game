"use strict";

$(document).ready(function () {
	$('#data-table-1').DataTable({
		autoWidth: true,
		ordering: false,
		responsive: false,
		language: {
			search: "",
			searchPlaceholder: "Search"
		}
	});

	$('#data-table-2').DataTable({
		autoWidth: true,
		ordering: false,
		responsive: false,
		language: {
			search: "",
			searchPlaceholder: "Search"
		}
	});

	$('#button').click(function () {
		table.row('.selected').remove().draw(false);
	});
});
