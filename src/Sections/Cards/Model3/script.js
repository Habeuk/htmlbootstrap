jQuery(document).ready(function ($) {
	(function () {

		$('#card-cardsmodel3').on('show.bs.modal', function (e) {
			var title = '';
			title += $(e.relatedTarget).data('title');
			console.log(title);
			$('.modal-title', this).text(title);
		});


	})();
});