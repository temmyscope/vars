<?php
require __DIR__.'/../src/Strings.php';

use PHPUnit\Framework\TestCase;
use Seven\Vars\Strings;

class StringsTest extends TestCase
{
    public function testStringEncryption()
    {
        $string = Strings::init('aes-256-cfb', 'a3M5w/bnPIrWs889BSQ==ZnM', 'uosL_7pM-5qU_c4S');
        $encryptedStr = $string->encrypt('random');
        $decryptedStr = $string->decrypt($encryptedStr);

        $this->assertSame('random', $decryptedStr);
    }

    public function testStringContainsAndPositions()
    {
    	$this->assertTrue(Strings::startsWith('Test word', ['word', 'Test'], false));

    	$this->assertTrue(Strings::startsWith('Test word', 'Test', true));

    	$this->assertTrue(Strings::endsWith('Test word', 'word', false));

    	$this->assertTrue(Strings::endsWith('Test word', ['word'], true));

    	$this->assertTrue(Strings::contains('Test word', 'word'));

    	$this->assertTrue(Strings::contains('Test word', 'word', false));

    	$this->assertSame(' Test ', Strings::between('This is a sample Test word', 'sample', 'Word'));

    	$this->assertSame(' Test ', Strings::between('This is a sample Test word', 'sample', 'word', false));
    }

    public function testStringSanity()
    {
    	$this->assertTrue( Strings::isVerysafe('a32dg') ); #test
    	$this->assertTrue( Strings::isXSafe('a32dg') ); #test for extreme sanity
    	$this->assertTrue( Strings::isSafe('a 32 dog') );
    	
    	$this->assertTrue( ('A32DG' === Strings::toUpper('a32dg')) );
    	$this->assertTrue( 'a32dg' === Strings::toLower('a32dg') );
    }

    public function testRandomStringGenerator()
    {	
    	$this->assertTrue( (Strings::rand(32) !== Strings::rand(32)) );
    	$this->assertTrue( Strings::randToken() !== Strings::randToken() );
    	$this->assertSame(128, strlen(Strings::fixedLengthToken(128)) );
    }
}