<?php
/**
 * @author Mark Oswald <markoswald123@gmail.com>
 * @date 11.02.15 20:51
 */


/**
 * define tilesize / must be same as defined in javascript
 */
define('TILE_SIZE', 512);
/**
 * define image quality for jpeg output, 100 is best
 */
define('IMAGE_QUALITY', 100);

/**
 * Parse url to get parameters
 *
 * url will be sth. like
 * /PATH/tiles/10/271/25/10
 * so throw away first parts incl. tiles
 */
$sStr = $_SERVER['REQUEST_URI'];
$aArgs = explode("/", $sStr);
while (in_array("tiles", $aArgs)) {
    array_shift($aArgs);
}

/** @var integer $iZoom zoomlevel */
$iZoom = $aArgs[0];
/** @var integer $iTileX horizontal coordinate */
$iTileX = $aArgs[1];
/** @var integer $iTiley vertical coordinate */
$iTileY = $aArgs[2];
/** @var integer $iMaxZoom maximum zoom level */
$iMaxZoom = $aArgs[3] + 1;

/** @var  $iScaleFactor */
$iScaleFactor = $iMaxZoom - $iZoom;

/** @var integer $iRealWidth */
$iRealWidth = pow(2, $iZoom - 1);
/** @var integer $iRealHeight */
$iRealHeight = pow(2, $iZoom - 1);

/** @var resource $oImg image object for output */
$oImg = imagecreatetruecolor(TILE_SIZE, TILE_SIZE);
/** @var integer $text_color sample text color */
$text_color = imagecolorallocate($oImg, 233, 14, 91);

/**
 * cross the whole canvas for better understanding how the images are created
 */
imageline($oImg, 0, 0, TILE_SIZE, TILE_SIZE, imagecolorallocate($oImg, 50, 50, 50));
imageline($oImg, TILE_SIZE, 0, 0, TILE_SIZE, imagecolorallocate($oImg, 50, 50, 50));

/**
 * @var integer $iTilesInTile calculate how many tiles are in then current rendered image
 *                            (in a row, though the image is a square, this equals the columns)
 */
$iTilesInTile = TILE_SIZE / $iRealWidth;

/**
 * iterate through rows and columns
 */
for ($i=0;$i<$iTilesInTile;$i++) {
    for ($j=0;$j<$iTilesInTile;$j++) {
        $iOffsetX = $i * $iRealWidth;
        $iOffsetY = $j * $iRealHeight;

        /** this just shows the placement of the current image */
        imagerectangle($oImg, $iOffsetX, $iOffsetY, $iOffsetX+$iRealWidth, $iOffsetY+$iRealHeight, imagecolorallocate($oImg, rand(50,200), rand(50,200), rand(50,200)));

        /** @var integer $iLeftOffset horizontal coordinate of image which is shown in this place */
        $iLeftOffset = $iTileX * $iTilesInTile + $i;
        /** @var integer $iTopOffset vertical coordinate of image which is shown in this place */
        $iTopOffset = $iTileY * $iTilesInTile + $j;

        /** @var string $sCoordinateText this is just a sample, here we insert our images */
        $sCoordinateText = "{$iLeftOffset}/{$iTopOffset}";
        imagestring($oImg, 1, $iOffsetX + 2, $iOffsetY + 12, $sCoordinateText, $text_color);
    }
}

/** send content type */
header('Content-Type: image/jpeg');

/** output image to browser buffer, quality defined in constant */
imagejpeg($oImg, null, IMAGE_QUALITY);

/** free memory */
imagedestroy($oImg);
