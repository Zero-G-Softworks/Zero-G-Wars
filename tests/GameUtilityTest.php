<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

use ZeroGWars\Utilities\GameUtilities;

class GameUtilityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNumToTile()
    {
        $this->assertEquals("A1", GameUtilities::numToTile(1,1));
        $this->assertEquals("J10", GameUtilities::numToTile(10,10));
    }
        
    public function testTileToNum()
    {
        $this->assertEquals(array(1,1), GameUtilities::tileToNum("A1"));
        $this->assertEquals(array(10,10), GameUtilities::tileToNum("J10"));
    }
    
    public function testOutOfBounds()
    {
        // edge cases
        $this->assertEquals(false, GameUtilities::isOutOfBounds("O15", "O15"));
        $this->assertEquals(false, GameUtilities::isOutOfBounds("A1", "O15"));
        
        // out of bounds, part edge cases
        $this->assertEquals(true, GameUtilities::isOutOfBounds("O16", "O15"));
        $this->assertEquals(true, GameUtilities::isOutOfBounds("P15", "O15"));
        $this->assertEquals(true, GameUtilities::isOutOfBounds("A0", "O15"));
    }
}
