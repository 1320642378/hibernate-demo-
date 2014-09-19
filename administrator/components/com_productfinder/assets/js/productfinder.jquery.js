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

	jQuery('#pf_answers_status').text('Loading...');
	jQuery.ajax({
	    url: url,
	    type: 'post',
	    dataType: 'json',
	    success: function(data) {
	    	jQuery('#pf_answers tbody').append('<tr>' + data + '</tr>');
	    },
	    error : function(err, req) {
	    	jQuery('#pf_answers_status').text('Error');
	    }
	});	
	jQuery('#pf_answers_status').text('');
	return false;
}
PF.addSeparator=function() {
	var url = 'index.php?option=com_productfinder&task=ajax.addSeparator&format=raw';
	
	jQuery('#pf_answers_status').text('Loading...');
	jQuery.ajax({
	    url: url,
	    type: 'post',
	    dataType: 'json',
	    success: function(data) {
	    	jQuery('#pf_answers tbody').append('<tr class="separator">' + data + '</tr>');
	    },
	    error : function(err, req) {
	    	jQuery('#pf_answers_status').text('Error');
	    }
	});	
	jQuery('#pf_answers_status').text('');
	return false;	
}
PF.deleteRow=function(el){
	jQuery(el).closest('tr').remove();
}
PF.deleteAnswer=function(el, ansId){
	var url = 'index.php?option=com_productfinder&task=ajax.deleteAnswer&format=raw&id='+ansId;
	
	jQuery('#pf_answers_status').text('Loading...');
	jQuery.ajax({
	    url: url,
	    type: 'post',
	    dataType: 'json',
	    success: function(data) {
	    	jQuery(el).closest('tr').remove();
	    	jQuery('#pf_answers tbody').append('<tr class="separator">' + data + '</tr>');
	    },
	    error : function(err, req) {
	    	jQuery('#pf_answers_status').text('Error');
	    }
	});	
	jQuery('#pf_answers_status').text('');
	return false;	

}

PF.serializeAnswers=function(){
	
	var el = jQuery('#pf_answers input[name^=ans_id]');
	var ids = new Array(jQuery(el).size());
	jQuery(el).each(function( index ) {
		ids[index] = jQuery(this).val();
	});	
	
	var el = jQuery('#pf_answers input[name^=ans_sel]');
	var sel = new Array();
	var i = 0;
	jQuery(el).each(function( index ) {
		if(jQuery(this).is(":checked"))
		{
			sel[i] = jQuery(this).val();
			i++;
		}
	});		

	var el = jQuery('#pf_answers input[name^=ans_value]');
	var values = new Array(jQuery(el).size());
	jQuery(el).each(function( index ) {
		values[index] = jQuery(this).val();
	});		

	var el = jQuery('#pf_answers input[name^=ans_label]');
	var labels = new Array(jQuery(el).size());
	jQuery(el).each(function( index ) {
		labels[index] = jQuery(this).val();
	});
	
	var el = jQuery('#pf_answers input[name^=ans_sep]');
	var sep = new Array(jQuery(el).size());
	jQuery(el).each(function( index ) {
		sep[index] = jQuery(this).val();
	});
	
	var el = jQuery('#pf_answers input[name^=ans_def]');
	var def = new Array(jQuery(el).size());
	jQuery(el).each(function( index ) {
		def[index] = (jQuery(this).is(':checked') ? 1 : 0);
	});		
	
	var el = jQuery('#pf_answers input[name^=ans_order]');
	var order = new Array(jQuery(el).size());
	jQuery(el).each(function( index ) {
		order[index] = jQuery(this).val();
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
	
	if (tel = jQuery('#jform_answers')) {
		var retval = '';
		if (typeof JSON.encode == 'function') {
			retval = JSON.encode(data);
		} else {
			retval = JSON.stringify(data);
		}
		jQuery(tel).val(retval);
	}	
	return false;
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
PF.checkAllValues = function() {
	var theToggler = jQuery('#toggler');
	var tState = theToggler.is(":checked");
	var boxes = jQuery('#pf_answers input[name^="ans_sel"]');
	var i=0;
	jQuery(boxes).each(function(){
		if(tState){
			jQuery(this).attr('checked', 'checked');
			i+=1;
		}
		else{
			jQuery(this).removeAttr('checked');
		}
	});	
	
	jQuery('input[name="boxchecked"]').val(i);
}