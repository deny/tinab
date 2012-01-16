

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
		data.env = (data.env == null ? null : new User(data.env));
		return new Task(data);
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

	