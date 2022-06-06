<?php

class Template_seo {
	
	var $page;
	var $title = "Stores Locator Admin";
	var $description = "";
	
	
	function setDefinedMetaValues() {
		
		/*
		//contact
		if($this->page=="contact") {
			$this->title = "Contact us";
			$this->description = "Contact us";
		}
		//team
		elseif($this->page=="team") {
			$this->title = "Team";
			$this->description = "Team";
		}
		*/
	}
	
	/*
	START DEFAULT FUNCTIONS
	Not to be modified
	*/
	
	function Template_seo($criteria=array()) {
		if($criteria['page']!='') $this->page = $criteria['page'];
		if($criteria['title']!='') $this->title = $criteria['title'];
		if($criteria['description']!='') $this->description = $criteria['description'];
		
		//defined meta value
		if($this->page!='') {
			$this->setDefinedMetaValues();
			$this->formatMetaTagsContent();
		}
	}
	
	function displaySeoMetaTags() {
		echo '<title>'.$this->title.'</title>'."\n";
		echo '<meta name="description" content="'.$this->description.'">'."\n";
	}
	
	function formatMetaTagsContent() {
		$this->title = ucfirst($this->title);
		$this->description = ucfirst(substr(strip_tags($this->description),0,160));
	}
	
}

?>