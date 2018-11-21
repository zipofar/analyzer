
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
}
