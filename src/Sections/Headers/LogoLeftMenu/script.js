jQuery(document).ready(function ($) {
	(function () {
		$('.site-header .iconesearch').click(function () {
			if ($('.site-header ').hasClass('show-search')) {
				$('.site-search ').removeClass('border-bottom');
				$('.site-search ').slideUp(200, function () {
					$('.site-header ').removeClass('show-search');
				});

			} else {
				$('.site-search ').slideDown(300, function () {
					$('.site-search ').addClass('border-bottom');
				});
				$('.site-header ').addClass('show-search');
			}
		});

	})();
});