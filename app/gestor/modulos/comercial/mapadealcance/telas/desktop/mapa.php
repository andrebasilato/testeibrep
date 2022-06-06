<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Mapa de Alcance </title>
<style>
body, td, th {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 11px;
	color: #000;
}
body {
	background-color: #FFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	overflow: hidden;
}
.box-conteudo {
	padding: 0px !important;
}
#mapCanvas {
	width: 100%;
	height: 100%;
	min-height: 500px;
}
#infoPanel {
	margin-left: 10px;
}
#infoPanel div {
	margin-bottom: 5px;
}
.ui-autocomplete {
	background: #fff;
	border-top: 1px solid #ccc;
	cursor: pointer;
	font: 15px 'Open Sans', Arial;
	margin-left: 3px;
	width: 493px !important;
	position: fixed;
}
.ui-autocomplete .ui-menu-item {
	list-style: none outside none;
	padding: 7px 0 9px 10px;
}
.ui-autocomplete .ui-menu-item:hover {
	background: #eee
}
.ui-autocomplete .ui-corner-all {
	color: #666 !important;
	display: block;
}
#divBotao {
	position: absolute;
	left: 90px;
	top: 28px;
	width: 150px;
	height: 40px;
	z-index: 2;
}
#divFiltro {
	position: absolute;
	left: 90px;
	top: 45px;
	padding: 15px 10px 0px 10px;
	background:#FFF;
	z-index: 1;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;	
	border-bottom: 1px #CCC solid;
	border-right: 1px #CCC solid;
}
.btn {
	display: inline-block;
	padding: 4px 12px;
	margin-bottom: 0;
	font-size: 12px;
	line-height: 20px;
	color: #333333;
	text-align: center;
	text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
	vertical-align: middle;
	cursor: pointer;
	background-color: #f5f5f5;
	background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
	background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
	background-repeat: repeat-x;
	border: 1px solid #cccccc;
	border-color: #e6e6e6 #e6e6e6 #bfbfbf;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	border-bottom-color: #b3b3b3;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
	-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
	-moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
	box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}
.btn-warning {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #faa732;
	background-image: -moz-linear-gradient(top, #fbb450, #f89406);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fbb450), to(#f89406));
	background-image: -webkit-linear-gradient(top, #fbb450, #f89406);
	background-image: -o-linear-gradient(top, #fbb450, #f89406);
	background-image: linear-gradient(to bottom, #fbb450, #f89406);
	background-repeat: repeat-x;
	border-color: #f89406 #f89406 #ad6704;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
 filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffbb450', endColorstr='#fff89406', GradientType=0);
 filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}

input, button, select, textarea {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}




/* Formulario */
form {
  margin: 0 0 20px;
}
fieldset {
  padding: 0;
  margin: 0;
  border: 0;
}
legend {
  display: block;
  width: 100%;
  padding: 0;
  margin-bottom: 10px;
  font-size: 14px;
  line-height: 40px;
  color: #333333;
  border: 0;
  border-bottom: 1px solid #e5e5e5;
}
legend small {
  font-size: 15px;
  color: #999999;
}
label,
input,
button,
select,
textarea {
  font-size: 11px;
  font-weight: normal;
  line-height: 11px;
}
input,
button,
select,
textarea {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
label {
  display: block;
  margin-bottom: 5px;
}
select,
textarea,
input[type="text"],
input[type="password"],
input[type="datetime"],
input[type="datetime-local"],
input[type="date"],
input[type="month"],
input[type="time"],
input[type="week"],
input[type="number"],
input[type="email"],
input[type="url"],
input[type="search"],
input[type="tel"],
input[type="color"],
.uneditable-input {
  display: inline-block;
  height: 20px;
  padding: 4px 6px;
  margin-bottom: 6px;
  font-size: 14px;
  line-height: 20px;
  color: #555555;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  vertical-align: middle;
}
input,
textarea,
.uneditable-input {
  width: 206px;
}
textarea {
  height: auto;
}
textarea,
input[type="text"],
input[type="password"],
input[type="datetime"],
input[type="datetime-local"],
input[type="date"],
input[type="month"],
input[type="time"],
input[type="week"],
input[type="number"],
input[type="email"],
input[type="url"],
input[type="search"],
input[type="tel"],
input[type="color"],
.uneditable-input {
  background-color: #ffffff;
  border: 1px solid #cccccc;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  -webkit-transition: border linear .2s, box-shadow linear .2s;
  -moz-transition: border linear .2s, box-shadow linear .2s;
  -o-transition: border linear .2s, box-shadow linear .2s;
  transition: border linear .2s, box-shadow linear .2s;
}
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus {
  border-color: rgba(82, 168, 236, 0.8);
  outline: 0;
  outline: thin dotted \9;
  /* IE6-9 */

  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
  -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
}
input[type="radio"],
input[type="checkbox"] {
  margin: 4px 0 0;
  *margin-top: 0;
  /* IE7 */

  margin-top: 1px \9;
  /* IE8-9 */

  line-height: normal;
}
input[type="file"],
input[type="image"],
input[type="submit"],
input[type="reset"],
input[type="button"],
input[type="radio"],
input[type="checkbox"] {
  width: auto;
}
select,
input[type="file"] {
  height: 30px;
  /* In IE7, the height of the select element cannot be changed by height, only font-size */

  *margin-top: 4px;
  /* For IE7, add top margin to align select with labels */

  line-height: 30px;
}
select {
  width: 220px;
  border: 1px solid #cccccc;
  background-color: #ffffff;
}
select[multiple],
select[size] {
  height: auto;
}
select:focus,
input[type="file"]:focus,
input[type="radio"]:focus,
input[type="checkbox"]:focus {
  outline: thin dotted #333;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
.uneditable-input,
.uneditable-textarea {
  color: #999999;
  background-color: #fcfcfc;
  border-color: #cccccc;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
  -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.025);
  cursor: not-allowed;
}
.uneditable-input {
  overflow: hidden;
  white-space: nowrap;
}
.uneditable-textarea {
  width: auto;
  height: auto;
}
input:-moz-placeholder,
textarea:-moz-placeholder {
  color: #999999;
}
input:-ms-input-placeholder,
textarea:-ms-input-placeholder {
  color: #999999;
}
input::-webkit-input-placeholder,
textarea::-webkit-input-placeholder {
  color: #999999;
}
.radio,
.checkbox {
  min-height: 20px;
  padding-left: 20px;
}
.radio input[type="radio"],
.checkbox input[type="checkbox"] {
  float: left;
  margin-left: -20px;
}
.controls > .radio:first-child,
.controls > .checkbox:first-child {
  padding-top: 5px;
}
.radio.inline,
.checkbox.inline {
  display: inline-block;
  padding-top: 5px;
  margin-bottom: 0;
  vertical-align: middle;
}
.radio.inline + .radio.inline,
.checkbox.inline + .checkbox.inline {
  margin-left: 10px;
}
.input-mini {
  width: 60px;
}
.input-small {
  width: 90px;
}
.input-medium {
  width: 150px;
}
.input-large {
  width: 210px;
}
.input-xlarge {
  width: 270px;
}
.input-xxlarge {
  width: 530px;
}
input[class*="span"],
select[class*="span"],
textarea[class*="span"],
.uneditable-input[class*="span"],
.row-fluid input[class*="span"],
.row-fluid select[class*="span"],
.row-fluid textarea[class*="span"],
.row-fluid .uneditable-input[class*="span"] {
  float: none;
  margin-left: 0;
}
.input-append input[class*="span"],
.input-append .uneditable-input[class*="span"],
.input-prepend input[class*="span"],
.input-prepend .uneditable-input[class*="span"],
.row-fluid input[class*="span"],
.row-fluid select[class*="span"],
.row-fluid textarea[class*="span"],
.row-fluid .uneditable-input[class*="span"],
.row-fluid .input-prepend [class*="span"],
.row-fluid .input-append [class*="span"] {
  display: inline-block;
}
input,
textarea,
.uneditable-input {
  margin-left: 0;
}
.controls-row [class*="span"] + [class*="span"] {
  margin-left: 20px;
}
input.span12,
textarea.span12,
.uneditable-input.span12 {
  width: 926px;
}
input.span11,
textarea.span11,
.uneditable-input.span11 {
  width: 846px;
}
input.span10,
textarea.span10,
.uneditable-input.span10 {
  width: 766px;
}
input.span9,
textarea.span9,
.uneditable-input.span9 {
  width: 686px;
}
input.span8,
textarea.span8,
.uneditable-input.span8 {
  width: 606px;
}
input.span7,
textarea.span7,
.uneditable-input.span7 {
  width: 526px;
}
input.span6,
textarea.span6,
.uneditable-input.span6 {
  width: 446px;
}
input.span5,
textarea.span5,
.uneditable-input.span5 {
  width: 366px;
}
input.span4,
textarea.span4,
.uneditable-input.span4 {
  width: 286px;
}
input.span3,
textarea.span3,
.uneditable-input.span3 {
  width: 206px;
}
input.span2,
textarea.span2,
.uneditable-input.span2 {
  width: 126px;
}
input.span1,
textarea.span1,
.uneditable-input.span1 {
  width: 46px;
}
.controls-row {
  *zoom: 1;
}
.controls-row:before,
.controls-row:after {
  display: table;
  content: "";
  line-height: 0;
}
.controls-row:after {
  clear: both;
}
.controls-row [class*="span"],
.row-fluid .controls-row [class*="span"] {
  float: left;
}
.controls-row .checkbox[class*="span"],
.controls-row .radio[class*="span"] {
  padding-top: 5px;
}
input[disabled],
select[disabled],
textarea[disabled],
input[readonly],
select[readonly],
textarea[readonly] {
  cursor: not-allowed;
  background-color: #eeeeee;
}
input[type="radio"][disabled],
input[type="checkbox"][disabled],
input[type="radio"][readonly],
input[type="checkbox"][readonly] {
  background-color: transparent;
}
input:focus:invalid,
textarea:focus:invalid,
select:focus:invalid {
  color: #b94a48;
  border-color: #ee5f5b;
}
input:focus:invalid:focus,
textarea:focus:invalid:focus,
select:focus:invalid:focus {
  border-color: #e9322d;
  -webkit-box-shadow: 0 0 6px #f8b9b7;
  -moz-box-shadow: 0 0 6px #f8b9b7;
  box-shadow: 0 0 6px #f8b9b7;
}
.form-actions {
  padding: 19px 20px 20px;
  margin-top: 20px;
  margin-bottom: 20px;
  background-color: #f5f5f5;
  border-top: 1px solid #e5e5e5;
  *zoom: 1;
}
.form-actions:before,
.form-actions:after {
  display: table;
  content: "";
  line-height: 0;
}
.form-actions:after {
  clear: both;
}
.help-block,
.help-inline {
  color: #595959;
}
.help-block {
  display: block;
  margin-bottom: 10px;
}
.help-inline {
  display: inline-block;
  *display: inline;
  /* IE7 inline-block hack */

  *zoom: 1;
  vertical-align: middle;
  padding-left: 5px;
}
.input-append,
.input-prepend {
  display: inline-block;
  margin-bottom: 10px;
  vertical-align: middle;
  font-size: 0;
  white-space: nowrap;
}
.input-append input,
.input-prepend input,
.input-append select,
.input-prepend select,
.input-append .uneditable-input,
.input-prepend .uneditable-input,
.input-append .dropdown-menu,
.input-prepend .dropdown-menu,
.input-append .popover,
.input-prepend .popover {
  font-size: 14px;
}
.input-append input,
.input-prepend input,
.input-append select,
.input-prepend select,
.input-append .uneditable-input,
.input-prepend .uneditable-input {
  position: relative;
  margin-bottom: 0;
  *margin-left: 0;
  vertical-align: top;
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;
}
.input-append input:focus,
.input-prepend input:focus,
.input-append select:focus,
.input-prepend select:focus,
.input-append .uneditable-input:focus,
.input-prepend .uneditable-input:focus {
  z-index: 2;
}
.input-append .add-on,
.input-prepend .add-on {
  display: inline-block;
  width: auto;
  height: 20px;
  min-width: 16px;
  padding: 4px 5px;
  font-size: 14px;
  font-weight: normal;
  line-height: 20px;
  text-align: center;
  text-shadow: 0 1px 0 #ffffff;
  background-color: #eeeeee;
  border: 1px solid #ccc;
}
.input-append .add-on,
.input-prepend .add-on,
.input-append .btn,
.input-prepend .btn,
.input-append .btn-group > .dropdown-toggle,
.input-prepend .btn-group > .dropdown-toggle {
  vertical-align: top;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0;
}
.input-append .active,
.input-prepend .active {
  background-color: #a9dba9;
  border-color: #46a546;
}
.input-prepend .add-on,
.input-prepend .btn {
  margin-right: -1px;
}
.input-prepend .add-on:first-child,
.input-prepend .btn:first-child {
  -webkit-border-radius: 4px 0 0 4px;
  -moz-border-radius: 4px 0 0 4px;
  border-radius: 4px 0 0 4px;
}
.input-append input,
.input-append select,
.input-append .uneditable-input {
  -webkit-border-radius: 4px 0 0 4px;
  -moz-border-radius: 4px 0 0 4px;
  border-radius: 4px 0 0 4px;
}
.input-append input + .btn-group .btn:last-child,
.input-append select + .btn-group .btn:last-child,
.input-append .uneditable-input + .btn-group .btn:last-child {
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;
}
.input-append .add-on,
.input-append .btn,
.input-append .btn-group {
  margin-left: -1px;
}
.input-append .add-on:last-child,
.input-append .btn:last-child,
.input-append .btn-group:last-child > .dropdown-toggle {
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;
}
.input-prepend.input-append input,
.input-prepend.input-append select,
.input-prepend.input-append .uneditable-input {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0;
}
.input-prepend.input-append input + .btn-group .btn,
.input-prepend.input-append select + .btn-group .btn,
.input-prepend.input-append .uneditable-input + .btn-group .btn {
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;
}
.input-prepend.input-append .add-on:first-child,
.input-prepend.input-append .btn:first-child {
  margin-right: -1px;
  -webkit-border-radius: 4px 0 0 4px;
  -moz-border-radius: 4px 0 0 4px;
  border-radius: 4px 0 0 4px;
}
.input-prepend.input-append .add-on:last-child,
.input-prepend.input-append .btn:last-child {
  margin-left: -1px;
  -webkit-border-radius: 0 4px 4px 0;
  -moz-border-radius: 0 4px 4px 0;
  border-radius: 0 4px 4px 0;
}
.input-prepend.input-append .btn-group:first-child {
  margin-left: 0;
}
input.search-query {
  padding-right: 14px;
  padding-right: 4px \9;
  padding-left: 14px;
  padding-left: 4px \9;
  /* IE7-8 doesn't have border-radius, so don't indent the padding */

  margin-bottom: 0;
  -webkit-border-radius: 15px;
  -moz-border-radius: 15px;
  border-radius: 15px;
}
/* Allow for input prepend/append in search forms */
.form-search .input-append .search-query,
.form-search .input-prepend .search-query {
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  border-radius: 0;
}
.form-search .input-append .search-query {
  -webkit-border-radius: 14px 0 0 14px;
  -moz-border-radius: 14px 0 0 14px;
  border-radius: 14px 0 0 14px;
}
.form-search .input-append .btn {
  -webkit-border-radius: 0 14px 14px 0;
  -moz-border-radius: 0 14px 14px 0;
  border-radius: 0 14px 14px 0;
}
.form-search .input-prepend .search-query {
  -webkit-border-radius: 0 14px 14px 0;
  -moz-border-radius: 0 14px 14px 0;
  border-radius: 0 14px 14px 0;
}
.form-search .input-prepend .btn {
  -webkit-border-radius: 14px 0 0 14px;
  -moz-border-radius: 14px 0 0 14px;
  border-radius: 14px 0 0 14px;
}
.form-search input,
.form-inline input,
.form-horizontal input,
.form-search textarea,
.form-inline textarea,
.form-horizontal textarea,
.form-search select,
.form-inline select,
.form-horizontal select,
.form-search .help-inline,
.form-inline .help-inline,
.form-horizontal .help-inline,
.form-search .uneditable-input,
.form-inline .uneditable-input,
.form-horizontal .uneditable-input,
.form-search .input-prepend,
.form-inline .input-prepend,
.form-horizontal .input-prepend,
.form-search .input-append,
.form-inline .input-append,
.form-horizontal .input-append {
  display: inline-block;
  *display: inline;
  /* IE7 inline-block hack */

  *zoom: 1;
  margin-bottom: 0;
  vertical-align: middle;
}
.form-search .hide,
.form-inline .hide,
.form-horizontal .hide {
  display: none;
}
.form-search label,
.form-inline label,
.form-search .btn-group,
.form-inline .btn-group {
  display: inline-block;
}
.form-search .input-append,
.form-inline .input-append,
.form-search .input-prepend,
.form-inline .input-prepend {
  margin-bottom: 0;
}
.form-search .radio,
.form-search .checkbox,
.form-inline .radio,
.form-inline .checkbox {
  padding-left: 0;
  margin-bottom: 0;
  vertical-align: middle;
}
.form-search .radio input[type="radio"],
.form-search .checkbox input[type="checkbox"],
.form-inline .radio input[type="radio"],
.form-inline .checkbox input[type="checkbox"] {
  float: left;
  margin-right: 3px;
  margin-left: 0;
}
.control-group {
  margin-bottom: 10px;
}
legend + .control-group {
  margin-top: 20px;
  -webkit-margin-top-collapse: separate;
}
.form-horizontal .control-group {
  margin-bottom: 20px;
  *zoom: 1;
}
.form-horizontal .control-group:before,
.form-horizontal .control-group:after {
  display: table;
  content: "";
  line-height: 0;
}
.form-horizontal .control-group:after {
  clear: both;
}
.form-horizontal .control-label {
  float: left;
  width: 160px;
  padding-top: 5px;
  text-align: right;
}
.form-horizontal .controls {
  *display: inline-block;
  *padding-left: 20px;
  margin-left: 180px;
  *margin-left: 0;
}
.form-horizontal .controls:first-child {
  *padding-left: 180px;
}
.form-horizontal .help-block {
  margin-bottom: 0;
}
.form-horizontal input + .help-block,
.form-horizontal select + .help-block,
.form-horizontal textarea + .help-block,
.form-horizontal .uneditable-input + .help-block,
.form-horizontal .input-prepend + .help-block,
.form-horizontal .input-append + .help-block {
  margin-top: 10px;
}
.form-horizontal .form-actions {
  padding-left: 180px;
}

</style>
<script src="/assets/js/jquery.1.7.1.min.js"></script>
<script src="/<?= $url[0]; ?>/<?= $url[1]; ?>/<?= $url[2]; ?>/dados.json?idoferta=<?= $_GET["idoferta"]; ?>&amp;idcurso=<?= $_GET["idcurso"]; ?>&amp;idestado=<?= $_GET["idestado"]; ?>" type="text/javascript"></script>
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
<script src="/assets/plugins/maps/markerclusterer_compiled.js"></script>
<script>

var map, mapOptions, data, pos;
var markerClusterer = null;
var imageUrl = 'http://chart.apis.google.com/chart?cht=mm&chs=24x32&chco=FFFFFF,008CFF,000000&ext=.png';
var options = {positions: {}};

function initialize(){

    if(navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(
            
            function(position) {
                pos = position.coords;
            },

            function() {
                handleNoGeolocation(true);
            });

    } else {
        handleNoGeolocation(false);
    }

    var coords = {};

    if (pos) { 
        coords.latitude  = position.coords.latitude;
        coords.longitude = position.coords.longitude;
    } else {
        if(data.matriculas.length > 0) {
          coords.latitude  = data.matriculas[0].latitude;
          coords.longitude = data.matriculas[0].longitude;
        } else {
          coords.latitude  = -8.754795;
          coords.longitude = -52.910156;
        }
        
    }

    mapOptions = {
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: new google.maps.LatLng(coords.latitude, coords.longitude),
        hue: '#002BFF'
    };

    map = new google.maps.Map(
        document.getElementById('mapCanvas'), mapOptions
    );


    if (markerClusterer) {
      markerClusterer.clearMarkers();
    }

    markers = [];
    markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(24, 32));

 
    for (var i in data.matriculas) {

        latLng = new google.maps.LatLng(
            data.matriculas[i].latitude,
            data.matriculas[i].longitude
        );
        
        marker = new google.maps.Marker({
            position: latLng,
            draggable: true,
            icon: markerImage,
            title: 'Aluno: ' + data.matriculas[i].aluno
        });


        markers.push(marker);
    }

    var zoom = 12;
    var size = 20;

    markerClusterer = new MarkerClusterer(map, markers, {
      maxZoom: zoom,
      gridSize: size
    });
}

function handleNoGeolocation(errorFlag) {
    if(errorFlag){
        map.setCenter(options.position);
    }
}

function clearClusters(e) {
   e.preventDefault();
   e.stopPropagation();
   markerClusterer.clearMarkers();
}


$(document).ready(function() {

    $( "#linkFiltros" ).click(function() {
      $("#divFiltro").toggle("slow");
    });

    $( '#dibBtnFull' ).click(function(){
        if(1 === $(this).html().indexOf('Aumentar')){
            $(this).html(' Diminuir o mapa <i class="icon-fullscreen"></i> ');
        } else {
            $(this).html(' Aumentar o mapa <i class="icon-fullscreen"></i> ');
        }
    });
});

google.maps.event.addDomListener(window, 'load', initialize);

</script>

</head>
<body>
<div id="divFiltro" style="background:#F4F4F4;">
<form name="formFiltro" method="get">
  <fieldset>
    <legend><?= $idioma["filtros"]; ?></legend>
    <span style="color:#FF0000" id="mensagem"><?php if(!$_GET) { echo 'Selecione um filtro para visualizar<br />as matrículas.<br /><br />'; } ?></span>
    <label><?= $idioma["oferta"]; ?></label>
    <select name="idoferta">
    <option value=""><?= $idioma["todos"]; ?></option>
    <? foreach($ofertasArray as $ind => $val) { ?>
    	<option value="<?= $val["idoferta"]; ?>"<? if($_GET["idoferta"] == $val["idoferta"]) { ?> selected="selected"<? } ?>><?= $val["nome"]; ?></option>
    <? } ?>    
    </select>
    <label><?= $idioma["curso"]; ?></label>
    <select name="idcurso">
    <option value=""><?= $idioma["todos"]; ?></option>
    <? foreach($cursosArray as $ind => $val) { ?>
    	<option value="<?= $val["idcurso"]; ?>"<? if($_GET["idcurso"] == $val["idcurso"]) { ?> selected="selected"<? } ?>><?= $val["nome"]; ?></option>
    <? } ?> 
    </select>  
    <label><?= $idioma["estado"]; ?></label>
    <select name="idestado">
    <option value=""><?= $idioma["todos"]; ?></option>
    <? foreach($estadosArray as $ind => $val) { ?>
    	<option value="<?= $val["idestado"]; ?>"<? if($_GET["idestado"] == $val["idestado"]) { ?> selected="selected"<? } ?>><?= $val["nome"]; ?></option>
    <? } ?> 
    </select> 
    <!--   
    <label>Cidade</label>
    <select name="oferta">
    	<option value="123">Valor</option>
    </select> 
    -->
    <br />    
    <button type="submit" class="btn"><?= $idioma["filtrar"]; ?></button>
  </fieldset>
</form>
</div>
<div id="divBotao">
  <a id="linkFiltros" href="javascript:void(0)" class="btn btn-warning">Opções de filtros</a>
</div>
<div id="mapCanvas"></div>
<script type="text/javascript">
  if(data.matriculas.length > 0) {
    document.getElementById('divFiltro').style.display = 'none';
  } else {
    <?php if(!$_GET['idoferta'] && !$_GET['idcurso'] && !$_GET['idestado']) { ?>
      var mensagem = 'Selecione um filtro para visualizar<br />as matrículas.<br /><br />'; 
    <?php } else { ?>
      var mensagem = 'Nenhuma matrícula encontrada!<br /><br />'; 
    <?php } ?>
    document.getElementById('mensagem').innerHTML = mensagem;
  }
</script>
</body>
</html>