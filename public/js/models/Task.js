

/**
 * Klasa zadania
 */
Task.prototype = new Lib_Model();
function Task(data) 
{	
// WYWOŁANIE KONSTUKTORA KLASY BAZOWEJ
	this.construct('task', {	
		/**
		 * Id zadania
		 * 
		 * @var	int
		 */
		id: data.id,
		
		/**
		 * Treść zadania
		 * 
		 * @var	string
		 */
		task: data.task,
		
		/**
		 * Środowisko
		 * 
		 * @var	string
		 */
		env: data.env,
		
		/**
		 * Status zadania
		 * 
		 * @var	string
		 */
		status: data.status,
		
		/**
		 * Etykiety
		 * 
		 * @var	array
		 */
		labels: data.labels,
		
		/**
		 * Osoba odpowiedzialna
		 * 
		 * @var	User
		 */
		user: data.user
	});
	
// GETTERY
	
	/**
	 * Zwraca id zadania
	 * 
	 * @var	int
	 */
	this.getId = function() {
		return this.get('id');
	};
	
	/**
	 * Zwraca treść zadania
	 * 
	 * @var	string
	 */
	this.getTask = function() {
		return this.get('task');
	};
	
	/**
	 * Zwraca środowisko
	 * 
	 * @var	string
	 */
	this.getEnv = function() {
		return this.get('env');
	};
	
	/**
	 * Zwraca status zadania
	 * 
	 * @var	string
	 */
	this.getStatus = function() {
		return this.get('status');
	};
	
	/**
	 * Zwraca etykiety
	 * 
	 * @var	array
	 */
	this.getLabels = function() {
		return this.get('labels');
	};
	
	/**
	 * Zwraca usera
	 * 
	 * @var	User
	 */
	this.getUser = function() {
		return this.get('user');
	};
	
// SETTERY
	
	/**
	 * Ustawia treść zadania
	 * 
	 * @param	string	sTask	nowa treść zadania	
	 * @return	void
	 */
	this.setTask = function(sTask) {
		return this.set('task', sTask);
	};
	
	/**
	 * Ustawia środowisko
	 * 
	 * @param	string	sEnv	nowe środowisko	
	 * @return	void
	 */
	this.setEnv = function(sEnv) {
		return this.set('env', sEnv);
	};
	
	/**
	 * Ustawia nowy status
	 * 
	 * @param	string	sStatus	nowy status	
	 * @return	void
	 */
	this.setStatus = function(sStatus) {
		return this.set('status', sStatus);
	};
	
	/**
	 * Ustawia nowego usera odpowiedzialnego za zadanie
	 * 
	 * @param	User	oUser	nowy obiekt usera
	 * @return	void
	 */
	this.setUser = function(oUser) {
		return this.set('user', oUser);
	};	
}

// STATYCZNE
	