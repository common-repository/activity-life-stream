function validate()
{
	var $ = jQuery.noConflict();
	var numHyphenExp = /^[0-9-]+$/;
	var alphNumExp=  /^[0-9a-zA-Z]+$/;
	var numExp = /^[0-9]+$/;
	var alphaExp = /^[a-zA-Z\ ]+$/;
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	
	$(".border_color").removeClass("border_color");
	
	var isOk=true;

		 if	(!(alphaExp.test(trim($("#twitter_name").val())))) 
		 {
			isOk=false;
			$("#twitter_name").addClass("border_color");
			$("#twitter_name").focus();
			return isOk;
		}
		 if	(!(alphaExp.test(trim($("#twinked_title").val())))) 
		 {
			isOk=false;
			$("#twinked_title").addClass("border_color");
			$("#twinked_title").focus();
			return isOk;
		}
		if(($("#twinked_desc").val().length>'140') || ($("#twinked_desc").val().length<'10')) 
		 {
			isOk=false;
			$("#twinked_desc").addClass("border_color");
			$("#twinked_desc").focus();
			return isOk;
		}
		if (!(alphaCommonExp.test(trim($("#skill_tags").val())))) 
		 {
			isOk=false;
			$("#skill_tags").addClass("border_color");
			$("#skill_tags").focus();
			return isOk;
		}
		if	(trim($("#linkedin").val()).length<'15') 
		 {
			isOk=false;
			$("#linkedin").addClass("border_color");
			$("#linkedin").focus();
			return isOk;
		}	
		return isOk;
		
}

function validate_resume()
{
	var $ = jQuery.noConflict();
	var numHyphenExp = /^[0-9-]+$/;
	var alphNumExp=  /^[0-9a-zA-Z]+$/;
	var numExp = /^[0-9]+$/;
	var alphaExp = /^[a-zA-Z\ ]+$/;
	var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	
	$(".border_color").removeClass("border_color");
	
	var isOk=true;

		 if	(!(alphaExp.test(trim($("#twitter_name").val())))) 
		 {
			isOk=false;
			$("#twitter_name").addClass("border_color");
			$("#twitter_name").focus();
			return isOk;
		}
		 if	(!(alphaExp.test(trim($("#twinked_title").val())))) 
		 {
			isOk=false;
			$("#twinked_title").addClass("border_color");
			$("#twinked_title").focus();
			return isOk;
		}
		if(($("#twinked_desc").val().length>'140') || ($("#twinked_desc").val().length<'10')) 
		 {
			isOk=false;
			$("#twinked_desc").addClass("border_color");
			$("#twinked_desc").focus();
			return isOk;
		}
		if (!(alphaCommonExp.test(trim($("#skill_tags").val())))) 
		 {
			isOk=false;
			$("#skill_tags").addClass("border_color");
			$("#skill_tags").focus();
			return isOk;
		}
		if	(trim($("#linkedin").val()).length<'15') 
		 {
			isOk=false;
			$("#linkedin").addClass("border_color");
			$("#linkedin").focus();
			return isOk;
		}	
		return isOk;
		
}


function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}
 
function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}
 
function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}
jQuery(function($){
//					$('#twinked_desc').keydown(function(ev){
//														if($(this).val().length > 142 && ev.keyCode != 8 && ev.keyCode != 13) { return false;
//														 $('#ch_counter').html(142-$(this).val().length);
//														 });
				});