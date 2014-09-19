if("undefined"===typeof PF)var PF={}
PF.checkCount=function(element, threshold){
	if(theField = document.getElementById('checkedInputs')){
		currVal = parseInt(theField.value); 
		if(element.checked){
			currVal = currVal+1;
		}
		else{
			currVal = currVal-1;
		}
		theField.value = currVal ; 
		
		if(currVal >= threshold){
			return true;
		}
	}
	return false;
}