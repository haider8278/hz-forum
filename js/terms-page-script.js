/**
 * All timelines page script
 */
( function( $ ) {

	$( document ).ready( function() {
		$("#col-container").prepend('<div class="tax-action-buttons"><a href="javascript:;" class="button button-primary addtax">Add New Timeline</a></div>');
		$("p.submit").append('<a href="javascript:;" class="button button-primary removetax">Cancel</a>');
		$(document).on("click",".addtax",function(){
			$(this).hide();
			$("#col-left").slideDown();
		});
		$(document).on("click",".removetax",function(){
			$(".addtax").show();
			$("#col-left").slideUp();
		});
		
		new Clipboard('.copybtn');
		
	} );

}( jQuery ) );
