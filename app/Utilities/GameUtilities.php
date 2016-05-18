<?php

namespace ZeroGWars\Utilities;

use ZeroGWars\Http\Controllers\Controller;
use ZeroGWars\User;
use ZeroGWars\ShipType;
use ZeroGWars\Game;

class GameUtilities
{
    /**
     * Convert a number pair to a tile address.
     *
     * @param  int  $col
     * @param  int  $row
     * @return Response
     */
    public static function numToTile($col, $row)
    {
        
        $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                           'f', 'g', 'h', 'i', 'j',
                           'k', 'l', 'm', 'n', 'o',
                           'p', 'q', 'r', 's', 't',
                           'u', 'v', 'w', 'x', 'y',
                           'z'
                         );
        return strtoupper($alphabet[$col-1]) . $row;
    }

    /**
     * Convert a tile address to a number pair.
     *
     * @param  string  $tile
     * @return Response
     */
    public static function tileToNum($tile)
    {
        $tile = strtolower($tile);
        preg_match('/([a-z]+)([0-9]+)/', $tile, $matches);
        $col = 0;
        if( count($matches) === 0 ) return [-1, -1];
        for( $i = 0; $i < strlen($matches[1]); $i++ ) {
            $charVal = intval( ord($matches[1][$i]) - 96 );
            $decimal = pow(26, strlen($matches[1])-$i-1);
            $col += $charVal * $decimal;
        }
        $row = intval($matches[2]);
        return [$col, $row];
    }
    
    public static function isOutOfBounds($tile, $limit)
    {
        list($col, $row) = GameUtilities::tileToNum($tile);
        list($maxCol, $maxRow) = GameUtilities::tileToNum($limit);
        
        if( ($col < 1 || $col > $maxCol) || ($row < 1 || $row > $maxRow) ) {
            return true;
        }
        else {
            return false;
        }
    }
}
