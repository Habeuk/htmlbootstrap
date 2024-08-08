jQuery(document).ready(function ($) {
	(function () {

		$('#pricelists-model1').on('show.bs.modal', function (e) {
			var title = $(e.relatedTarget).data('title-small');
			if (title != '') {
				title += ' : ';
			}
			title += $(e.relatedTarget).data('title');
			$('.modal-title', this).text(title);
			$('.pack_selectionner', this).val(title);
		});


	})();
});