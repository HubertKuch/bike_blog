<?php

namespace Hubert\BikeBlog\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Hubert\BikeBlog\Utils\HTMLTag;
use Hubert\BikeBlog\Utils\CustomHTMLTagsParser;

class CustomHTMLParserTest extends TestCase {

    public function testHTMLTagToString() {
        $tag = "meter";
        $expected = "<meter/>";

        self::assertSame((new HTMLTag($tag))->getHTML(), $expected);
    }

    public function testParsingTag() {
        $tag = new HTMLTag("meter");
        $parser = new CustomHTMLTagsParser();
        $toReplace = "<div>test</div>";
        $content = "<div>TEST<meter/></div>";
        $expected = "<div>TEST<div>test</div></div>";

        self::assertSame($expected, $parser->parseTag($tag, $toReplace, $content));
    }
}
