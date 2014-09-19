/**
* Product Finder for Joomla! 2.5.x
* @package Product Finder
* @subpackage Component
* @version 1.0
* @revision $Revision: 1.2 $
* @author Andrea Forghieri
* @copyright (C) 2012 - 2014 Andrea Forghieri, Solidsystem - http://www.solidsystem.it
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL version 2
*/
if("undefined"===typeof PF)var PF={}
PF.addAnswer=function(){
	var url = 'index.php?option=com_productfinder&task=ajax.addField&format=raw';
	var result = $('pf_answers').getElement('tbody');
	var statusBox = $('pf_answers_status');
	var request = new Request.JSON({
		url : url,
		method : 'post',
		onRequest : function() {
			statusBox.set('text', 'Loading...');
		},
		onComplete : function(response) {
			statusBox.set('text', '');
			var theRow = new Element('tr', { html: response });
			result.grab(theRow);
		}
	}).send();
}
PF.addSeparator=function() {
	var url = 'index.php?option=com_productfinder&task=ajax.addSeparator&format=raw';
	var result = $('pf_answers').getElement('tbody');
	var statusBox = $('pf_answers_status');
	var request = new Request.JSON({
		url : url,
		method : 'post',
		onRequest : function() {
			statusBox.set('text', 'Loading...');
		},
		onComplete : function(response) {
			statusBox.set('text', '');
			var theRow = new Element('tr', { className: 'separator', html: response });
			result.grab(theRow);
		}
	}).send();
}
PF.deleteRow=function(el){
	parent = el.getParent('tr');
	if(parent){parent.dispose();}
}
PF.deleteAnswer=function(el, ansId){
	var url = 'index.php?option=com_productfinder&task=ajax.deleteAnswer&format=raw&id='+ansId;
	var statusBox = $('pf_answers_status');
	var request = new Request.JSON({
		url : url,
		method : 'post',
		onRequest : function() {
			statusBox.set('text', 'Processing...');
		},
		onComplete : function(response) {
			if(response == '1'){
				statusBox.set('text', '');
				parent = el.getParent('tr');
				if(parent){parent.dispose();}
			}
			else{
				statusBox.set('text', 'Error');				
			}
		}
	}).send();

}

PF.serializeAnswers=function(){
	var el = $('pf_answers');
	if(! el) return;
	
	inputs = el.getElements('input[name^=ans_id]');
	var numInputs = inputs.length;
	var ids = new Array(numInputs);
	$each(inputs, function(elem, index){
		ids[index] = elem.value;
	});
	
	
	var i = 0;
	inputs = el.getElements('input[name^=ans_sel]')
	var sel = new Array(numInputs);
	$each(inputs, function(elem, index){
		if(elem.checked) {sel[i] = elem.value; i++}
	});

	
	inputs = el.getElements('input[name^=ans_value]');
	var values = new Array(numInputs);
	$each(inputs, function(elem, index){
		values[index] = elem.value;
	});
	
	inputs = el.getElements('input[name^=ans_label]')
	var labels = new Array(numInputs);
	$each(inputs, function(elem, index){
		labels[index] = elem.value;
	});
	
	inputs = el.getElements('input[name^=ans_sep]')
	var sep = new Array(numInputs);
	$each(inputs, function(elem, index){
		sep[index] = elem.value;
	});
	
	inputs = el.getElements('input[name^=ans_def]')
	i = 0;
	var def = new Array(numInputs);
	$each(inputs, function(elem, index){
		if(elem.checked) {
			def[i] = 1;
		}
		else{
			def[i] = 0;
		}
		i++;
	});
	
	inputs = el.getElements('input[name^=ans_order]')
	var order = new Array(numInputs);
	$each(inputs, function(elem, index){
		order[index] = elem.value;
	});
	
	var data = {
		"ans_ids":ids,
		"ans_values":values,
		"ans_labels":labels,
		"ans_sep":sep,
		"ans_defaults":def,
		"ans_sel":sel,
		"ans_orders":order,
	 };
	if(tel = $('jform_answers')) {
		var retval = '';
		if(typeof JSON.encode =='function'){
			retval = JSON.encode(data);
		}
		else{
			retval = JSON.stringify(data);
		}
		tel.value = retval;
	}
}

PF.toggleRule = function(el, ref, itemId, answerId){
	if(!el || !ref || !itemId || ! answerId) {
		alert('Missing param');
		return;
	}
	targetImg = el.getFirst('img');
	if(!targetImg) return;
	
	var url = 'index.php?option=com_productfinder&task=ajax.toggleRule&format=raw';
	var statusBox = $('pf_rules_status');
	var request = new Request.JSON({
		url : url,
		method : 'post',
		data : '&ref=' + ref + '&cid=' + itemId + '&aid=' + answerId,  
		onRequest : function() {
			statusBox.set('text', 'Processing...');
		},
		onComplete : function(response) {
			if(undefined == response){
				alert('Server Error');
			}
			else if(response == 0){
				statusBox.set('text', 'Error updating rule');
			}
			else{
				el.getFirst('img').dispose();
				el.set('html', response);
				statusBox.set('text', '');
			}
		}
	}).send();
}

PF.saveAnswerOrder = function( n ) {
	PF.checkAllAnswers_button( n );
}

//needed by saveorder function
PF.checkAllAnswers_button = function( n ) {
	for ( var j = 0; j <= n; j++ ) {
		box = eval( "document.adminForm.ans_sel" + j );
		if ( box ) {
			if ( box.checked == false ) {
				box.checked = true;
			}
		} 
		else{
			return;
		}
	}
	submitform('answers.saveorder');
}

/* check all field values */
PF.checkAllValues = function(){
	var theToggler = $('toggler');
	var cbx = $$('input[name^=ans_sel]');
	var i=0;
	$each(cbx, function(elem, index){
	    elem.checked = theToggler.checked;
	    i += (theToggler.checked == true ? 1 : 0);
	});
	document.adminForm.boxchecked.value = i;
}