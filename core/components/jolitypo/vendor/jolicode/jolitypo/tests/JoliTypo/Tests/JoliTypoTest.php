<?php

/*
 * This file is part of JoliTypo - a project by JoliCode.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace JoliTypo\Tests;

use JoliTypo\Fixer;
use JoliTypo\FixerInterface;
use JoliTypo\StateBag;

class JoliTypoTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleInstance()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $this->assertInstanceOf('JoliTypo\Fixer', $fixer);

        $this->assertEquals('Coucou&hellip;', $fixer->fix('Coucou...'));
    }

    public function testSimpleInstanceRulesChange()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $this->assertInstanceOf('JoliTypo\Fixer', $fixer);

        $this->assertEquals('Coucou&hellip;', $fixer->fix('Coucou...'));

        $fixer->setRules(array('CurlyQuote'));

        $this->assertEquals('I&rsquo;m a pony.', $fixer->fix("I'm a pony."));
    }

    public function testHtmlComments()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $this->assertEquals('<p>Coucou&hellip;</p> <!-- Not Coucou... -->', $fixer->fix('<p>Coucou...</p> <!-- Not Coucou... -->'));

        // This test can't be ok, DomDocument is encoding entities even in comments (╯°□°）╯︵ ┻━┻
        //$this->assertEquals("<p>Coucou&hellip;</p> <!-- abusé -->", $fixer->fix("<p>Coucou...</p> <!-- abusé -->"));
    }

    /**
     * @expectedException \JoliTypo\Exception\BadRuleSetException
     */
    public function testBadRuleSets()
    {
        new Fixer('YOLO');
    }

    /**
     * @expectedException \JoliTypo\Exception\BadRuleSetException
     */
    public function testBadRuleSetsArray()
    {
        new Fixer(array());
    }

    /**
     * @expectedException \JoliTypo\Exception\BadRuleSetException
     */
    public function testBadRuleSetsAfterConstructor()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $fixer->setRules('YOLO');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidProtectedTags()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $fixer->setProtectedTags('YOLO');
    }

    /**
     * @expectedException \JoliTypo\Exception\BadRuleSetException
     */
    public function testInvalidCustomFixerInstance()
    {
        new Fixer(array(new FakeFixer()));
    }

    public function testOkFixer()
    {
        $fixer = new Fixer(array(new OkFixer()));

        $this->assertEquals('<p>Nope !</p>', $fixer->fix('<p>Nope !</p>'));
    }

    public function testProtectedTags()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $fixer->setProtectedTags(array('pre', 'a'));
        $fixed_content = $fixer->fix('<p>Fixed...</p> <pre>Not fixed...</pre> <p>Fixed... <a>Not Fixed...</a>.</p>');

        $this->assertEquals('<p>Fixed&hellip;</p> <pre>Not fixed...</pre> <p>Fixed&hellip; <a>Not Fixed...</a>.</p>', $fixed_content);
    }

    /**
     * @expectedException \JoliTypo\Exception\BadRuleSetException
     */
    public function testBadClassName()
    {
        new Fixer(array('Ellipsis', 'Acme\\Demo\\Fixer'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadLocale()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $fixer->setLocale(false);
    }

    /**
     * @expectedException \JoliTypo\Exception\BadRuleSetException
     */
    public function testEmptyRules()
    {
        new Fixer(array());
    }

    public function testXmlPrefixedContent()
    {
        $fixer = new Fixer(array('Ellipsis'));
        $this->assertInstanceOf('JoliTypo\Fixer', $fixer);

        $this->assertEquals('<p>Hey &eacute;pic dude&hellip;</p>', $fixer->fix('<?xml encoding="UTF-8"><body><p>Hey épic dude...</p></body>'));
        $this->assertEquals('<p>Hey &eacute;pic dude&hellip;</p>', $fixer->fix('<?xml encoding="ISO-8859-1"><body><p>Hey épic dude...</p></body>'));
    }

    public function testBadEncoding()
    {
        $fixer = new Fixer(array('Trademark'));
        $this->assertInstanceOf('JoliTypo\Fixer', $fixer);

        $this->assertEquals('Mentions L&eacute;gales', $fixer->fix(utf8_encode(utf8_decode('Mentions Légales'))));

        // JoliTypo can handle double encoded UTF-8 strings, or ISO strings, but that's not a feature.
        $isoString = mb_convert_encoding('Mentions Légales', 'ISO-8859-1', 'UTF-8');
        $this->assertEquals('Mentions L&eacute;gales', $fixer->fix(utf8_encode($isoString)));
        $this->assertEquals('Mentions L&eacute;gales', $fixer->fix($isoString));
        $this->assertEquals('Mentions L&Atilde;&copy;gales', $fixer->fix(utf8_encode(utf8_encode($isoString))));
    }

    public function testEmptyContent()
    {
        $fixer = new Fixer(array('Trademark'));
        $this->assertInstanceOf('JoliTypo\Fixer', $fixer);

        $this->assertEquals('', $fixer->fix(''));
        $this->assertEquals("\n ", $fixer->fix("\n "));
        $this->assertEquals('some content &reg;', $fixer->fix("\n some content (r)"));
    }

    public function testNonHTMLContent()
    {
        $fixer = new Fixer(array('Trademark', 'SmartQuotes'));
        $this->assertInstanceOf('JoliTypo\Fixer', $fixer);

        $toFix = <<<NOT_HTML
I don't think "FosUserBundle" is a good idea for a complex application.

\tThat being said, it's an awesome way to get stuffs done(c) in a snap!
NOT_HTML;
        $fixed = <<<NOT_HTML
I don't think &ldquo;FosUserBundle&rdquo; is a good idea for a complex application.

\tThat being said, it's an awesome way to get stuffs done&copy; in a snap!
NOT_HTML;

        $this->assertEquals($fixed, $fixer->fix($toFix));
        $this->assertEquals(html_entity_decode($fixed, null, 'UTF-8'), $fixer->fixString($toFix));
        $this->assertEquals('Here is a “protip©”!', $fixer->fixString('Here is a "protip(c)"!'));
    }

    public function testDeprecatedFixer()
    {
        $fixer = new Fixer(array('Numeric'));
        $this->assertInstanceOf('JoliTypo\Fixer', $fixer);

        $this->assertEquals('3'.Fixer::NO_BREAK_SPACE.'€', $fixer->fixString('3 €'));
    }
}

class FakeFixer
{
}

class OkFixer implements FixerInterface
{
    public function fix($content, StateBag $stateBag = null)
    {
        return $content;
    }
}
