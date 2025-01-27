/*Dashboard Init*/
 
"use strict"; 
$(document).ready(function() {
	/*Mon Toaster Alert*/
	let loggedUser = $('span#logged-user').data("name")
	$.toast({
		heading: `Bienvenue ${loggedUser} !` ,
		text: `<p>Merci de nous avoir fait confiance  !</p>` ,
		position: `top-right`,
		loaderBg:`#07ff03`,
		class: `jq-toast-primary`,
		hideAfter: 3200, 
		stack: 6,
		showHideTransition: `hide`
	});	
});
