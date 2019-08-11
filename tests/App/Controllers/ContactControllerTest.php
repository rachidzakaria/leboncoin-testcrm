<?php

namespace Tests\App\Controllers;

use PHPUnit\Framework\TestCase;

class ContactControllerTest extends TestCase
{
    function httpGet($url)
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $output=curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $info["http_code"];
    }

    public function testindex()
    {
        $this->assertEquals(302,($this->httpGet("http://localhost/testTechnique/index.php?p=contact.index")));
    }
}