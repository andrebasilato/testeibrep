<?php

class Template_engine {
	
	var $htmlOpener='';
	var $jsFilesHeader='';
	var $cssFilesHeader='';
	var $jsFilesFooter='';
	var $cssFilesFooter='';
	var $jsOnReady='';
	var $title_meta_tag='';
	var $description_meta_tag='';
	var $page_meta_tag; //used in template_seo class => define type of page
	var $googleAnalyticsCode='';
	
	function Template_engine() {}
	
	function setMetaTags($criteria=array()) {
		$this->title_meta_tag = $criteria['title'];
		$this->description_meta_tag = $criteria['description'];
		$this->page_meta_tag = $criteria['page'];
	}
	
	function displayMetaTags() {
		$s1 = new Template_seo(array("title"=>$this->title_meta_tag, "description"=>$this->description_meta_tag, "page"=>$this->page_meta_tag));
		$s1->displaySeoMetaTags();
	}
	
	function displayHeader() {
		echo $this->htmlOpener;
		echo '<head>'."\n";
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n"; //ISO-8859-1, UTF-8
		$this->displayMetaTags();
		echo $this->cssFilesHeader;
		echo $this->jsFilesHeader;
		echo $this->displayJsOnReady();
		echo $this->displayGoogleAnalytics();
		echo '</head>'."\n";
		echo '<body>'."\n\n";
	}
	
	function setGoogleAnalyticsCode($googleAnalyticsCode) {
		$this->googleAnalyticsCode = $googleAnalyticsCode;
	}
	
	function displayGoogleAnalytics() {
		if($this->googleAnalyticsCode!='') {
		?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $this->googleAnalyticsCode; ?>']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
		<?php
		echo "\n";
		}
	}
	
	function displayJsOnReady() {
		if($this->jsOnReady!='') {
			echo '<script>'."\n";
			echo '$(document).ready(function() {'."\n";
			echo $this->jsOnReady."\n";
			echo '})'."\n";
			echo '</script>'."\n";
		}
	}
	
	function addJsOnReady($js) {
		$this->jsOnReady .= $js.' ';
	}
	
	function addJsFile($url,$position='') {
		if($position=='footer') $this->jsFilesFooter .= '<script type="text/javascript" src="'.$url.'"></script>'."\n";
		else $this->jsFilesHeader .= '<script type="text/javascript" src="'.$url.'"></script>'."\n";
	}
	
	function addCssFile($url,$position='') {
		if($position=='footer') $this->cssFilesFooter .= '<link rel="stylesheet" href="'.$url.'">'."\n";
		else $this->cssFilesHeader .= '<link rel="stylesheet" href="'.$url.'">'."\n";
	}
	
	function displayFooter() {
		echo "\n\n";
		echo $this->jsFilesFooter;
		echo $this->cssFilesFooter;
		echo '</body>'."\n";
		echo '</html>'."\n";
	}
}

?>