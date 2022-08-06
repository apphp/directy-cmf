// -------------------------------------------------------
// Executes when HTML-Document is loaded and DOM is ready
// -------------------------------------------------------
$(document).ready(function(){

    // ASSIGN ACTION TO ALL CLOSE BUTTONS
    $('.alert .close').click(function(){
        $(this).parent().hide();
    });
	
});