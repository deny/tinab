$(document).ready(function(){

//	oTask = new Task();
//	oTask.setTask('nowy task');
//	oTask.setEnv('nowe env');
//			
//	console.log(oTask.getTask());
//	oTask.save();
//	console.log(oTask.getTask());
//	oTask.setTask('lalala');
//	console.log(oTask.getTask());
//	oTask.revert();
//	console.log(oTask.getTask());
//	
//	Task.load(1);
	
//	oTask = TaskFactory.getNew().getOne(4);
//	console.log(oTask.getTask());
	
	/**
	 * Wyświetlenie formularza dodawania taska
	 */
	$('.task-add').click(function(){
		$('#task').val('');
		$('#users').val('');
		$('#labels').val('');
		$('.task-add-form').show(500);
		$('.task-add-form .error-inline').detach();
		return false;
	});
	
	/**
	 * Anulowanie dodawania taska
	 */
	$('.task-close').click(function(){
		$('.task-add-form').hide(500);
		return false;
	});
	
	/**
	 * Wysłanie formularza z taskiem
	 */
	$('.task-add-form').submit(function(){
		$('.task-add-form .error-inline').detach();
		data = {
			task: 	$('#task').val(),
			labels:	$('#labels').val(),
			users: $('#users').val()
		};
		
		oFactory = new TaskFactory();
		oTmp = oFactory.create(data);
		if(oTmp != null) // dane poprawne
		{
			oList = $('.task-list.new');
			
			sLi = '<li data-id="'+ oTmp.getId() +'">';
			if(oTmp.getEnv() != null)
			{
				sLi += ' <span class="task-env">'+ oTmp.getEnv().getName() +'</span> ';
			}
			
			aLabels = oTmp.getLabels();
			$.each(aLabels, function(iKey) {
				sLi +=  ' <span class="task-label">'+ aLabels[iKey] +'</span> ';
			});
			
			sLi += ' <span class="task">'+ oTmp.getTask() +'</span> ';
			
			if(oTmp.getUser() != null)
			{
				sLi += ' <span class="task-user">'+ oTmp.getUser().getName() +'</span> ';
			}
			
			sLi += ' <a class="task-edit" href="#">Edytuj</a> ';
			sLi += '</li>';
			
			oList.append(sLi);
			
			// @todo dodanie taska do listy
			$('.task-add-form').hide(500);
		}
		else // pokazanie błędów
		{
			showTaskErrors(oFactory.getMessages());
		}
		
		return false;
	});
	
	function showTaskErrors(aMsg)
	{
		aFields = ['task', 'users', 'labels'];
		
		$.each(aFields, function(iKey){
		
			sField = aFields[iKey];
			if(aMsg[sField])
			{
				sMsg = '';
				$.each(aMsg[sField], function(i) {
					sMsg += aMsg[sField][i] + '<br />'; 
				});
				
				console.log($('#'+ sField));
				$('#'+ sField).after('<div class="error-inline" style="display: block;">'+ sMsg + '</div>');
			}
			
		});
	}
	
	
	
});


