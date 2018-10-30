
<?php

use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    protected static $page;
    protected static $pageEmpty;

    public static function setUpBeforeClass()
    {
        self::$page = file_get_contents(__DIR__.'/../../../__fixtures__/page1.html');
        self::$pageEmpty = file_get_contents(__DIR__.'/../../../__fixtures__/pageEmpty.html');
    }

    public function test_getTagImg()
    {
        $expected_0 = '<img src="/lib/images/license/button/cc-by-sa.png" alt="CC Attribution-Share Alike 4.0 International">';
        $expected_6 = '<img src="/lib/exe/indexer.php?id=start&amp;1540743971" width="2" height="1" alt="">';
        $resParse = \App\Misc\Parser::getTagImg(self::$page);
        $this->assertEquals($expected_0, $resParse[0]);
        $this->assertEquals($expected_6, $resParse[6]);
    }

    public function test_getTagImg_IfNoImg()
    {
        $this->assertEquals([], \App\Misc\Parser::getTagImg(self::$pageEmpty));
    }

    public function test_getTagScript()
    {
        $expected_0 = [
            'tag' => '<script>(function(H){H.className=H.className.replace(/\bno-js\b/,\'js\')})(document.documentElement)</script>',
            'attr' => '',
            'body' => '(function(H){H.className=H.className.replace(/\bno-js\b/,\'js\')})(document.documentElement)'
        ];
        $resParse = \App\Misc\Parser::getTagScript(self::$page);
        $this->assertEquals($expected_0, $resParse[0]);
    }

    public function test_getTagScript_IfNoScript()
    {
        $this->assertEquals([], \App\Misc\Parser::getTagScript(self::$pageEmpty));
    }

    public function test_getTagLink()
    {
        $expected_0 = '<link rel="search" type="application/opensearchdescription+xml" href="/lib/exe/opensearch.php" title="AdminHelpers">';
        $expected_10 = '<link rel="apple-touch-icon" href="/lib/tpl/dokuwiki/images/apple-touch-icon.png">';
        $resParse = \App\Misc\Parser::getTagLink(self::$page);
        $this->assertEquals($expected_0, $resParse[0]);
        $this->assertEquals($expected_10, $resParse[10]);
    }

    public function test_getTagLink_IfNoLink()
    {
        $this->assertEquals([], \App\Misc\Parser::getTagLink(self::$pageEmpty));
    }

    public function test_isStyleSheetTag()
    {
        $tagTrue = '<link rel="stylesheet" type="text/css" href="/lib/exe/css.php?t=dokuwiki&amp;35ac">';
        $tagFalse = '<link rel="canonical" href="https://zipofar.ru/">';
        $this->assertTrue(\App\Misc\Parser::isStyleSheetTag($tagTrue));
        $this->assertFalse(\App\Misc\Parser::isStyleSheetTag($tagFalse));
    }

    public function test_getHrefFromTag()
    {
        $tagWith = '<link rel="stylesheet" type="text/css" href="/lib/exe/css.php?t=dokuwiki&amp;35ac">';
        $tagWithout = '<link rel="stylesheet" type="text/css">';
        $tagWithEmpty = '<link rel="stylesheet" type="text/css" href="">';
        $expected = '/lib/exe/css.php?t=dokuwiki&amp;35ac';

        $this->assertEquals($expected, \App\Misc\Parser::getHrefFromTag($tagWith));
        $this->assertEquals(null, \App\Misc\Parser::getHrefFromTag($tagWithout));
        $this->assertEquals('', \App\Misc\Parser::getHrefFromTag($tagWithEmpty));
    }

    public function test_getSrcFromTag()
    {
        $tagWith = '<script type="text/javascript" charset="utf-8" src="/lib/exe/jquery.php?tseed=23f"></script>';
        $tagWithout = '<script type="text/javascript" charset="utf-8"></script>';
        $tagWithEmpty = '<script type="text/javascript" charset="utf-8" src=""></script>';
        $expected = '/lib/exe/jquery.php?tseed=23f';

        $this->assertEquals($expected, \App\Misc\Parser::getSrcFromTag($tagWith));
        $this->assertEquals(null, \App\Misc\Parser::getSrcFromTag($tagWithout));
        $this->assertEquals('', \App\Misc\Parser::getSrcFromTag($tagWithEmpty));
    }
}
