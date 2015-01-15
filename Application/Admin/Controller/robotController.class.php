<?php
namespace Admin\Controller;

use Common\Controller\CommonController;

class ContentController extends CommonController
{
    public function index()
    {
    }

    public function robot()
    {
        vendor('Snoopy');
        vendor('WebCrawl');
        $url = $_POST['URL'];
        $go = new WebCrawl($url);
        header("Content-type:text/html;charset=" . $go->getCharset());
        $snoopy = new snoopy;
        $snoopy->fetchtext($url);
        if ($snoopy->fetchtext($url)) { // other methods: fetch, fetchform, fetchlinks, submittext and submitlinks
            // response code:    print "response code: ".$snoopy->response_code."<br/>/n";
            // print the headers:        print "<b>Headers:</b><br/>";
            while (list($key, $val) = each($snoopy->headers)) {
                print $key . ": " . $val . "<br/> \n";
            }
            // print the texts of the website:
            print "<pre>" . htmlspecialchars($snoopy->results) . "</pre>/n";
        } else {
            print "Snoopy: error while fetching document: " . $snoopy->error . "/n";
        }

    }
}