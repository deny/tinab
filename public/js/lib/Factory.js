
/**
 * Podstawa fabryki obiektów
 */
function Lib_Factory() {
	
// DANE PRYWATNE
	var modelData = {
			
		/**
		 * Nazwa modelu danych
		 * 
		 * @var	string
		 */
		sModel:	null,
		
		aMsg: null
	};
	

// FABRYCZNE
	
	
	
	this.create = function(data) {
		
		aRes = this.call('create', data);
		
		if(aRes.success)
		{
			return this.createObject(aRes.object);
		}
		modelData.aMsg = aRes.msg;
		
		return null;
	};
	
	this.getMessages = function() {
		return modelData.aMsg;
	};
	
	/**
	 * Zwraca z bazy obiekt na podstawie ID
	 * 
	 * @param	int		iId	id obiektu
	 * @return	Lib_Model
	 */
	this.getOne = function(iId) {
		aRes = this.call('get-one', {itemid: iId});
		return this.createObject(aRes);
	};

	
// INNE
	
	/**
	 * Konstuktor fabryki
	 * 
	 * @param	strign	model	nazwa modelu
	 * @return	void	
	 */
	this.construct = function(model) {
		modelData.sModel = model;
	};
	
	/**
	 * Wykonuje hita do skryptu ph z zapytaniem o dane
	 * 
	 * @return	array
	 */
	this.call = function(sMethod, aParams) {

		aResult = null;
		$.ajax({
			type: "POST",
	        url: '/ajax/'+ modelData.sModel +'/'+ sMethod +'/id/'+ Lib_Factory.iProjectId,
	        data: aParams,
	        dataType: 'json',
	        async: false,
	        success: function(res) {
	        	aResult = res;
			}
		});
		
		return aResult;
	};
	
	
	/**
	 * Konstuktor modelu
	 * 
	 * @param	strign	model	nazwa modelu
	 * @param	array	data	dane modelu
	 * @return	void	
	 */
	this.createObject = function(data) {
		return null;
	};
};

// STATYCZNE
	Lib_Factory.iProjectId = null;
	
	Lib_Factory.setProjectId = function(iId) {
		Lib_Factory.iProjectId = iId;
	};