<?php

/*  Use With CLI-PHP
A Tool By Pwn0sec Community && Duck Research :) :D
Changing Author Name Wont Make You One :)
*/ 

error_reporting(0);

if (isset($argv[0])) {
echo "        
╔═╗┬ ┬┌┐┌╔═╗┌─┐┬─┐┌─┐┌┬┐┌─┐┌┬┐┌─┐┬─┐
╠═╝││││││╠═╝├─┤├┬┘├─┤│││├┤  │ ├┤ ├┬┘
╩  └┴┘┘└┘╩  ┴ ┴┴└─┴ ┴┴ ┴└─┘ ┴ └─┘┴└─
                                                                                 \n
 Pwn0sec Community && Duck Research\n
";

echo "\t[+] Enter Your Website Here: ";
$input = trim(fgets(STDIN, 1024));

}


	if (!file_exists("dom.php")) {
		$get = @file_get_contents("https://gist.githubusercontent.com/andripwn/f2d99f6c9330f40174171e8109d342cc/raw/6bf8c5a6af79d2cbfa5e51e7cf91d7e7a37c7927/simple_html_dom.php");
		@file_put_contents("dom.php", $get); // downloads dom.php file
	}

	require 'dom.php';
	$crawled_urls=array();
	$found_urls=array();

	function rel2abs($rel, $base){
		if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
		if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
		extract(parse_url($base));
		$path = preg_replace('#/[^/]*$#', '', $path);
		if ($rel[0] == '/') $path = '';
		$abs = "$host$path/$rel";
		$re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
		for($n=1; $n>0;$abs=preg_replace($re,'/', $abs,-1,$n)){}
		$abs=str_replace("../","",$abs);
		return $scheme.'://'.$abs;
	}

	function perfect_url($u,$b){
		$bp=parse_url($b);

		if(($bp['path']!="/" && $bp['path']!="") || $bp['path']==''){

			if($bp['scheme']==""){$scheme="http";}else{$scheme=$bp['scheme'];}
	  			$b=$scheme."://".$bp['host']."/";
			}
	 
		if(substr($u,0,2)=="//"){
			$u="http:".$u;
		}

	 	if(substr($u,0,4)!="http"){
	  		$u=rel2abs($u,$b);
	 	}

	return $u;
	
	}
	
	function crawl_site($u){
		global $crawled_urls;
		$uen=urlencode($u);

		if((array_key_exists($uen,$crawled_urls)==0 || $crawled_urls[$uen] < date("YmdHis",strtotime('-25 seconds', time())))){

			$html = file_get_html($u);
	  		$crawled_urls[$uen]=date("YmdHis");

	  		foreach($html->find("a") as $li){

	   			$url=perfect_url($li->href,$u);
	   			$enurl=urlencode($url);

	   			if($url!='' && substr($url,0,4)!="mail" && substr($url,0,4)!="java" && array_key_exists($enurl,$found_urls)==0){
	    		$found_urls[$enurl]=1;

		    	if (preg_match('/=/',$url)){
					echo $url."\n";}
	    		}

	   		}
	  	}
	}


	

	echo "\nResult:\n";
	crawl_site($input);
	echo "\n\t[\$] Work Done :D \n\t\t~ Pwn0sec Community\n\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////

?>
