

/**
 * Klasa usera
 */
User.prototype = new Lib_Model();
function User(data) 
{	
// WYWOŁANIE KONSTUKTORA KLASY BAZOWEJ
	this.construct('user', {	
		
		/**
		 * Id usera
		 * 
		 * @var	int
		 */
		id: data.id,
		
		/**
		 * Nazwa usera
		 * 
		 * @var	string
		 */
		name: data.name
	});
	
// GETTERY
	
	/**
	 * Zwraca id usera
	 * 
	 * @var	int
	 */
	this.getId = function() {
		return this.get('id');
	};
	
	/**
	 * Zwraca nazwę usera
	 * 
	 * @var	string
	 */
	this.getName = function() {
		return this.get('name');
	};
}

// STATYCZNE
	