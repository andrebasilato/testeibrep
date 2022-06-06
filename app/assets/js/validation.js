/*--------------------------------------------------------------------------------------------*\

  validation.js
  -------------

  v2.4, Feb 2008

  This script provides generic validation for any web form. For a discussion and example usage
  of this script, go to http://www.benjaminkeen.com/software/js_validation

  This script is written by Ben Keen with additional code contributed by Mihai Ionescu and
  Nathan Howard. It is free to distribute, to re-write - to do what ever you want with it.

  Before using it, please read the following disclaimer.

  THIS SOFTWARE IS PROVIDED ON AN "AS-IS" BASIS WITHOUT WARRANTY OF ANY KIND. BENJAMINKEEN.COM
  SPECIFICALLY DISCLAIMS ANY OTHER WARRANTY, EXPRESS OR IMPLIED, INCLUDING ANY WARRANTY OF
  MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. IN NO EVENT SHALL BENJAMINKEEN.COM BE
  LIABLE FOR ANY CONSEQUENTIAL, INDIRECT, SPECIAL OR INCIDENTAL DAMAGES, EVEN IF BENJAMINKEEN.COM
  HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH POTENTIAL LOSS OR DAMAGE. USER AGREES TO HOLD
  BENJAMINKEEN.COM HARMLESS FROM AND AGAINST ANY AND ALL CLAIMS, LOSSES, LIABILITIES AND EXPENSES.

\*--------------------------------------------------------------------------------------------*/


/*--------------------------------------------------------------------------------------------*\
  Function: validateFields()
  Purpose:  generic form field validation.
  Parameters: form  - the name of the form to validate
              rules - an array of the validation rules. Each rule is a string of the form:

   "[if:FIELDNAME=VALUE,]REQUIREMENT,fieldname[,fieldname2 [,fieldname3, date_flag]],error message"

              if:FIELDNAME=VALUE,   This allows us to only validate a field only if a fieldname
                       FIELDNAME has a value VALUE. This option allows for nesting; i.e. you can
                       have multiple if clauses, separated by a comma. They will be examined in the
                       order in which they appear in the line.

              Valid REQUIREMENT strings are:
                "required"     - field must be filled in
                "digits_only"  - field must contain digits only
                "is_alpha"     - field must only contain alphanumeric characters (0-9, a-Z)
                "custom_alpha" - field must be of the custom format specified.
                      fieldname:  the name of the field
                      fieldname2: a character or sequence of special characters. These characters are:
                          L   An uppercase Letter.          V   An uppercase Vowel.
                          l   A lowercase letter.           v   A lowercase vowel.
                          D   A letter (upper or lower).    F   A vowel (upper or lower).
                          C   An uppercase Consonant.       x   Any number, 0-9.
                          c   A lowercase consonant.        X   Any number, 1-9.
                          E   A consonant (upper or lower).
                "reg_exp"      - field must match the supplied regular expression.
                      fieldname:  the name of the field
                      fieldname2: the regular expression
                      fieldname3: (optional) flags for the reg exp (like i for case insensitive
                "letters_only" - field must only contains letters (a-Z)

                "length=X"     - field has to be X characters long
                "length=X-Y"   - field has to be between X and Y (inclusive) characters long
                "length>X"     - field has to be greater than X characters long
                "length>=X"    - field has to be greater than or equal to X characters long
                "length<X"     - field has to be less than X characters long
                "length<=X"    - field has to be less than or equal to X characters long

                "valid_email"  - field has to be a valid email address
                "valid_date"   - field has to be a valid date
                      fieldname:  MONTH
                      fieldname2: DAY
                      fieldname3: YEAR
                      date_flag:  "later_date" / "any_date"
                "same_as"      - fieldname is the same as fieldname2 (for password comparison)

                "range=X-Y"    - field must be a number between the range of X and Y inclusive
                "range>X"      - field must be a number greater than X
                "range>=X"     - field must be a number greater than or equal to X
                "range<X"      - field must be a number less than X
                "range<=X"     - field must be a number less than or equal to X

  Comments:   With both digits_only, is_alpha, letters_only and valid_email options, if the empty
              string is passed in it won't generate an error, thus allowing validation of
              non-required fields. So, for example, if you want a field to be a valid email address,
              provide validation for both "required" and "valid_email".
\*------------------------------------------------------------------------------------------------*/
function validateFields(form, rules)
{
  // loop through rules
  for (var i=0; i<rules.length; i++)
  {
    // split row into component parts
    var row = rules[i].split(",");

    // while the row begins with "if:..." test the condition. If true, strip the
    // if:..., part and continue evaluating the rest of the line. Keep repeating
    // this while the line begins with an if-condition. If it fails any of the
    // conditions, don't bother validating the rest of the line.
    var satisfiesIfConditions = true;
    while (row[0].match("^if:"))
    {
      var condition = row[0];
      condition = condition.replace("if:", "");

      // check if it's a = or != test
      var comparison = "equal";
      var parts = new Array();
      if (condition.search("!=") != -1)
      {
        parts = condition.split("!=");
        comparison = "not_equal";
      }
      else
        parts = condition.split("=");

      var fieldToCheck = parts[0];
      var valueToCheck = parts[1];

      // find value of FIELDNAME for conditional check
      var fieldnameValue = "";
      if (form[fieldToCheck].type == undefined) // RADIO
      {
        for (var j=0; j<form[fieldToCheck].length; j++)
        {
          if (form[fieldToCheck][j].checked)
            fieldnameValue = form[fieldToCheck][j].value;
        }
      }
      // single checkbox
      else if (form[fieldToCheck].type == "checkbox")
      {
        if (form[fieldToCheck].checked)
          fieldnameValue = form[parts[0]].value;
      }
      // all other field types
      else
        fieldnameValue = form[parts[0]].value;

      // if the VALUE is NOT the same, we don't need to validate this field. Return.
      if (comparison == "equal" &&  fieldnameValue != valueToCheck)
      {
        satisfiesIfConditions = false;
        break;
      }
      else if (comparison == "not_equal" && fieldnameValue == valueToCheck)
      {
        satisfiesIfConditions = false;
        break;
      }
      else
        row.shift();    // remove this if-condition from line, and continue validating line
    }

    if (!satisfiesIfConditions)
      continue;


    var requirement = row[0];
    var fieldName   = row[1];

    // depending on the validation test, store the incoming strings for use later...
    if (row.length == 6)        // valid_date
    {
      var fieldName2   = row[2];
      var fieldName3   = row[3];
      var date_flag    = row[4];
      var errorMessage = row[5];
    }
    else if (row.length == 5)     // reg_exp (WITH flags like g, i, m)
    {
      var fieldName2   = row[2];
      var fieldName3   = row[3];
      var errorMessage = row[4];
    }
    else if (row.length == 4)     // same_as, custom_alpha, reg_exp (without flags like g, i, m)
    {
      var fieldName2   = row[2];
      var errorMessage = row[3];
    }
    else
      var errorMessage = row[2];    // everything else!


    // if the requirement is "length...", rename requirement to "length" for switch statement
    if (requirement.match("^length"))
    {
      var lengthRequirements = requirement;
      requirement = "length";
    }

    // if the requirement is "range=...", rename requirement to "range" for switch statement
    if (requirement.match("^range"))
    {
      var rangeRequirements = requirement;
      requirement = "range";
    }


    // now, validate whatever is required of the field
    switch (requirement)
    {
      case "required":

		// if radio buttons or multiple checkboxes:
        if (form[fieldName].type == undefined)
        {
          var oneIsChecked = false;
          for (var j=0; j<form[fieldName].length; j++)
          {
            if (form[fieldName][j].checked)
              oneIsChecked = true;
          }
          if (!oneIsChecked)
          {
            alertMessage(form[fieldName], errorMessage);
            return false;
          }
        }
        else if (form[fieldName].type == "select-multiple")
        {
          var oneIsSelected = false;
          for (k=0; k<form[fieldName].length; k++)
          {
            if (form[fieldName][k].selected)
              oneIsSelected = true;
          }

          // if no options have been selected, or if there ARE no options in the multi-select
          // dropdown, return false
          if (!oneIsSelected || form[fieldName].length == 0)
          {
            alertMessage(form[fieldName], errorMessage);
            return false;
          }
        }
        // a single checkbox
        else if (form[fieldName].type == "checkbox")
        {
          if (!form[fieldName].checked)
          {
            alertMessage(form[fieldName], errorMessage);
            return false;
          }
        }
        // otherwise, just perform ordinary "required" check.
        else if (!form[fieldName].value)
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

      case "formato_arquivo":
	  	if(form[fieldName].value){
			var nomeArquivo = form[fieldName].value.split(".");
			var extensao 	= nomeArquivo[nomeArquivo.length-1].toLowerCase();
			var tiposPermit	= fieldName2.toLowerCase();
			var extensoes 	= tiposPermit.split('|');

			 if (!in_array(extensao, extensoes)){
				alertMessage(form[fieldName], errorMessage);
				return false;
			 }
		}

      break;

	  case "file_required":
	  	if(form[fieldName].value == ""){
			alertMessage(form[fieldName], errorMessage);
			return false;
		}
      break;

	  case "array_required":
	    var count = 0;
	  	if (is_array(form[fieldName+"[]"])){
			for (var j=0; j<form[fieldName+"[]"].length; j++){
				if (form[fieldName+"[]"][j].checked)
					count++;
			}
		}
		if(count < fieldName2){
			alertMessage(form[fieldName+"[]"], errorMessage);
          return false;
		}
	  break;

	  case "tamanho_arquivo":
		var arquivo =  form[fieldName].files[0];
		if(arquivo){
			var tamanho = Math.round(arquivo.size * 100 / 1024) / 100;
			if(fieldName3 != "" && (tamanho > fieldName3)){
				alertMessage(form[fieldName], errorMessage);
				return false;
			}
		}
	  break;

	  case "digits_only":
        if (form[fieldName].value && form[fieldName].value.match(/\D/))
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

      case "letters_only":
        if (form[fieldName].value && form[fieldName].value.match(/[^a-zA-Z]/))
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;


	  case "is_alpha":
        if (form[fieldName].value && form[fieldName].value.match(/\W/))
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

      case "custom_alpha":
        var conversion = {
          "L": "[A-Z]",
          "V": "[AEIOU]",
          "l": "[a-z]",
          "v": "[aeiou]",
          "D": "[a-zA-Z]",
          "F": "[aeiouAEIOU]",
          "C": "[BCDFGHJKLMNPQRSTVWXYZ]",
          "x": "[0-9]",
          "c": "[bcdfghjklmnpqrstvwxyz]",
          "X": "[1-9]",
          "E": "[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]"
            };

        var reg_exp_str = "";
        for (j=0; j<fieldName2.length; j++)
        {
          if (conversion[fieldName2.charAt(j)])
            reg_exp_str += conversion[fieldName2.charAt(j)];
          else
            reg_exp_str += fieldName2.charAt(j);
        }
        var reg_exp = new RegExp(reg_exp_str);

        if (form[fieldName].value && reg_exp.exec(form[fieldName].value) == null)
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

      case "reg_exp":
        var reg_exp_str = fieldName2;

        // rather crumby, but...
        if (row.length == 5)
          var reg_exp = new RegExp(reg_exp_str, fieldName3);
        else
          var reg_exp = new RegExp(reg_exp_str);

        if (form[fieldName].value && reg_exp.exec(form[fieldName].value) == null)
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

      case "length":
        comparison_rule = "";
        rule_string = "";

        // if-else order is important here: needs to check for >= before >
        if      (lengthRequirements.match(/length=/))
        {
          comparison_rule = "equal";
          rule_string = lengthRequirements.replace("length=", "");
        }
        else if (lengthRequirements.match(/length>=/))
        {
          comparison_rule = "greater_than_or_equal";
          rule_string = lengthRequirements.replace("length>=", "");
        }
        else if (lengthRequirements.match(/length>/))
        {
          comparison_rule = "greater_than";
          rule_string = lengthRequirements.replace("length>", "");
        }
        else if (lengthRequirements.match(/length<=/))
        {
          comparison_rule = "less_than_or_equal";
          rule_string = lengthRequirements.replace("length<=", "");
        }
        else if (lengthRequirements.match(/length</))
        {
          comparison_rule = "less_than";
          rule_string = lengthRequirements.replace("length<", "");
        }

        if(form[fieldName].value != ""){
			// now perform the appropriate validation
			switch (comparison_rule)
			{
			  case "greater_than_or_equal":
				if (!(form[fieldName].value.length >= parseInt(rule_string)))
				{
				  alertMessage(form[fieldName], errorMessage);
				  return false;
				}
				break;

			  case "greater_than":
				if (!(form[fieldName].value.length > parseInt(rule_string)))
				{
				  alertMessage(form[fieldName], errorMessage);
				  return false;
				}
				break;

			  case "less_than_or_equal":
				if (!(form[fieldName].value.length <= parseInt(rule_string)))
				{
				  alertMessage(form[fieldName], errorMessage);
				  return false;
				}
				break;

			  case "less_than":
				if (!(form[fieldName].value.length < parseInt(rule_string)))
				{
				  alertMessage(form[fieldName], errorMessage);
				  return false;
				}
				break;

			  case "equal":
				var range_or_exact_number = rule_string.match(/[^_]+/);
				var fieldCount = range_or_exact_number[0].split("-");

				// if the user supplied two length fields, make sure the field is within that range
				if (fieldCount.length == 2)
				{
				  if (form[fieldName].value.length < fieldCount[0] || form[fieldName].value.length > fieldCount[1])
				  {
					alertMessage(form[fieldName], errorMessage);
					return false;
				  }
				}

				// otherwise, check it's EXACTLY the size the user specified
				else
				{
				  if (form[fieldName].value.length != fieldCount[0])
				  {
					alertMessage(form[fieldName], errorMessage);
					return false;
				  }
				}

				break;
			}
		}
        break;

      // this is also true if field is empty [should be same for digits_only]
      case "valid_email":
        if (form[fieldName].value && !isValidEmail(form[fieldName].value))
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;
      case "registered_email":
          alertMessage(form[fieldName], errorMessage);
          return false;
        break;
      case "valid_date":

        // this is written for future extensibility of isValidDate function to allow
        // checking for dates BEFORE today, AFTER today, IS today and ANY day.
        var isLaterDate = false;
        if    (date_flag == "later_date")
          isLaterDate = true;
        else if (date_flag == "any_date")
          isLaterDate = false;

        if (!isValidDate(form[fieldName].value, form[fieldName2].value, form[fieldName3].value, isLaterDate))
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

	  case "valid_date_br":
		var campos = form[fieldName].value.split("/");
        if (!isValidDate(campos[1], campos[0], campos[2], false) && campos[0] != "")
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

      case "same_as":
        if (form[fieldName].value != form[fieldName2].value)
        {
          alertMessage(form[fieldName], errorMessage);
          return false;
        }
        break;

      case "range":

        comparison_rule = "";
        rule_string = "";

        // if-else order is important here: needs to check for >= before >
        if      (rangeRequirements.match(/range=/))
        {
          comparison_rule = "equal";
          rule_string = rangeRequirements.replace("range=", "");
        }
        else if (rangeRequirements.match(/range>=/))
        {
          comparison_rule = "greater_than_or_equal";
          rule_string = rangeRequirements.replace("range>=", "");
        }
        else if (rangeRequirements.match(/range>/))
        {
          comparison_rule = "greater_than";
          rule_string = rangeRequirements.replace("range>", "");
        }
        else if (rangeRequirements.match(/range<=/))
        {
          comparison_rule = "less_than_or_equal";
          rule_string = rangeRequirements.replace("range<=", "");
        }
        else if (rangeRequirements.match(/range</))
        {
          comparison_rule = "less_than";
          rule_string = rangeRequirements.replace("range<", "");
        }

        // now perform the appropriate validation
        switch (comparison_rule)
        {
          case "greater_than_or_equal":
            if (form[fieldName].value && !(form[fieldName].value >= Number(rule_string)))
            {
              alertMessage(form[fieldName], errorMessage);
              return false;
            }
            break;

          case "greater_than":
            if (form[fieldName].value && !(form[fieldName].value > Number(rule_string)))
            {
              alertMessage(form[fieldName], errorMessage);
              return false;
            }
            break;

          case "less_than_or_equal":
            if (form[fieldName].value && !(form[fieldName].value <= Number(rule_string)))
            {
              alertMessage(form[fieldName], errorMessage);
              return false;
            }
            break;

          case "less_than":
            if (form[fieldName].value && !(form[fieldName].value < Number(rule_string)))
            {
              alertMessage(form[fieldName], errorMessage);
              return false;
            }
            break;

          case "equal":
            var rangeValues = rule_string.split("-");

            // if the user supplied two length fields, make sure the field is within that range
            if (form[fieldName].value && (form[fieldName].value < Number(rangeValues[0])) || (form[fieldName].value > Number(rangeValues[1])))
            {
              alertMessage(form[fieldName], errorMessage);
              return false;
            }
            break;
        }
        break;

      case "valida_cpf":
		  if (!validaCPF(form[fieldName].value))
		  {
			alertMessage(form[fieldName], errorMessage);
			return false;
		  }

		  break;

	  case "valida_cnpj":
		  if (!validaCNPJ(form[fieldName].value))
		  {
			alertMessage(form[fieldName], errorMessage);
			return false;
		  }

		  break;

	  case "function":
        custom_function = fieldName;
        if (!(eval(custom_function + "()")))
          return false;
        break;

      default:
        alert("Unknown requirement flag in validateFields(): " + requirement);
        return false;
    }
  }

  exibeLoading();

  return true;
}


/*--------------------------------------------------------------------------------------------*\
  Function: alertMessage()
  Purpose:  simple helper function which alerts a message, then focuses on and highlights
            a particular field.
\*--------------------------------------------------------------------------------------------*/
function alertMessage(obj, message)
{
  var backgroundColor = "#F2F9FF";

  alert(message);

  // if "obj" is an array: it's a radio button. Focus on the first element.

  if (obj.type == undefined)
    obj[0].focus();
  else
  {
    obj.style.background = backgroundColor;
    obj.focus();
  }
  return false;
}


/*--------------------------------------------------------------------------------------------*\
  Function: isValidEmail
  Purpose:  tests a string is a valid email
\*--------------------------------------------------------------------------------------------*/
function isValidEmail(str)
{
  // trim starting / ending whitespace
  str = str.replace(/^\s*/, "");
  str = str.replace(/\s*$/, "");

  var at="@"
  var dot="."
  var lat=str.indexOf(at)
  var lstr=str.length
  var ldot=str.indexOf(dot)

  if (str.indexOf(at)==-1)
    return false

  if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr)
    return false

  if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr)
    return false

  if (str.indexOf(at,(lat+1))!=-1)
    return false

  if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot)
    return false

  if (str.indexOf(dot,(lat+2))==-1)
    return false

  if (str.indexOf(" ")!=-1)
    return false

  return true;
}


// helper function to check to see if a string is empty
function isEmpty(str)
{
  return ((str == null) || (str.length == 0));
}


/*--------------------------------------------------------------------------------------------*\
  Function: isWhitespace()
  Purpose:  Returns true if string parameter is empty or whitespace characters only.
\*--------------------------------------------------------------------------------------------*/
function isWhitespace(s)
{
  var i;

  // Is s empty?
  if (isEmpty(s)) return true;

  for (var i=0; i<s.length; i++)
  {
    var c = s.charAt(i);
    if (whitespace.indexOf(c) == -1)
      return false;
  }

  return true;
}


/*----------------------------------------------------------------------------*\
  Function:   isValidDate()
  Purpose:    to check an incoming date is valid. If any of the date parameters
              fail, it returns a string message denoting the problem.
  Parameters: month       - an integer between 1 and 12
              day         - an integer between 1 and 31 (depending on month)
              year        - a 4-digit integer value
              isLaterDate - a boolean value. If true, the function verifies the
                            date being passed in is LATER than the current date.
\*----------------------------------------------------------------------------*/
function isValidDate(month, day, year, isLaterDate)
{
  // depending on the year, calculate the number of days in the month
  if (year % 4 == 0)      // LEAP YEAR
    var daysInMonth = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  else
    var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);


  // first, check the incoming month and year are valid.
  if (!month || !day || !year)          return false;
  if (1 > month || month > 12)          return false;
  if (year < 0)                         return false;
  if (1 > day || day > daysInMonth[month-1]) return false;


  // if required, verify the incoming date is LATER than the current date.
  if (isLaterDate)
  {
    // get current date
    var today = new Date();
    var currMonth = today.getMonth() + 1; // since returns 0-11
    var currDay   = today.getDate();
    var currYear  = today.getFullYear();

    // zero-pad today's month & day
    if (String(currMonth).length == 1)  currMonth = "0" + currMonth;
    if (String(currDay).length == 1)  currDay   = "0" + currDay;
    currDate = String(currYear) + String(currMonth) + String(currDay);

    // zero-pad incoming month & day
    if (String(month).length == 1)  month = "0" + month;
    if (String(day).length == 1)  day   = "0" + day;
    incomingDate = String(year) + String(month) + String(day);

    if (Number(currDate) > Number(incomingDate))
      return false;
  }

  return true;
}

/*----------------------------------------------------------------------------
 FUNÇÕES DIANDSON CARDOSO
----------------------------------------------------------------------------*/
function isNumber(e) {
	if (document.all)// Internet Explorer
		var tecla = event.keyCode;
	else
		var tecla = e.which;

	if (tecla != 8 && tecla != 0 && (tecla < 48 || tecla > 57)) {
		return false;
	}
}
function isNumberFloat(e) {
	if (document.all)// Internet Explorer
		var tecla = event.keyCode;
	else
		var tecla = e.which;
	if (tecla != 44 && tecla != 46 && tecla != 8 && tecla != 0 && (tecla < 48 || tecla > 57)) {
		return false;
	}
}
function isNumberCopy() {
	var value = $(this).attr('value');
	if (isNaN(value)){
		 $(this).attr('value','') ;
	}
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,"",features);
}

function is_array(mixed_var){
	var key = '';

    if (!mixed_var) return false;

    if (typeof mixed_var === 'object') {
        if (mixed_var.hasOwnProperty) {
            for (key in mixed_var){
                if (false === mixed_var.hasOwnProperty(key))
                    return false;
            }
        }
        return true;
    }
    return false;
}

function in_array(valor,vetor){
    for(var i in vetor){
        if(valor == vetor[i]){
            return i;
        }
    }
    return false;
}

function validarMinutosSegundos() {

	var value = $(this).attr('value');

	var Horas 		= value.substring(0,2);
	var Minutos 	= value.substring(3,5);
	var Segundos 	= value.substring(6,8);

	var segundosNew = "00";
	var minutosNew = "00";
	var horasNew = "00";

	if(Segundos > 59){
		segundosNew = Segundos % 60;
		minutosNew = parseInt(Segundos / 60);
	}else{
		segundosNew = Segundos;
	}

	Minutos = Number(minutosNew) + Number(Minutos);

	if(Minutos > 59){
		minutosNew = Minutos % 60;
		horasNew = parseInt(Minutos / 60);
	}else{
		minutosNew = Minutos;
	}

	Horas = Number(Horas) + Number(horasNew);


	if(Horas < 10) Horas = "0"+Horas;
	if(minutosNew < 10) minutosNew = "0"+minutosNew;
	if(segundosNew < 10) segundosNew = "0"+segundosNew;

	if(Horas <= 99)
		var newHorario = Horas+':'+minutosNew+':'+segundosNew;
	else
		var newHorario = '99:59:59';

	$(this).attr('value',newHorario);
}

//METODO DE SELECIONAR UM RADIO
(function($) {
	$.fn.radioSel = function(valueToSel){
		/*
        Como usar
        » Marca o radio cujo valor é 'DIDI';
          $('input[id=nome]').radioSel('DIDI')
        » Retorna o valor do radio selecionado ou 'false' caso nenhum esteja marcado;
          $('input[id=nome]').radioSel()
        » Limpa todos os radio marcado;
          $('input[id=nome]').radioSel('')
        */

		if(arguments.length>0){
			if(valueToSel!=''){
				return this.each(function(){ // itera sobre cada elemento encontrado
					if($(this).val()==valueToSel)this.checked = true;
				})
			}else{ //Se veio vazio é para limpar todas as marcações
				return this.each(function(){ this.checked = false; })
			}
		}else{
			valorSelecionado = false;
			this.each(function(){ // itera sobre cada elemento encontrado
				if(this.checked){
					valorSelecionado = $(this).val();
					return valorSelecionado;
				}
			});
			return valorSelecionado;
		}
	};
})(jQuery);

/*----------------------------------------------------------------------------
 FUNÇÕES TOMAZ NOVAES
----------------------------------------------------------------------------*/

function validaCPF(valueCPF) {
	if(valueCPF.length > 0) {
	  //valida o CPF
	  var cpf = valueCPF;
	  exp = /\.|\-/g
	  cpf = cpf.toString().replace(exp, "");
	  if(cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
		  return false;

	  add = 0;
	  for (i = 0; i < 9; i++)
		  add += parseInt(cpf.charAt(i)) * (10 - i);
	  rev = 11 - (add % 11);
	  if (rev == 10 || rev == 11)
		  rev = 0;
	  if (rev != parseInt(cpf.charAt(9)))
		  return false;

	  add = 0;
	  for (i = 0; i < 10; i ++)
		  add += parseInt(cpf.charAt(i)) * (11 - i);
	  rev = 11 - (add % 11);
	  if (rev == 10 || rev == 11)
		  rev = 0;
	  if (rev != parseInt(cpf.charAt(10)))
		  return false;
	}
	return true;

}

function validaCNPJ(valueCNPJ) {
	//valida o CNPJ
	var cnpj = valueCNPJ;
	var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
	var dig1= new Number;
	var dig2= new Number;

	exp = /\.|\-|\//g
	cnpj = cnpj.toString().replace( exp, "" );
	if(cnpj.length != 14 || cnpj == "00000000000000" || cnpj == "11111111111111" || cnpj == "22222222222222" || cnpj == "33333333333333" || cnpj == "44444444444444" || cnpj == "55555555555555" || cnpj == "666666666666666" || cnpj == "77777777777777" || cnpj == "88888888888888" || cnpj == "99999999999999")
		return false;
	var digito = new Number(eval(cnpj.charAt(12) + cnpj.charAt(13)));

	for(i = 0; i < valida.length; i++){
			dig1 += (i > 0 ? (cnpj.charAt(i - 1) * valida[i]) : 0);
			dig2 += cnpj.charAt(i) * valida[i];
	}
	dig1 = (((dig1 % 11) < 2) ? 0 : (11-(dig1 % 11)));
	dig2 = (((dig2 % 11) < 2) ? 0 : (11-(dig2 % 11)));

	if(((dig1 * 10) + dig2) != digito)
		return false;

	return true;
}