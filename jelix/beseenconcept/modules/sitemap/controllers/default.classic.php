<?php
/**
* @package   beseenconcept
* @subpackage sitemap
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {

        $rep = $this->getResponse('sitemap');
        $rep->importFromUrlsXml();

        return $rep;
    }

    function pingEngines() {


		$sitemapurl = jUrl::get('sitemap~default:index');
		$url = 'http'. (empty($_SERVER['HTTPS']) ? '' : 's') .'://'. $_SERVER['HTTP_HOST'] . urlencode($sitemapurl);

		// google
		$rep->ping('http://www.google.com/webmasters/tools/ping?sitemap=' . $url);

		// yahoo
		$rep->ping('http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url=' . $url);

		// Live
		$rep->ping('http://webmaster.live.com/ping.aspx?siteMap='.$url);

		// Ask
		$rep->ping('http://submissions.ask.com/ping?sitemap=' . $url);
    }
}

