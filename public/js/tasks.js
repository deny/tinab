$(document).ready(function(){
	
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
	 * Wyświetlenie formularza dodawania taska
	 */
	$('.task-del').click(function(){
		
		if(confirm('Czy na pewno chcesz usunąć to zadanie?'))
		{
			iId = $(this).attr('data-id');
			oFactory = new TaskFactory();
			if(oFactory.del(iId))
			{
				$('input.task[data-id="'+ iId +'"]').parent().detach();
			}
		}

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
			changeTask(oTmp);
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
	
// OPERACJE GRUPOWE
	/**
	 * Grupowa zmiana środowiska
	 */
	$('.env-ch').click(function(){
		
		aIds = getTasks();	
		
		var aEnvData = null;
		if($(this).attr('data-id') != 0)
		{
			aEnvData = {
				'id':	$(this).attr('data-id'),
				'name': $(this).html()
			};
		}
		var oFactory = new TaskFactory();
		
		$.each(aIds, function(iKey){
			
			oTmp = oFactory.getOne(aIds[iKey]);
			oTmp.setEnv(aEnvData == null ? null : new Env(aEnvData));
			oTmp.save();
			
			oTmp = oFactory.save(oTmp);
			
			changeTask(oTmp);
		});
		
		return false;
	});
	
	/**
	 * Grupowa zmiana statusu
	 */
	$('.status-ch').click(function(){
		
		aIds = getTasks();
		
		sStatus = $(this).attr('data-id');
		var oFactory = new TaskFactory();
		
		$.each(aIds, function(iKey){
			
			oTmp = oFactory.getOne(aIds[iKey]);
			oTmp.setStatus(sStatus);
			oTmp.save();
			
			oTmp = oFactory.save(oTmp);
			
			changeTask(oTmp);
		});
		
		return false;
	});
	
	/**
	 * Grupowa zmiana usera
	 */
	$('.user-ch').click(function(){
		
		aIds = getTasks();
		
		if($(this).attr('data-id') == 0)
		{
			oUser = null;
		}
		else
		{
			oUser = new Env({
				'id':	$(this).attr('data-id'),
				'name': $(this).html()
			});
		}
		
		var oFactory = new TaskFactory();
		
		$.each(aIds, function(iKey){
			
			oTmp = oFactory.getOne(aIds[iKey]);
			oTmp.setUser(oUser);
			oTmp.save();
			
			oTmp = oFactory.save(oTmp);
			
			changeTask(oTmp);
		});
		
		return false;
	});
	
	/**
	 * Zwraca id zaznaczonych zadań
	 */
	function getTasks() {
		
		aTasks = $('input.task:checked');
		
		if(aTasks.size() < 1)
		{
			alert('Musisz wybrać zadania');
		}
		
		var aTmp = new Array();
		$.each(aTasks, function(){
			aTmp.push($(this).attr('data-id'));
		});
		
		return aTmp;
	}
	
	/**
	 * Odznacza zaznaczone zadania
	 */
	function clearTasks() {
		$('input.task:checked').checked(false);
	}
	
	
	
});


