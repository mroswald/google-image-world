<?php
/**
 * @author Mark Oswald <markoswald123@gmail.com>
 * @date 11.02.15 20:51
 */
$sStr = $_SERVER['REQUEST_URI'];
define('TILE_SIZE', 512);

$iSkipParams = 4;
$aArgs = explode("/", $sStr);
for ($i=1;$i<$iSkipParams;$i++) {
    array_shift($aArgs);
}

$iZoom = $aArgs[0];
$iTileX = $aArgs[1];
$iTileY = $aArgs[2];
$iMaxZoom = $aArgs[3] + 1;
$iScaleFactor = $iMaxZoom - $iZoom;

function GetMapWidth($level){
    return TILE_SIZE * (int)pow(2,$level);
}

$iRealWidth = pow(2, $iZoom - 1);
$iRealHeight = pow(2, $iZoom - 1);

$im = imagecreatetruecolor(TILE_SIZE, TILE_SIZE);
$text_color = imagecolorallocate($im, 233, 14, 91);


//2 ^ ($iZoom - 1) = Coord
//
//$iCoordY =



//imagestring($im, 1, 5, 3,  "{$sStr} {$iZoom} ({$iRealWidth}x{$iRealHeight})", $text_color);
imageline($im, 0, 0, TILE_SIZE, TILE_SIZE, imagecolorallocate($im, 50, 50, 50));
imageline($im, TILE_SIZE, 0, 0, TILE_SIZE, imagecolorallocate($im, 50, 50, 50));






/*********/

$iTilesInTile = TILE_SIZE / $iRealWidth;
for ($i=0;$i<$iTilesInTile;$i++) {
    for ($j=0;$j<$iTilesInTile;$j++) {
        $iOffsetX = $i * $iRealWidth;
        $iOffsetY = $j * $iRealHeight;
        // tmp!
        imagerectangle($im, $iOffsetX, $iOffsetY, $iOffsetX+$iRealWidth, $iOffsetY+$iRealHeight, imagecolorallocate($im, rand(50,200), rand(50,200), rand(50,200)));

        // calc correct image id
        $iLeftOffset = $iTileX * $iTilesInTile + $i;
        $iTopOffset = $iTileY * $iTilesInTile + $j;
        imagestring($im, 1, $iOffsetX + 2, $iOffsetY + 12,  "{$iLeftOffset}/{$iTopOffset}", $text_color);
    }
}

/***********/












// Die Content-Type-Kopfzeile senden, in diesem Fall image/jpeg
header('Content-Type: image/jpeg');

// Das Bild ausgeben
imagejpeg($im, null, 100);

// Den Speicher freigeben
imagedestroy($im);
