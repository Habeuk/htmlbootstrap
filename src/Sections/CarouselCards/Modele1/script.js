jQuery(document).ready(function ($) {
	(function () {
		$('.owl-carousel').each(function (i, v) {
			var self = this;
			var params = $(self).data('carouselcardsmodele1');
			//console.log('OWL responsive : ', params);
			if (params) {
				params['navText'] = [
					'<i class="fa fa-angle-left" aria-hidden="true"></i>',
					'<i class="fa fa-angle-right" aria-hidden="true"></i>'
				];
				/***
				 * after the plugin has initialized.
				 */
				$(self).on('initialized.owl.carousel', function (event) {
					//console.log('initialized.owl.carousel');

					setTimeout(function () {
						$('.owl-dots', self).addClass('show');
						$('.owl-dots > div', self).each(function (k, value) {
							var src = $(this).html();
							src = src.replace(/(\r\n|\n|\r)/gm, "");
							$(this).html('<img src="' + src + '" class=""img-fluid>')
						});
						$('.owl-dots > div', self).click(function () {
							var owl_index = $('.owl-dots > div', self).index(this);
							$(self).trigger('to.owl.carousel', owl_index);
						});

					}, 200);
				});
				$(self).owlCarousel(params);
				$('.carouselcards-modele1 .header .owl-next').click(function () {
					$(self).trigger('next.owl.carousel');
				});
				$('.carouselcards-modele1 .header .owl-prev').click(function () {
					$(self).trigger('prev.owl.carousel');
				});
			}
		});

	})();
});