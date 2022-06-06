<?php
/**
* Copyleft (C) 2012
* @author "B.Tag" <bb.anyd@gmail.com> ,
* and [chris wanstrath (chris@ozmm.org),
* vlad andersen (vlad.andersen@gmail.com)]
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
*/
class Yaml
{

    const YAML_STRING_ZERO = '0';
    const YAML_ZERO = 0;
    const YAML_NUMBER_ONE = 1;
    const YAML_NUMBER_TWO = 2;
    const YAML_NUMBER_THREE = 3;
    const YAML_MINUS_ONE = -1;
    const YAML_VERTICAL_LINE = '|';
    const YAML_LEFT_ANGLE = '<';
    const YAML_RIGHT_ANGLE = '>';
    const YAML_LINE_FEED = '\n';

    const YAML_COMMENT = '#';
    const YAML_DOUBLE_QUOTATION = '"';
    const YAML_SINGLE_QUOTATION = "'";
    const YAML_ASTERISK = '*';
    const YAML_AND  = '&';
    const YAML_COLON = ':';
    const YAML_THREE_MINUS_SIGN = '---';
    const YAML_MINUS_SIGN = '-';

    private $tmpKeyOrValue = array();
    private $tmpResult = array();
    private $delayedKey = array();
    private $LiteralPlaceHolder = '___YAML_Literal_Block___';
    private $indent = 0;
    private $_dumpIndent;
    private $_dumpWordWrap;
    private $_containsGroupAnchor = false;
    private $_containsGroupAlias = false;
    private $SavedGroups = array();

    public static function getInstance(){
        return new self();
    }

    /*
    * Will YAML data reading into Array
    */
    private function _readerFile( $fileName ){
        if (!empty($fileName) && strpos($fileName, self::YAML_LINE_FEED) === false && file_exists($fileName))
            return file($fileName);
        else{
           throw new Exception("not found {$fileName},Please check the file exists.");
        }
    }

    /*
    * format data
    * Will YAML data parsed into an Array of structure
    */
    private function _formatData( array $data ){
        $tmpIndent = self::YAML_ZERO;

        if (empty ($data))
        return array();
        $cnt = count($data);
        foreach ($data as $key=>$val){
        //echo $val;
        // count indent
        $this->indent = $tmpIndent = strlen($val) - strlen(ltrim($val));
        // by indent get Node
        $tmpNode = $this->getParentNodeByIndent($tmpIndent);
        // strip node indent
        $tmpStripNodeIndent = $this->stripIndent($val,$tmpIndent);
        // if node data is comment, skip
        if($this->isComment($tmpStripNodeIndent))
        continue;
        // if node data is empty, skip
        if(trim ($tmpStripNodeIndent) === '')
        continue;
        $this->tmpKeyOrValue = $tmpNode;

        $literalBlockStyle = $this->startsLiteralBlock($tmpStripNodeIndent);

        $literalBlock = '';
        if($literalBlockStyle){
        $tmpStripNodeIndent = rtrim ($tmpStripNodeIndent, $literalBlockStyle . self::YAML_LINE_FEED);
        $tmpStripNodeIndent .= $this->LiteralPlaceHolder;
        $literal_block_indent = strlen($data[$key+1]) - strlen(ltrim($data[$key+1]));
        while (++$key < $cnt && $this->literalBlockContinues($data[$key], $this->indent)) {
        $literalBlock = $this->addLiteralLine($literalBlock, $data[$key], $literalBlockStyle, $literal_block_indent);
        }
        $key--;
        }

        while (++$key < $cnt && $this->greedilyNeedNextLine($tmpStripNodeIndent)) {

        $tmpStripNodeIndent = rtrim ($tmpStripNodeIndent, " \n\t\r") . ' ' . ltrim ($data[$key], " \t");
        }
        $key--;

        //$testStr = 'dddddd:"dddddd"';
        //var_dump(strpos ($testStr, self::YAML_DOUBLE_QUOTATION));
        $comment = strpos ($tmpStripNodeIndent, self::YAML_COMMENT);

        if ( $comment ) {

        $cutStr = substr($tmpStripNodeIndent, $comment,self::YAML_MINUS_ONE);

        $tmpStripNodeIndent = str_replace($cutStr, '', $tmpStripNodeIndent);

        unset($cutStr);

        }
        // var_dump($tmpStripNodeIndent);
        $lineArray = $this->_parserLine($tmpStripNodeIndent);
        // var_dump($lineArray);
        if ($literalBlockStyle)
        $lineArray = $this->revertLiteralPlaceHolder ($lineArray, $literalBlock);

        $this->addArray($lineArray, $tmpIndent);

        foreach ($this->delayedKey as $indent => $delayedPath)
        $this->tmpKeyOrValue[$indent] = $delayedPath;

        $this->delayedKey = array();

        }
        // var_dump($this->tmpResult);
        return $this->tmpResult;

    }

    private function addArrayInline ($array, $indent) {
    //$CommonGroupPath = $this->tmpKeyOrValue;
    if (empty ($array)) return false;

    foreach ($array as $k => $_) {
    $this->addArray(array($k => $_), $indent);
    //$this->tmpKeyOrValue = $CommonGroupPath;
    }
    return true;
    }

    private function referenceContentsByAlias ($alias) {

    if (!isset($this->SavedGroups[$alias])) {
    echo "Bad group name: $alias."; break;
    }
    $groupPath = $this->SavedGroups[$alias];
    $value = $this->tmpResult;
    foreach ($groupPath as $k) {
    $value = $value[$k];
    }

    return $value;
    }

    private function addArray ($incoming_data, $incoming_indent) {

    // print_r ($incoming_data);
    // var_dump(count ($incoming_data));
    if (count ($incoming_data) > self::YAML_NUMBER_ONE){
    return $this->addArrayInline ($incoming_data, $incoming_indent);
    }

    $key = key ($incoming_data);

    $value = isset($incoming_data[$key]) ? $incoming_data[$key] : null;
    if ($key === '__!YAMLZero')
    $key = self::YAML_STRING_ZERO;

    if ($incoming_indent == self::YAML_ZERO && !$this->_containsGroupAlias && !$this->_containsGroupAnchor) { // Shortcut for root-level values.
    if ($key || $key === '' || $key === self::YAML_STRING_ZERO) {
    $this->tmpResult[$key] = $value;
    } else {
    $this->tmpResult[] = $value; end ($this->tmpResult); $key = key ($this->tmpResult);
    }
    $this->tmpKeyOrValue[$incoming_indent] = $key;
    return;
    }



    $history = array();
    // Unfolding inner array tree.
    $history[] = $_arr = $this->tmpResult;
    foreach ($this->tmpKeyOrValue as $k) {
    $history[] = $_arr = $_arr[$k];
    }

    if ($this->_containsGroupAlias) {
    $value = $this->referenceContentsByAlias($this->_containsGroupAlias);
    $this->_containsGroupAlias = false;
    }


    // Adding string or numeric key to the innermost level or $this->arr.
    if (is_string($key) && $key == '<<') {
    if (!is_array ($_arr)) {
    $_arr = array ();
    }

    $_arr = array_merge ($_arr, $value);
    } else if ($key || $key === '' || $key === '0') {
    if (!is_array ($_arr))
    $_arr = array ($key=>$value);
    else
    $_arr[$key] = $value;
    } else {
    if (!is_array ($_arr)) {
    $_arr = array ($value); $key = 0;
    }
    else { $_arr[] = $value; end ($_arr); $key = key ($_arr);
    }
    }

    $reverse_path = array_reverse($this->tmpKeyOrValue);
    $reverse_history = array_reverse ($history);
    $reverse_history[0] = $_arr;
    $cnt = count($reverse_history) - 1;
    for ($i = 0; $i < $cnt; $i++) {
    $reverse_history[$i+1][$reverse_path[$i]] = $reverse_history[$i];
    }
    $this->tmpResult = $reverse_history[$cnt];

    $this->tmpKeyOrValue[$incoming_indent] = $key;

    if ($this->_containsGroupAnchor) {
    $this->SavedGroups[$this->_containsGroupAnchor] = $this->tmpKeyOrValue;
    if (is_array ($value)) {
    $k = key ($value);
    if (!is_int ($k)) {
    $this->SavedGroups[$this->_containsGroupAnchor][$incoming_indent + 2] = $k;
    }
    }
    $this->_containsGroupAnchor = false;
    }

    }

    function revertLiteralPlaceHolder ($lineArray, $literalBlock) {
    $tmpStr = '';
    foreach ($lineArray as $k => $_) {
    // var_dump($this->LiteralPlaceHolder);
    if (is_array($_))
    // var_dump($_);
    $lineArray[$k] = $this->revertLiteralPlaceHolder ($_, $literalBlock);
    else if (substr($_, self::YAML_MINUS_ONE * strlen ($this->LiteralPlaceHolder)) == $this->LiteralPlaceHolder)
    $tmpStr = rtrim ($literalBlock, "\r\n");
    }
    // var_dump($tmpStr);
    return $lineArray;
    }

    private function _parserLine($strLine){
    $strLine = trim($strLine);
    if (empty($strLine))
    return array();
    $tmpAnchorOrAlias = $this->searchAnchorOrAlias($strLine);
    // var_dump($tmpGroup);

    if(!empty($tmpAnchorOrAlias)){

    if ($tmpAnchorOrAlias[self::YAML_ZERO] == self::YAML_AND)
    $this->_containsGroupAnchor = substr ($tmpAnchorOrAlias, self::YAML_NUMBER_ONE);
    if ($tmpAnchorOrAlias[self::YAML_ZERO] == self::YAML_ASTERISK)
    $this->_containsGroupAlias = substr ($tmpAnchorOrAlias, self::YAML_NUMBER_ONE);

    $strLine = trim(str_replace($tmpAnchorOrAlias, '', $strLine));
    }

    /*
    * Mapped Sequence
    */

    //cut last char.
    $cutLastChar = substr ($strLine, self::YAML_MINUS_ONE, self::YAML_NUMBER_ONE);

    if ($strLine[self::YAML_ZERO] == self::YAML_MINUS_SIGN &&
    $cutLastChar == self::YAML_COLON){
    $key = $this->unquote(trim(substr ($strLine, self::YAML_NUMBER_ONE , self::YAML_MINUS_ONE)));
    $this->delayedKey = array(strpos ($strLine, $key) + $this->indent => $key);
    return array($key=>array());
    }

    /*
    * Mapped Value
    */
    if( $cutLastChar == self::YAML_COLON ){
    $key = $this->unquote(trim(substr ($strLine, self::YAML_ZERO , self::YAML_MINUS_ONE)));
    return array($key=>'');
    }

    /*
    * is array element
    */
    if ($this->isArrayElement($strLine))
    return $this->returnArrayElement($strLine);

    //is Plain Array
    if($strLine[self::YAML_ZERO] == '[' &&
    substr ($strLine, self::YAML_MINUS_ONE, self::YAML_NUMBER_ONE) == ']'){

    return $this->_toType($strLine);
    }


    // return Key Value Pair
    $tmpArray = array();
    if (strpos ($strLine, self::YAML_COLON)) {
    // It's a key/value pair most likely
    // If the key is in double quotes pull it out
    if (($strLine[self::YAML_ZERO] == self::YAML_DOUBLE_QUOTATION
    || $strLine[self::YAML_ZERO] == self::YAML_SINGLE_QUOTATION)
    && preg_match('/^(["\'](.*)["\'](\s)*:)/',$strLine,$matches)) {
    $value = trim(str_replace($matches[self::YAML_NUMBER_ONE],'',$strLine));
    $key = $matches[self::YAML_NUMBER_TWO];
    } else {
    // Do some guesswork as to the key and the value
    $explode = explode(self::YAML_COLON,$strLine);
    $key = trim($explode[self::YAML_ZERO]);
    array_shift($explode);
    $value = trim(implode(self::YAML_COLON,$explode));
    }
    // Set the type of the value. Int, string, etc
    $value = $this->_toType($value);
    if ($key === self::YAML_STRING_ZERO) $key = '__!YAMLZero';
    $tmpArray[$key] = $value;
    } else {
    $tmpArray = array ($strLine);
    }

    return $tmpArray;

    }

    private function returnKeyValuePair ($line) {
    $array = array();
    $key = '';
    if (strpos ($line, ':')) {
    // It's a key/value pair most likely
    // If the key is in double quotes pull it out
    if (($line[0] == '"' || $line[0] == "'") && preg_match('/^(["\'](.*)["\'](\s)*:)/',$line,$matches)) {
    $value = trim(str_replace($matches[1],'',$line));
    $key = $matches[2];
    } else {
    // Do some guesswork as to the key and the value
    $explode = explode(':',$line);
    $key = trim($explode[0]);
    array_shift($explode);
    $value = trim(implode(':',$explode));
    }
    // Set the type of the value. Int, string, etc
    $value = $this->_toType($value);
    if ($key === '0') $key = '__!YAMLZero';
    $array[$key] = $value;
    } else {
    $array = array ($line);
    }
    return $array;

    }

    /**
    * Finds the type of the passed value, returns the value as the new type.
    * @access private
    * @param string $value
    * @return mixed
    */
    private function _toType($value) {
    if ($value === '') return null;
    $first_character = $value[0];
    $last_character = substr($value, self::YAML_MINUS_ONE, self::YAML_NUMBER_ONE);

    $is_quoted = false;
    do {
    if (!$value) break;
    if ($first_character != self::YAML_DOUBLE_QUOTATION && $first_character != self::YAML_SINGLE_QUOTATION) break;
    if ($last_character != self::YAML_DOUBLE_QUOTATION && $last_character != self::YAML_SINGLE_QUOTATION) break;
    $is_quoted = true;
    } while (0);

    if ($is_quoted)
    return strtr(substr ($value, self::YAML_NUMBER_ONE, self::YAML_MINUS_ONE), array ('\\"' => self::YAML_DOUBLE_QUOTATION, '\'\'' => '\'', '\\\'' => '\''));

    if (strpos($value, ' #') !== false && !$is_quoted)
    $value = preg_replace('/\s+#(.+)$/','',$value);

    if (!$is_quoted) $value = str_replace('\n', "\n", $value);

    if ($first_character == '[' && $last_character == ']') {
    // Take out strings sequences and mappings
    $innerValue = trim(substr ($value, 1, -1));
    if ($innerValue === '') return array();
    $explode = $innerValue;
    // Propagate value array
    $value = array();
    foreach ($explode as $v) {
    $value[] = $this->_toType($v);
    }
    return $value;
    }

    if (strpos($value,': ')!==false && $first_character != '{') {
    $array = explode(': ',$value);
    $key = trim($array[self::YAML_ZERO]);
    array_shift($array);
    $value = trim(implode(': ',$array));
    $value = $this->_toType($value);
    return array($key => $value);
    }

    if ($first_character == '{' && $last_character == '}') {
    $innerValue = trim(substr ($value, self::YAML_NUMBER_ONE, self::YAML_MINUS_ONE));
    if ($innerValue === '') return array();
    // Inline Mapping
    // Take out strings sequences and mappings
    $explode = $this->_inlineEscape($innerValue);
    // Propagate value array
    $array = array();
    foreach ($explode as $v) {
    $SubArr = $this->_toType($v);
    if (empty($SubArr)) continue;
    if (is_array ($SubArr)) {
    $array[key($SubArr)] = $SubArr[key($SubArr)]; continue;
    }
    $array[] = $SubArr;
    }
    return $array;
    }

    if ($value == 'null' || $value == 'NULL' || $value == 'Null' || $value == '' || $value == '~') {
    return null;
    }

    if ( is_numeric($value) && preg_match ('/^(-|)[1-9]+[0-9]*$/', $value) ){
    $intvalue = (int)$value;
    if ($intvalue != PHP_INT_MAX)
    $value = $intvalue;
    return $value;
    }

    if (in_array($value,
    array('true', 'on', '+', 'yes', 'y', 'True', 'TRUE', 'On', 'ON', 'YES', 'Yes', 'Y'))) {
    return true;
    }

    if (in_array(strtolower($value),
    array('false', 'off', self::YAML_MINUS_SIGN, 'no', 'n'))) {
    return false;
    }

    if (is_numeric($value)) {
    if ($value === self::YAML_STRING_ZERO) return self::YAML_ZERO;
    if (rtrim ($value, self::YAML_ZERO) === $value)
    $value = (float)$value;
    return $value;
    }

    return $value;
    }

    private function isArrayElement ($line) {
    if (!$line)
    return false;
    if ($line[self::YAML_ZERO] != self::YAML_MINUS_SIGN)
    return false;
    if (strlen ($line) > self::YAML_NUMBER_THREE){
    if (substr($line,self::YAML_ZERO,self::YAML_NUMBER_THREE) == self::YAML_THREE_MINUS_SIGN) {
    return false;
    }
    }

    return true;
    }

    private function returnArrayElement ($line) {
    if (strlen($line) <= self::YAML_NUMBER_ONE)
    return array(array());
    $array = array();
    $value = trim(substr($line,self::YAML_NUMBER_ONE));
    $value = $this->_toType($value);
    $array[] = $value;
    return $array;
    }

    private function unquote ($value) {
    if (!$value)
    return $value;
    if (!is_string($value))
    return $value;
    if ($value[self::YAML_ZERO] == self::YAML_SINGLE_QUOTATION)
    return trim ($value, self::YAML_SINGLE_QUOTATION);
    if ($value[self::YAML_ZERO] == self::YAML_DOUBLE_QUOTATION)
    return trim ($value, self::YAML_DOUBLE_QUOTATION);

    return $value;
    }



    /*
    * bill-to: &id001
    * street: |
    * 123 Tornado Alley
    * Suite 16
    * city: East Westville
    * state: KS
    *
    * ship-to: *id001
    * Repeat part use this method processing: use the anchor point (and)
    * and reference (*) label will "bill-to" hash table contents are
    * copied into the "ship-to" hash table
    *
    * function searchAnchorOrAlias search string included (&) and (*) character
    *
    */

    private function searchAnchorOrAlias ($strLine) {
    $symbolsForReference = 'A-z0-9_\-';

    if (strpos($strLine, self::YAML_AND) === false && strpos($strLine, self::YAML_ASTERISK) === false)
    return false;

    if ($strLine[self::YAML_ZERO] == self::YAML_AND && preg_match('/^('.self::YAML_AND.'['.$symbolsForReference.']+)/', $strLine, $matches))
    return $matches[self::YAML_ZERO];

    if ($strLine[self::YAML_ZERO] == self::YAML_ASTERISK && preg_match('/^(\*['.$symbolsForReference.']+)/', $strLine, $matches))
    return $matches[self::YAML_ZERO];

    if (preg_match('/('.self::YAML_AND.'['.$symbolsForReference.']+)$/', $strLine, $matches))
    return $matches[self::YAML_ZERO];

    if (preg_match('/(\*['.$symbolsForReference.']+$)/', $strLine, $matches))
    return $matches[self::YAML_ZERO];

    if (preg_match ('#^\s*<<\s*:\s*(\*[^\s]+).*$#', $strLine, $matches))
    return $matches[self::YAML_ZERO];

    return false;

    }



    private function greedilyNeedNextLine( $var ) {
    $line = trim ($var);
    if (!strlen($var)) return false;
    if (substr ($var, self::YAML_MINUS_ONE, self::YAML_NUMBER_ONE) == ']') return false;
    if ($var[0] == '[') return true;
    if (preg_match ('#^[^:]+?:\s*\[#', $var)) return true;
    return false;
    }

    private function literalBlockContinues ($str, $lineIndent) {
    if (!trim($str))
    return true;
    if (strlen($str) - strlen(ltrim($str)) > $lineIndent)
    return true;
    return false;
    }

    private function addLiteralLine ($literalBlock, $str, $literalBlockStyle, $indent = -1) {
    $str = $this->stripIndent($str, $indent);
    if ($literalBlockStyle !== self::YAML_VERTICAL_LINE) {
    $str = $this->stripIndent($str);
    }
    $str = rtrim ($str, "\r\n\t ") . self::YAML_LINE_FEED;
    if ($literalBlockStyle == self::YAML_VERTICAL_LINE) {
    return $literalBlock . $str;
    }
    if (strlen($str) == self::YAML_ZERO)
    return rtrim($literalBlock, ' ') . self::YAML_LINE_FEED;
    if ($str == self::YAML_LINE_FEED && $literalBlockStyle == self::YAML_RIGHT_ANGLE) {
    return rtrim ($literalBlock, " \t") . self::YAML_LINE_FEED;
    }
    if ($str != self::YAML_LINE_FEED)
    $str = trim ($str, "\r\n ") . " ";
    return $literalBlock . $str;
    }

    /*
    * strip string Indent
    */
    private function stripIndent( $str, $indent = -1 ){

    if ($indent == self::YAML_MINUS_ONE)
    $indent = strlen($str) - strlen(ltrim($str));
    return substr($str,$indent);
    }

    /*
    * Chinese Character intercepted
    */
    // private function cnsubstr( $str, $start, $len ){

    // $tmpstr = "";
    // $strlens = $start + $len;
    // // var_dump($strlens);
    // for($i = 0; $i < $strlens; $i++) {
    // if(ord(substr($str, $i, 1)) > 0xa0) {
    // $tmpstr .= substr($str, $i, 2);
    // $i++;
    // } else
    // $tmpstr .= substr($str, $i, 1);

    // }

    // return $tmpstr;
    // }

    /*
    *
    */
    private function getParentNodeByIndent( $indent ){

    if ($indent == self::YAML_ZERO)
    return array();
    $tmp = $this->tmpKeyOrValue;
    do {
    end($tmp);
    $lastIndent = key($tmp);
    if($indent <= $lastIndent)
    array_pop($tmp);

    }while ($indent <= $lastIndent);

    return $tmp;

    }

    /*
    * loader file
    * parser file data.
    *
    */
    public function Loader( $fileName ){
    return $this->_formatData($this->_readerFile($fileName));
    }

    public function Parser(array $tmpArray){
    return $this->_formatData($tmpArray);
    }

    /*
    * if data is comment return true
    * # and ---
    */

    private function isComment ($Indent) {
    // var_dump($Indent[0]);
    if (!$Indent)
    return false;
    if ($Indent[0] == self::YAML_COMMENT)
    return true;
    if (trim($Indent, " \r\n\t") == self::YAML_THREE_MINUS_SIGN)
    return true;
    return false;
    }

    private function startsLiteralBlock ( $str ) {
        $lastChar = substr (trim($str), self::YAML_MINUS_ONE);
        if ($lastChar != self::YAML_RIGHT_ANGLE && $lastChar != self::YAML_VERTICAL_LINE)
        return false;
        if ($lastChar == self::YAML_VERTICAL_LINE)
        return $lastChar;
        // HTML tags should not be counted as literal blocks.
        if (preg_match ('#<.*?>$#', $str))
        return false;
        return $lastChar;
    }
}
