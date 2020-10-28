<?php
require __DIR__.'/../src/Validation.php';

use PHPUnit\Framework\TestCase;
use Seven\Vars\Validation;

class ValidationTest extends TestCase
{
    public function setUp(): void{
        $data = [
            'name' => 'Random 1',
            'email' => 'random1@mail.com',
            'password' => 'pa33w0rd',
            'site' => 'hybeexchange.com',
            'age' => 24, 'day' => 24,
            'nickname' => 'dick & harry'
        ];
        $this->validation = new Validation($data);
    }

    public function testRequiredRule()
    {
        $this->validation->rules([
            'name' => [ 'required' => true ]
        ]);

        $this->assertTrue( $this->validation->passed() );
    }

    public function testEmailAndUrlRules()
    {
        $this->validation->rules([
            'email' => [ 'email' => true ]
        ]);
        $this->assertTrue( $this->validation->passed() );

        $this->validation->rules([
            'site' => ['url' => true]
        ]);
        $this->assertTrue( $this->validation->passed() );
    }

    public function testLesserAndGreaterThanValidatorRule()
    {
        $this->validation->rules([
            'age' => [ 'gt' => 21, 'lt' => 27 ]
        ]);

        $this->assertTrue( $this->validation->passed() );
    }

    public function testLengthRule()
    {
        $this->validation->rules([
            'password' => [ 'min' => 8, 'max' => 26, 'len' => 8  ]
        ]);

        $this->assertTrue( $this->validation->passed() );
    }


    public function testGeneralRules()
    {
        $this->validation->rules([
            'age' => [ 'is' => 24, 'same' => 'day' ]
        ]);

        $this->assertTrue( $this->validation->passed() );
    }
}