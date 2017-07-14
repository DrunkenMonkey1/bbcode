<?php
/**
 * Created by PhpStorm.
 * User: Genert Org
 * Date: 13/07/2017
 * Time: 13:42
 */

use PHPUnit\Framework\TestCase;
use Genert\BBCode\BBCode;

class ParserTest extends TestCase {
    public function testParser() {
        $bbCode = new BBCode();

        $this->assertNotNull($bbCode);
    }

    public function testHtmlReturnsCorrectBBCode() {
        $bbCode = new BBCode();
        $input = '
            <strong>bold</strong>
            <i>italic</i>
            <u>underline</u>
            <s>line through</s>
            <blockquote>quote</blockquote>
            <blockquote><small>golonka</small>quote</blockquote>
            <a href="http://www.example.com">http://www.example.com</a>
            <a href="http://www.example.com">example.com</a>
            <img src="http://example.com/logo.png">
            <ol>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
            </ol>
            <code><?php echo \'Hello World\'; ?></code>
            <iframe width="560" height="315" src="//www.youtube.com/embed/pnGJXuHDoYw" frameborder="0" allowfullscreen></iframe>
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
            </ul>
            <table>
              <tr>
                <td>table 1</td>
                <td>table 2</td>
              </tr>
              <tr>
                <td>table 3</td>
                <td>table 4</td>
              </tr>
            </table>
        ';

        $output = '
            [b]bold[/b]
            [i]italic[/i]
            [u]underline[/u]
            [s]line through[/s]
            [quote]quote[/quote]
            [quote][small]golonka[/small]quote[/quote]
            [url=http://www.example.com]http://www.example.com[/url]
            [url=http://www.example.com]example.com[/url]
            [img]http://example.com/logo.png[/img]
            [list=1]
                [*]Item 1
                [*]Item 2
                [*]Item 3
            [/list]
            [code]<?php echo \'Hello World\'; ?>[/code]
            [youtube]pnGJXuHDoYw[/youtube]
            [list]
                [*]Item 1
                [*]Item 2
                [*]Item 3
            [/list]
            [table]
              [tr]
                [td]table 1[/td]
                [td]table 2[/td]
              [/tr]
              [tr]
                [td]table 3[/td]
                [td]table 4[/td]
              [/tr]
            [/table]
        ';

        $this->assertEquals($output, $bbCode->convertFromHtml($input));
    }

    public function testBBCodeReturnsCorrectHtml() {
        $bbCode = new BBCode();

        $input = '
            [b]bold[/b]
            [i]italic[/i]
            [u]underline[/u]
            [s]line through[/s]
            [quote]quote[/quote]
            [quote][small]golonka[/small]quote[/quote]
            [url=http://www.example.com]http://www.example.com[/url]
            [url=http://www.example.com]example.com[/url]
            [img]http://example.com/logo.png[/img]
            [list=1]
                [*]Item 1
                [*]Item 2
                [*]Item 3
            [/list]
            [code]<?php echo \'Hello World\'; ?>[/code]
            [youtube]pnGJXuHDoYw[/youtube]
            [list]
                [*]Item 1
                [*]Item 2
                [*]Item 3
            [/list]
        ';

        $output = '
            <strong>bold</strong>
            <i>italic</i>
            <u>underline</u>
            <s>line through</s>
            <blockquote>quote</blockquote>
            <blockquote><small>golonka</small>quote</blockquote>
            <a href="http://www.example.com">http://www.example.com</a>
            <a href="http://www.example.com">example.com</a>
            <img src="http://example.com/logo.png">
            <ol>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
            </ol>
            <code><?php echo \'Hello World\'; ?></code>
            <iframe width="560" height="315" src="//www.youtube.com/embed/pnGJXuHDoYw" frameborder="0" allowfullscreen></iframe>
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
            </ul>
        ';

        $this->assertEquals($output, $bbCode->convertToHtml($input));
    }

    public function testCaseSensitivity()
    {
        $bbCode = new BBCode();
        $input = $bbCode->convertToHtml('[B][I][U]Ran[b]d[/b]om text[/u][/I][/b]', BBCode::CASE_SENSITIVE);
        $output = '<strong><i><u>Ran<strong>d</strong>om text</u></i></strong>';

        $this->assertEquals(
            $input,
            $output
        );
    }

    public function testRemovalOfBBCodeTags()
    {
        $bbCode = new BBCode();

        $this->assertEquals(
            $bbCode->stripBBCodeTags('[u]What is this[/u]'),
            'What is this'
        );

        $this->assertEquals(
            $bbCode->stripBBCodeTags('Lo[b]rem[/b] dol[url=http://yolo.com]o[/url]r sit a[s]me[/s]t!'),
            'Lorem dolor sit amet!'
        );
    }

    public function testOnlyFunctionality()
    {
        $bbCode = new BBCode();

        $this->assertEquals(
            $bbCode->only('bold')->convertToHtml('[b]Bold[/b] [i]italic[/i]'),
            '<strong>Bold</strong> [i]italic[/i]'
        );

    }
}
