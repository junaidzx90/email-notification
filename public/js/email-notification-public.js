jQuery(function( $ ) {
	'use strict';
	$("#en_agb_check").on("change", function(){
		if($("#en_agb_check").prop("checked")){
			$(".agb_checkbox").css({
				'border-color': '#15c39a',
				'border-top-color': 'transparent',
				'border-left-color': 'transparent'
			});
		}
		if(!$("#en_agb_check").prop("checked")){
			$(".agb_checkbox").css("border", "2px solid #999");
		}
	})
	$("#en_form").on("submit", function(e){
		if(!$("#en_agb_check").prop("checked")){
			e.preventDefault();
			$(".agb_checkbox").css("border-color", "red");
		}
	});
});
