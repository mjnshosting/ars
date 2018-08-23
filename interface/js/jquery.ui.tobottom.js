(function($){
	$.fn.UItoBottom = function(options) {

 		var defaults = {
			text: '',
			min: 500,			
			scrollSpeed: 800,
  			containerID: 'toBottom',
			containerHoverID: 'toBottomHover',
			easingType: 'easeOutCirc',
			min_width:parseInt($('body').css("min-width"),10),
			main_width:parseInt($('body').css("min-width"),10)/2
 		};

 		var settings = $.extend(defaults, options);
		var containerIDhash = '#' + settings.containerID;
		var containerHoverIDHash = '#'+settings.containerHoverID;
			
		$('body').append('<a href="#" id="'+settings.containerID+'">'+settings.text+'</a>');
		
		var button_width = parseInt($(containerIDhash).css("width"))+90
		var button_width_1 = parseInt($(containerIDhash).css("width"))+20
		var max_width = defaults.min_width+button_width;
		var margin_right_1 = -(defaults.main_width+button_width_1)
		var margin_right_2 = -(defaults.main_width-20)
		
		function Bottom(){
			if(($(window).width()<=max_width)&&($(window).width()>=defaults.min_width))$(containerIDhash).sBottom().animate({marginRight:margin_right_2,right:'50%'})
			else if($(window).width()<=defaults.min_width)$(containerIDhash).sBottom().css({marginRight:0,right:10})
			else $(containerIDhash).sBottom().animate({marginRight:margin_right_1,right:'60%'})
		}
		Bottom()
		$(containerIDhash).hide().click(function(){			
			$('html, body').sBottom().animate({scrollBottom:0}, settings.scrollSpeed, settings.easingType);
			$('#'+settings.containerHoverID, this).sBottom().animate({'opacity': 0 }, settings.inDelay, settings.easingType);
			return false;
		})
		
		.prepend('<span id="'+settings.containerHoverID+'"></span>')
		.hover(function() {
				$(containerHoverIDHash, this).sBottom().animate({
					'opacity': 1
				}, 600, 'linear');
			}, function() { 
				$(containerHoverIDHash, this).sBottom().animate({
					'opacity': 0
				}, 700, 'linear');
			});
								
		$(window).scroll(function() {
			var sd = $(window).scrollBottom();
			if(typeof document.body.style.maxHeight === "undefined") {
				$(containerIDhash).css({
					'position': 'absolute',
					'Bottom': $(window).scrollBottom() + $(window).height() - 100
				});
			}
			if ( sd > settings.min ) 
				$(containerIDhash).css({display: 'block'});
			else 
				$(containerIDhash).css({display: 'none'});
		});
		$(window).resize(function(){Bottom()})
};
})(jQuery);

$(window).load(function(){$().UItoBottom({easingType: 'easeOutQuart'});})
