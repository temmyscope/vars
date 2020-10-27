<?php
require __DIR__.'/../src/Arrays.php';

use PHPUnit\Framework\TestCase;
use Seven\Vars\Arrays;

class ArraysTest extends TestCase
{
   
    public function setUp(): void{
        $data = [
            0 => [
                'name' => 'Random 1',
                'age' => 24,
                'nickname' => 'dick & harry'
            ],
            1 => [
                'name' => 'Random 2',
                'age' => 27,
                'nickname' => 'harry'
            ],
            2 => [
                'name' => 'Random 3',
                'age' => 24,
                'nickname' => 'dick'
            ],
            3 => [
                'name' => 'Random 4',
                'age' => 21,
                'nickname' => 'choudharry'
            ]
        ];
        $this->arrays = new Arrays($data);
    }

    public function testCountable()
    {
        $this->assertEquals(count($this->arrays), $this->arrays->count());
    }

    public function testOffsetCasesAndCount()
    {
        $this->arrays->offsetSet(4, [
            'name' => 'Random 5',
            'age' => 23,
            'nickname' => 'newbie'
        ]);

        $this->assertTrue($this->arrays->offsetExists(4));

        $this->assertEquals(count($this->arrays), $this->arrays->count());

        $this->assertEquals($this->arrays[4], $this->arrays->offsetGet(4));

        $this->arrays->offsetUnset(4);

        $this->assertEquals(4, $this->arrays->count());
    }

    public function testPushPopShift()
    {
        $this->arrays->add([
            'name' => 'Random 5',
            'age' => 23,
            'nickname' => 'newbie'
        ]);

        $this->assertEquals(count($this->arrays), $this->arrays->count());

        $this->arrays->addEach([
            [ 'name' => 'Random 6', 'age' => 22, 'nickname' => 'jjc' ],
            [ 'name' => 'Random 7', 'age' => 24, 'nickname' => 'johnny' ]
        ]);

        $this->assertEquals($this->arrays[4], $this->arrays->offsetGet(4));

        $this->arrays->addToEach([
            'city' => 'lagos'
        ]);

        $this->assertEquals($this->arrays[4]['city'], $this->arrays->offsetGet(4)['city']);

        $first = $this->arrays->offsetGet(0);
        $shifted = $this->arrays->shift();

        $this->assertEquals( $first, $shifted );
        $this->assertEquals( $this->arrays->count(), count($this->arrays->shiftEach()) );

        $count = $this->arrays->count();
        $popped = $this->arrays->pop();

        $this->assertEquals( count($this->arrays), $count-1);

        $total = $this->arrays->popEach();
        $this->assertEquals(5, count($total) );
    }

    public function testPositionalMethods()
    {
        $this->assertEquals( $this->arrays[0], $this->arrays->first() );
        $this->assertEquals( $this->arrays[3], $this->arrays->last() );

        $reversed = $this->arrays->reverse()->return();
        $this->assertEquals(27, $reversed[2]['age']);

        $sorted = $this->arrays->sort('age')->return();
        $this->assertEquals( 21, $sorted[0]['age']);

        $sorted = $this->arrays->downSort('age')->return();
        $this->assertEquals( 27, $sorted[0]['age']);

    }
    
}