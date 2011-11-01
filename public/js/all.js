$(document).ready(function(){
	
	// potwierdzenie akcji
	$('a.confirm').live('click', function(){
		
		if(confirm($(this).attr('data-confirm')))
		{
			$(this).attr('href', $(this).attr('href').substring(1));
			return true;
		}
		
		return false;
	});
	
});