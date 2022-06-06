<?php

/*--------------------------------------------------------------------------------------------*\

  validation.php
  --------------

  v2.3.3, Apr 2010

  This script provides generic validation for any web form. For a discussion and example usage
  of this script, go to http://www.benjaminkeen.com/software/php_validation

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
  Parameters: field - the POST / GET fields from a form which need to be validated.
              rules - an array of the validation rules. Each rule is a string of the form:

   "[if:FIELDNAME=VALUE,]REQUIREMENT,fieldname[,fieldname2 [,fieldname3, date_flag]],error message"

              if:FIELDNAME=VALUE,   This allows us to only validate a field
                          only if a fieldname FIELDNAME has a value VALUE. This
                          option allows for nesting; i.e. you can have multiple
                          if clauses, separated by a comma. They will be examined
                          in the order in which they appear in the line.

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

                "valid_email"  - field has to be valid email address
                "valid_date"   - field has to be a valid date
                      fieldname:  MONTH
                      fieldname2: DAY
                      fieldname3: YEAR
                      date_flag:  "later_date" / "any_date"
                "same_as"     - fieldname is the same as fieldname2 (for password comparison)

                "range=X-Y"    - field must be a number between the range of X and Y inclusive
                "range>X"      - field must be a number greater than X
                "range>=X"     - field must be a number greater than or equal to X
                "range<X"      - field must be a number less than X
                "range<=X"     - field must be a number less than or equal to X


  Comments:   With both digits_only, valid_email and is_alpha options, if the empty string is passed
              in it won't generate an error, thus allowing validation of non-required fields. So,
              for example, if you want a field to be a valid email address, provide validation for
              both "required" and "valid_email".
\*--------------------------------------------------------------------------------------------*/
function validateFields($fields, $rules)
{
    $errors = array();

    // loop through rules
    for ($i=0; $i<count($rules); $i++)
    {
        // split row into component parts
        $row = explode(",", $rules[$i]);

        // while the row begins with "if:..." test the condition. If true, strip the if:..., part and
        // continue evaluating the rest of the line. Keep repeating this while the line begins with an
        // if-condition. If it fails any of the conditions, don't bother validating the rest of the line
        $satisfies_if_conditions = true;
        while (preg_match("/^if:/", $row[0]))
        {
            $condition = preg_replace("/^if:/", "", $row[0]);

            // check if it's a = or != test
            $comparison = "equal";
            $parts = array();
            if (preg_match("/!=/", $condition))
            {
                $parts = explode("!=", $condition);
                $comparison = "not_equal";
            }
            else
                $parts = explode("=", $condition);

            $field_to_check = $parts[0];
            $value_to_check = $parts[1];

            // if the VALUE is NOT the same, we don't need to validate this field. Return.
            if ($comparison == "equal" && $fields[$field_to_check] != $value_to_check)
            {
                $satisfies_if_conditions = false;
                break;
            }
            else if ($comparison == "not_equal" && $fields[$field_to_check] == $value_to_check)
            {
                $satisfies_if_conditions = false;
                break;
            }
            else
                array_shift($row);    // remove this if-condition from line, and continue validating line
        }

        if (!$satisfies_if_conditions)
            continue;


        $requirement = $row[0];
        $field_name  = $row[1];

        // depending on the validation test, store the incoming strings for use later...
        if (count($row) == 6)        // valid_date
        {
            $field_name2   = $row[2];
            $field_name3   = $row[3];
            $date_flag     = $row[4];
            $error_message = $row[5];
        }
        else if (count($row) == 5)     // reg_exp (WITH flags like g, i, m)
        {
            $field_name2   = $row[2];
            $field_name3   = $row[3];
            $error_message = $row[4];
        }
        else if (count($row) == 4)     // same_as, custom_alpha, reg_exp (without flags like g, i, m)
        {
            $field_name2   = $row[2];
            $error_message = $row[3];
        }
        else
            $error_message = $row[2];    // everything else!


        // if the requirement is "length=...", rename requirement to "length" for switch statement
        if (preg_match("/^length/", $requirement))
        {
            $length_requirements = $requirement;
            $requirement         = "length";
        }

        // if the requirement is "range=...", rename requirement to "range" for switch statement
        if (preg_match("/^range/", $requirement))
        {
            $range_requirements = $requirement;
            $requirement        = "range";
        }


        // now, validate whatever is required of the field
        switch ($requirement)
        {
            case "required":
                if(is_array($fields[$field_name])){
                    if(!$field_name2){
                        foreach($fields[$field_name] as $ind => $val){
                            if(!$val){
                                $errors[] = $error_message;
                            }
                        }
                    }elseif (!isset($fields[$field_name][$field_name2]) || $fields[$field_name][$field_name2] == ""){
                        $errors[] = $error_message;
                    }
                }else{
                    if (!isset($fields[$field_name]) || $fields[$field_name] == ""){
                        $errors[] = $error_message;
                    }
                }
                break;

            case "array_required":
                if(count($fields[$field_name]) < $field_name2)
                    $errors[] = $error_message;
                break;

            case "file_required":
                if(!$fields[$field_name]['name']){
                    $errors[] = $error_message;
                }
                break;

            case "formato_arquivo":
                $extensao =  strtolower(end(explode(".", $fields[$field_name]['name'])));
                $extensoes = explode('|',strtolower($field_name2));
                if($fields[$field_name]['name'])
                    if(!in_array($extensao, $extensoes))
                        $errors[] = $error_message;
                break;

            case "tamanho_arquivo":
                if(isset($field_name3) && ($fields[$field_name]['size'] / 1024) > $field_name3)
                    $errors[] = $error_message;
                break;

            case "valida_cpf":
                // Extrai somente os números
                if(array_key_exists('documento', $fields))
                    $cpf = preg_replace('/[^0-9]/is', '', $fields['documento']);
                if(array_key_exists('cpf', $fields))
                    $cpf = preg_replace('/[^0-9]/is', '', $fields['cpf']);

                // Verifica se foi informado todos os digitos corretamente
                if (strlen($cpf) == 11) {
                    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
                    if (preg_match('/(\d)\1{10}/', $cpf)) {
                        $errors[] = $error_message;
                    }

                    // Faz o calculo para validar o CPF
                    for ($t = 9; $t < 11; $t++) {
                        for ($d = 0, $c = 0; $c < $t; $c++) {
                            $d += $cpf[$c] * (($t + 1) - $c);
                        }
                        $d = ((10 * $d) % 11) % 10;
                        if ($cpf[$c] != $d) {
                            $errors[] = $error_message;
                        }
                    }
                }
                break;

            case "valida_cnpj":
                if(strlen($fields[$field_name]) > 0) {
                    if(!validaCNPJ($fields[$field_name])) {
                        $errors[] = $error_message;
                        break;
                    }
                }
                break;
            case "digits_only":
                if (isset($fields[$field_name]) && preg_match("/\D/", $fields[$field_name]))
                    $errors[] = $error_message;
                break;

            case "number_only":
                if (isset($fields[$field_name]) && preg_match("/[^0-9]/", $fields[$field_name]))
                    $errors[] = $error_message;
                break;

            case "letters_only":
                if (isset($fields[$field_name]) && preg_match("/[^a-zA-Z]/", $fields[$field_name]))
                    $errors[] = $error_message;
                break;

            // doesn't fail if field is empty
            case "valid_email":
                //$regexp="/^[a-z0-9]+([_+\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
                $regexp="/^([[:alnum:]_.-]){1,}@([[:alnum:][:digit:]_.-]{2,})(\.[[:alnum:]]{2,3})(\.[[:alnum:]]{2})?$/";
                if (isset($fields[$field_name]) && !empty($fields[$field_name]) && !preg_match($regexp, $fields[$field_name]))
                    $errors[] = $error_message;
                break;

            case "length":
                $comparison_rule = "";
                $rule_string     = "";

                if      (preg_match("/length=/", $length_requirements))
                {
                    $comparison_rule = "equal";
                    $rule_string = preg_replace("/length=/", "", $length_requirements);
                }
                else if (preg_match("/length>=/", $length_requirements))
                {
                    $comparison_rule = "greater_than_or_equal";
                    $rule_string = preg_replace("/length>=/", "", $length_requirements);
                }
                else if (preg_match("/length<=/", $length_requirements))
                {
                    $comparison_rule = "less_than_or_equal";
                    $rule_string = preg_replace("/length<=/", "", $length_requirements);
                }
                else if (preg_match("/length>/", $length_requirements))
                {
                    $comparison_rule = "greater_than";
                    $rule_string = preg_replace("/length>/", "", $length_requirements);
                }
                else if (preg_match("/length</", $length_requirements))
                {
                    $comparison_rule = "less_than";
                    $rule_string = preg_replace("/length</", "", $length_requirements);
                }

                if ($fields[$field_name] != ""){
                    switch ($comparison_rule)
                    {
                        case "greater_than_or_equal":
                            if (!(strlen($fields[$field_name]) >= $rule_string))
                                $errors[] = $error_message;
                            break;
                        case "less_than_or_equal":
                            if (!(strlen($fields[$field_name]) <= $rule_string))
                                $errors[] = $error_message;
                            break;
                        case "greater_than":
                            if (!(strlen($fields[$field_name]) > $rule_string))
                                $errors[] = $error_message;
                            break;
                        case "less_than":
                            if (!(strlen($fields[$field_name]) < $rule_string))
                                $errors[] = $error_message;
                            break;
                        case "equal":
                            // if the user supplied two length fields, make sure the field is within that range
                            if (preg_match("/-/", $rule_string))
                            {
                                list($start, $end) = explode("-", $rule_string);
                                if (strlen($fields[$field_name]) < $start || strlen($fields[$field_name]) > $end)
                                    $errors[] = $error_message;
                            }
                            // otherwise, check it's EXACTLY the size the user specified
                            else
                            {
                                if (strlen($fields[$field_name]) != $rule_string)
                                    $errors[] = $error_message;
                            }
                            break;
                    }
                }
                break;

            case "range":
                $comparison_rule = "";
                $rule_string     = "";

                if      (preg_match("/range=/", $range_requirements))
                {
                    $comparison_rule = "equal";
                    $rule_string = preg_replace("/range=/", "", $range_requirements);
                }
                else if (preg_match("/range>=/", $range_requirements))
                {
                    $comparison_rule = "greater_than_or_equal";
                    $rule_string = preg_replace("/range>=/", "", $range_requirements);
                }
                else if (preg_match("/range<=/", $range_requirements))
                {
                    $comparison_rule = "less_than_or_equal";
                    $rule_string = preg_replace("/range<=/", "", $range_requirements);
                }
                else if (preg_match("/range>/", $range_requirements))
                {
                    $comparison_rule = "greater_than";
                    $rule_string = preg_replace("/range>/", "", $range_requirements);
                }
                else if (preg_match("/range</", $range_requirements))
                {
                    $comparison_rule = "less_than";
                    $rule_string = preg_replace("/range</", "", $range_requirements);
                }

                switch ($comparison_rule)
                {
                    case "greater_than":
                        if ($fields[$field_name] && !($fields[$field_name] > $rule_string))
                            $errors[] = $error_message;
                        break;
                    case "less_than":
                        if ($fields[$field_name] && !($fields[$field_name] < $rule_string))
                            $errors[] = $error_message;
                        break;
                    case "greater_than_or_equal":
                        if ($fields[$field_name] && !($fields[$field_name] >= $rule_string))
                            $errors[] = $error_message;
                        break;
                    case "less_than_or_equal":
                        if ($fields[$field_name] && !($fields[$field_name] <= $rule_string))
                            $errors[] = $error_message;
                        break;
                    case "equal":
                        list($start, $end) = explode("-", $rule_string);

                        if ($fields[$field_name] && ($fields[$field_name] < $start) || ($fields[$field_name] > $end))
                            $errors[] = $error_message;
                        break;
                }
                break;

            case "same_as":
                if ($fields[$field_name] != $fields[$field_name2])
                    $errors[] = $error_message;
                break;

            case "valid_date":
                // this is written for future extensibility of isValidDate function to allow
                // checking for dates BEFORE today, AFTER today, IS today and ANY day.
                $is_later_date = false;
                if    ($date_flag == "later_date")
                    $is_later_date = true;
                else if ($date_flag == "any_date")
                    $is_later_date = false;

                if (!is_valid_date($fields[$field_name], $fields[$field_name2], $fields[$field_name3], $is_later_date))
                    $errors[] = $error_message;
                break;

            case "valid_date_br":
                $campos = explode('/',$fields[$field_name]);
                if (!is_valid_date($campos[1], $campos[0], $campos[2], false) and $campos[0] != "")
                    $errors[] = $error_message;
                break;

            case "is_alpha":
                if (preg_match('/[^A-Za-z0-9]/', $fields[$field_name]))
                    $errors[] = $error_message;
                break;

            case "custom_alpha":
                $chars = array();
                $chars["L"] = "[A-Z]";
                $chars["V"] = "[AEIOU]";
                $chars["l"] = "[a-z]";
                $chars["v"] = "[aeiou]";
                $chars["D"] = "[a-zA-Z]";
                $chars["F"] = "[aeiouAEIOU]";
                $chars["C"] = "[BCDFGHJKLMNPQRSTVWXYZ]";
                $chars["x"] = "[0-9]";
                $chars["c"] = "[bcdfghjklmnpqrstvwxyz]";
                $chars["X"] = "[1-9]";
                $chars["E"] = "[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]";

                $reg_exp_str = "";
                for ($j=0; $j<strlen($field_name2); $j++)
                {
                    if (array_key_exists($field_name2[$j], $chars))
                        $reg_exp_str .= $chars[$field_name2[$j]];
                    else
                        $reg_exp_str .= $field_name2[$j];
                }

                if (!empty($fields[$field_name]) && !preg_match("/$reg_exp_str/", $fields[$field_name]))
                    $errors[] = $error_message;
                break;

            case "reg_exp":
                $reg_exp_str = $field_name2;

                // rather crumby, but...
                if (count($row) == 5)
                    $reg_exp = "/" . $reg_exp_str . "/" . $row[3];
                else
                    $reg_exp = "/" . $reg_exp_str . "/";

                if (!empty($fields[$field_name]) && !preg_match($reg_exp, $fields[$field_name]))
                    $errors[] = $error_message;
                break;

            default:
                die("Unknown requirement flag in validate_fields(): $requirement");
                break;
        }
    }

    return $errors;
}


/*------------------------------------------------------------------------------------------------*\
  Function:   is_valid_date
  Purpose:    checks a date is valid / is later than current date
  Parameters: $month       - an integer between 1 and 12
              $day         - an integer between 1 and 31 (depending on month)
              $year        - a 4-digit integer value
              $is_later_date - a boolean value. If true, the function verifies the date being passed
                               in is LATER than the current date.
\*------------------------------------------------------------------------------------------------*/
function is_valid_date($month, $day, $year, $is_later_date)
{
    // depending on the year, calculate the number of days in the month
    if ($year % 4 == 0)      // LEAP YEAR
        $days_in_month = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    else
        $days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    // first, check the incoming month and year are valid.
    if (!$month || !$day || !$year) return false;
    if (1 > $month || $month > 12)  return false;
    if ($year < 0)                  return false;
    if (1 > $day || $day > $days_in_month[$month-1]) return false;


    // if required, verify the incoming date is LATER than the current date.
    if ($is_later_date)
    {
        // get current date
        $today = date("U");
        $date = mktime(0, 0, 0, $month, $day, $year);
        if ($date < $today)
            return false;
    }

    return true;
}

/*function validaCNPJ($cnpj) {
    if (strlen($cnpj) <> 18) return 0;
    $soma1 = ($cnpj[0] * 5) +

    ($cnpj[1] * 4) +
    ($cnpj[3] * 3) +
    ($cnpj[4] * 2) +
    ($cnpj[5] * 9) +
    ($cnpj[7] * 8) +
    ($cnpj[8] * 7) +
    ($cnpj[9] * 6) +
    ($cnpj[11] * 5) +
    ($cnpj[12] * 4) +
    ($cnpj[13] * 3) +
    ($cnpj[14] * 2);
    $resto = $soma1 % 11;
    $digito1 = $resto < 2 ? 0 : 11 - $resto;
    $soma2 = ($cnpj[0] * 6) +

    ($cnpj[1] * 5) +
    ($cnpj[3] * 4) +
    ($cnpj[4] * 3) +
    ($cnpj[5] * 2) +
    ($cnpj[7] * 9) +
    ($cnpj[8] * 8) +
    ($cnpj[9] * 7) +
    ($cnpj[11] * 6) +
    ($cnpj[12] * 5) +
    ($cnpj[13] * 4) +
    ($cnpj[14] * 3) +
    ($cnpj[16] * 2);
    $resto = $soma2 % 11;
    $digito2 = $resto < 2 ? 0 : 11 - $resto;
    return (($cnpj[16] == $digito1) && ($cnpj[17] == $digito2));
} */
function validaCNPJ($cnpj) {

    if (strlen($cnpj) == 14) {
        $soma1 = ($cnpj[0] * 5) +

            ($cnpj[1] * 4) +
            ($cnpj[2] * 3) +
            ($cnpj[3] * 2) +
            ($cnpj[4] * 9) +
            ($cnpj[5] * 8) +
            ($cnpj[6] * 7) +
            ($cnpj[7] * 6) +
            ($cnpj[8] * 5) +
            ($cnpj[9] * 4) +
            ($cnpj[10] * 3) +
            ($cnpj[11] * 2);
        $resto = $soma1 % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;
        $soma2 = ($cnpj[0] * 6) +

            ($cnpj[1] * 5) +
            ($cnpj[2] * 4) +
            ($cnpj[3] * 3) +
            ($cnpj[4] * 2) +
            ($cnpj[5] * 9) +
            ($cnpj[6] * 8) +
            ($cnpj[7] * 7) +
            ($cnpj[8] * 6) +
            ($cnpj[9] * 5) +
            ($cnpj[10] * 4) +
            ($cnpj[11] * 3) +
            ($cnpj[12] * 2);
        $resto = $soma2 % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;
        return (($cnpj[12] == $digito1) && ($cnpj[13] == $digito2));
    } elseif (strlen($cnpj) == 18) {
        $soma1 = ($cnpj[0] * 5) +

            ($cnpj[1] * 4) +
            ($cnpj[3] * 3) +
            ($cnpj[4] * 2) +
            ($cnpj[5] * 9) +
            ($cnpj[7] * 8) +
            ($cnpj[8] * 7) +
            ($cnpj[9] * 6) +
            ($cnpj[11] * 5) +
            ($cnpj[12] * 4) +
            ($cnpj[13] * 3) +
            ($cnpj[14] * 2);
        $resto = $soma1 % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;
        $soma2 = ($cnpj[0] * 6) +

            ($cnpj[1] * 5) +
            ($cnpj[3] * 4) +
            ($cnpj[4] * 3) +
            ($cnpj[5] * 2) +
            ($cnpj[7] * 9) +
            ($cnpj[8] * 8) +
            ($cnpj[9] * 7) +
            ($cnpj[11] * 6) +
            ($cnpj[12] * 5) +
            ($cnpj[13] * 4) +
            ($cnpj[14] * 3) +
            ($cnpj[16] * 2);
        $resto = $soma2 % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;
        return (($cnpj[16] == $digito1) && ($cnpj[17] == $digito2));
    } else
        return 0;
}

?>