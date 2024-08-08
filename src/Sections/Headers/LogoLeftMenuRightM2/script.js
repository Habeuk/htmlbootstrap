jQuery(document).ready(function($)
{
	(function()
	{
		setTimeout(() => {
			var height = $('.site-header-m2').height();
			var body_padding_top = $('body').css('padding-top');
			if($('.site-header-m2').hasClass('is_static')){
				if(!$('.site-header-m2').hasClass('is_absolute')){
					$('header.main.header').css({
						'padding-top' : height + 'px'
					});
				}
				$('.site-header-m2').addClass('fixe-menu').css({'margin-top':body_padding_top});
			}
		}, 600);
	})();
});