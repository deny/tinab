

/**
 * Klasa zadania
 */
TaskFactory.prototype = new Lib_Factory();
function TaskFactory() 
{	
// WYWOŁANIE KONSTUKTORA KLASY BAZOWEJ
	this.construct('task');
	
	/**
	 * Zwraca obiekt taska
	 * 
	 * @return	Task
	 */
	this.createObject = function(data) {
		data.user = (data.user == null ? null : new User(data.user));
		data.env = (data.env == null ? null : new Env(data.env));
		return new Task(data);
	};
	
	/**
	 * Zapisuje obiekt
	 * 
	 * @return	Task
	 */
	this.save = function(oTmp) {
		aRes = this.call('save', {
			itemid: oTmp.getId(),
			task: oTmp.getTask(),
			env: oTmp.getEnv() == null ? '' : oTmp.getEnv().getId(),
			status: oTmp.getStatus(),
			labels: oTmp.getLabels().join(', '),
			users: oTmp.getUser() == null ? '' : oTmp.getUser().getId()
		});
		
		if(aRes.success)
		{
			return this.createObject(aRes.object);
		}
		
		return null;
	};
	
	/**
	 * Zapisuje usuwa obiekt
	 * 
	 * @return	bool
	 */
	this.del = function(iId) {
		aRes = this.call('delete', {
			itemid: iId
		});
		
		return aRes.success;
	};
}

//STATYCZNE

	/**
	 * Zwraca instancję obiektu
	 * 
	 * @return	TaskFactory
	 */
	TaskFactory.getNew = function() {
		return new TaskFactory();
	};

	