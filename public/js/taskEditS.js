/**
 * Zwraca kod HTML dla taska
 * 
 * @param	Task	oTmp	obiekt taska
 * @return	string
 */
function taskToHtml(oTmp) {
	sLi = '<li data-id="'+ oTmp.getId() +'">';
	
	sLi += '<input type="checkbox" class="task" data-id="'+ oTmp.getId() +'" />';
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
	
	sLi += ' <a class="task-edit" href="/project/tasks/edit/id/'+ Lib_Factory.iProjectId +'/task/'+ oTmp.getId() +'"href="#">Edytuj</a> ';
	sLi += ' <a class="task-del" href="#" data-id="'+ oTmp.getId() +'">Usuń</a> ';
	sLi += '</li>';
	
	return sLi;
};

/**
 * Zamiana taska
 * 
 * @param	Task	oTask	obiekt taska
 * @return	void
 */
function changeTask(oTask) {
	
	if(oTask == null)
	{
		return '';
	}
	
	$('input.task[data-id="'+ oTask.getId() +'"]').parent().detach();
	
	oList = $('.task-list.'+ oTask.getStatus());
	sLi = taskToHtml(oTask);
	oList.append(sLi);
};

