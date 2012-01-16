
/**
 * Podstawa modelu danych
 */
function Lib_Model() {
	
// DANE PRYWATNE
	var modelData = {
			
		/**
		 * Nazwa modelu danych
		 * 
		 * @var	string
		 */
		sModel:	null,
		
		/**
		 * Treśc komunikatów walidacyjnych
		 * 
		 * @var	array
		 */
		aMessages: null,
		
		/**
		 * Dane modelu
		 * 
		 * @var	object
		 */
		aData: null,
		
		/**
		 * Kopia danych (do przywrócenia)
		 * 
		 * @var	object
		 */
		aOldData: null
	};
	
// GETTERY
	
	/**
	 * Ustawia wartośc pola
	 * 
	 * @return	mixed
	 */
	this.get = function(sField) {
		return modelData.aData[sField];
	};
	
// SETTERY
	
	/**
	 * Ustawia wartośc pola
	 * 
	 * @param	string	sField	nazwa pola
	 * @param	mixed	mValue	nowa wartość
	 * @return	void
	 */
	this.set = function(sField, mValue) {
		modelData.aData[sField] = mValue;
	};
	
// INNE
	
	/**
	 * Konstuktor modelu
	 * 
	 * @param	strign	model	nazwa modelu
	 * @param	array	data	dane modelu
	 * @return	void	
	 */
	this.construct = function(model, data) {
		modelData.sModel = model;
		modelData.aData = data;
		modelData.aOldData = cloneObj(data);
	};
	
	/**
	 * Zapisuje dane modelu
	 * 
	 * @return	void
	 */
	this.save = function() {
		
		if(validate())
		{
			modelData.aOldData =  cloneObj(modelData.aData);
			return true;
		}
		
		return false;
	};
	
	/**
	 * Usuwa obiekt
	 * 
	 * @return	void
	 */
	this.del = function() {
		return false;
	};
	
	/**
	 * Przywraca dane sprzed edycji
	 * 
	 * @return	void
	 */
	this.revert = function() {
		modelData.aData =  cloneObj(modelData.aOldData);
	};
	
// PRYWATNE
	
	/**
	 * Walidacaj danych
	 * 
	 * @returns	bool
	 */
	function validate() {
		console.log(modelData.aData);
	};
	
	/**
	 * Klonuje obiekty
	 * 
	 * @param 	object	obj	klonoany obiekt
	 * @return	object
	 */
	function cloneObj(obj) {
		var clone = {};

	    for (var i in obj) {
	        if (typeof obj[i] == 'object') {
	            clone.i = cloneObj(obj[i]);
	        } else {
	            clone.i = obj[i];
	        }
	    }

	    return clone;
	};
	
};