

/**
 * Klasa środowiska
 */
Env.prototype = new Lib_Model();
function Env(data) 
{	
// WYWOŁANIE KONSTUKTORA KLASY BAZOWEJ
	this.construct('env', {	
		
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
	 * Zwraca id środowiska
	 * 
	 * @var	int
	 */
	this.getId = function() {
		return this.get('id');
	};
	
	/**
	 * Zwraca nazwę środowiska
	 * 
	 * @var	string
	 */
	this.getName = function() {
		return this.get('name');
	};
}

// STATYCZNE
	