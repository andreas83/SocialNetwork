<?php

/* ------------------------------------------------------------------------ */
/* class.Images.php									 						*/
/* Eine Klasse, um mit Bildern rumzuhantieren
/* A class to handle and manipulate images
/* ------------------------------------------------------------------------ */
/* Manuel Reinhard, manu@sprain.ch
/* Twitter: @sprain
/* Web: www.sprain.ch
/* ------------------------------------------------------------------------ */
/* History:
/* 2012/08/14 - Manuel Reinhard - Bugfix in rotate()
/* 2012/02/05 - Manuel Reinhard - Bugfix and simplification when cropping images
/*								  added getHTML() and improved displayHTML()
/*								  added basic test file
/* 2011/02/14 - Manuel Reinhard - added project to Github
/* 2011/01/24 - Manuel Reinhard - added parameter $jpegQuality to functions resize() and rotate()
/* 2010/11/09 - Manuel Reinhard - now uses sys_get_temp_dir() to determine default temp directory (if available),
/*								  made displayHTML() a bit more intelligent,
/*								  added alternative function rotateImage() in case imagerotate() is not available
/* 2010/07/19 - Manuel Reinhard - added watermark functions, thanks to Fernando Greiffo for the idea
/* 2010/05/06 - Manuel Reinhard - corrected the date on the change below this one
/* 2010/05/05 - Manuel Reinhard - added several minor improvements, thanks to Christian, http://hymnos.existenz.ch/
/* 2010/05/04 - Manuel Reinhard - when it all started
/* ------------------------------------------------------------------------ */


/**************************************
 * Don't change anything from here on
 * if you don't know what you're doing.
 * Otherwise the earth might disappear
 * in a large black hole. We'll blame you!
 **************************************/
class Image
{

    //Set variables
    protected $image = "";
    protected $imageInfo = array();
    protected $fileInfo = array();
    protected $tmpfile = array();
    protected $pathToTempFiles = "";
    protected $Watermark;


    /**
     * Constructor of this class
     * @param string $image (path to image)
     */
    public function __construct($image)
    {

        //Set path to temp files
        if (function_exists("sys_get_temp_dir")) {
            $this->setPathToTempFiles(sys_get_temp_dir());
        } else {
            $this->setPathToTempFiles($_SERVER["DOCUMENT_ROOT"]);
        }//if

        //Does file exist?
        if (file_exists($image)) {
            $this->image = $image;
            $this->readImageInfo();
        } else {
            throw new Exception("File does not exist: " . $image);
        }
    }//function


    /**
     * Constructor of this class
     * @param string $image (path to image)
     */
    public function __destruct()
    {
        if (file_exists($this->tmpfile)) {
            unlink($this->tmpfile);
        }//if
    }//function


    /**
     * Read and set some basic info about the image
     * @param string $image (path to image)
     */
    protected function readImageInfo()
    {

        //get data
        $data = getimagesize($this->image);

        //make readable
        $this->imageInfo["width"] = $data[0];
        $this->imageInfo["height"] = $data[1];
        $this->imageInfo["imagetype"] = $data[2];
        $this->imageInfo["htmlWidthAndHeight"] = $data[3];
        $this->imageInfo["mime"] = $data["mime"];
        $this->imageInfo["channels"] = (isset($data["channels"]) ? $data["channels"] : NULL);
        $this->imageInfo["bits"] = $data["bits"];

        return true;

    }//function


    /************************************
     * /* SETTERS
     * /************************************
     *
     * /**
     * Sets path to temp files
     * @param string $path
     */
    public function setPathToTempFiles($path)
    {

        //Set path
        $path = realpath($path) . DIRECTORY_SEPARATOR;
        $this->pathToTempFiles = $path;

        //Set tmpfile to save temporary files
        $this->tmpfile = tempnam($this->pathToTempFiles, "classImagePhp_");

        return true;
    }


    /**
     * Sets new main image
     * @param string $pathToImage
     */
    protected function setNewMainImage($pathToImage)
    {

        //set now
        $this->image = $pathToImage;

        //Read new image info
        $this->readImageInfo();

        return true;

    }//function


    /************************************
     * /* ACTIONS
     * /************************************
     *
     * /**
     * Resizes an image
     * Some portions of this function as found on
     * http://www.bitrepository.com/resize-an-image-keeping-its-aspect-ratio-using-php-and-gd.html
     * @param int $max_width
     * @param int $max_height
     * @param string $method
     *                 fit = Fits image into width and height while keeping original aspect ratio. Expect your image not to use the full area.
     *                 crop = Crops image to fill the area while keeping original aspect ratio. Expect your image to get, well, cropped.
     *               fill = Fits image into the area without taking care of any ratios. Expect your image to get deformed.
     *
     * @param string $cropAreaLeftRight
     *                 l = left
     *               c = center
     *               r = right
     *
     * @param string $cropAreaBottomTop
     *                 t = top
     *               c = center
     *               b = bottom
     */
    public function resize($max_width, $max_height, $method = "fit", $cropAreaLeftRight = "c", $cropAreaBottomTop = "c", $jpgQuality = 75)
    {

        //Get data
        $width = $this->getWidth();
        $height = $this->getHeight();
        $type = $this->getType();
        $mime = $this->getMimeType();
        $landscape = false;
        $newImage_landscape = false;
        $newImage_square = false;

        //Set new data
        $newImage_width = $max_width;
        $newImage_height = $max_height;
        $srcX = 0;
        $srcY = 0;


        //is the image landscape or portrait?
        if ($this->getRatioWidthToHeight() >= 1) {
            $landscape = "true";
        }//if


        //Get ratio of max_width : max_height
        $ratioOfMaxSizes = $max_width / $max_height;
        if ($ratioOfMaxSizes > 1) {
            $newImage_landscape = true;
        } elseif ($ratioOfMaxSizes == 1) {
            $newImage_square = true;
        }//if

        //Want to fit in the area?
        if ($method == "fit") {

            if ($ratioOfMaxSizes >= $this->getRatioWidthToHeight()) {
                $max_width = $max_height * $this->getRatioWidthToHeight();
            } else {
                $max_height = $max_width * $this->getRatioHeightToWidth();
            }//if

            //set image data again
            $newImage_width = $max_width;
            $newImage_height = $max_height;


            //or want to crop it?
        } elseif ($method == "crop") {

            //set new max height or width
            if ($ratioOfMaxSizes > $this->getRatioWidthToHeight()) {
                $max_height = $max_width * $this->getRatioHeightToWidth();
            } else {
                $max_width = $max_height * $this->getRatioWidthToHeight();
            }

            //which area to crop?
            if ($cropAreaLeftRight == "r") {
                $srcX = $width - (($newImage_width / $max_width) * $width);
            } elseif ($cropAreaLeftRight == "c") {
                $srcX = ($width / 2) - ((($newImage_width / $max_width) * $width) / 2);
            }//if//if

            if ($cropAreaBottomTop == "b") {
                $srcY = $height - (($newImage_height / $max_height) * $height);
            } elseif ($cropAreaBottomTop == "c") {
                $srcY = ($height / 2) - ((($newImage_height / $max_height) * $height) / 2);
            }//if//if

        }//if


        //set some function stuff
        list($image_create_func, $image_save_func) = $this->getFunctionNames();


        //Let's get it on, create image!
        $imageC = ImageCreateTrueColor($newImage_width, $newImage_height);
        $newImage = $image_create_func($this->image);

        if ($image_save_func == 'ImagePNG') {
            //http://www.akemapa.com/2008/07/10/php-gd-resize-transparent-image-png-gif/
            imagealphablending($imageC, false);
            imagesavealpha($imageC, true);
            $transparent = imagecolorallocatealpha($imageC, 255, 255, 255, 127);
            imagefilledrectangle($imageC, 0, 0, $newImage_width, $newImage_height, $transparent);
        }

        ImageCopyResampled($imageC, $newImage, 0, 0, $srcX, $srcY, $max_width, $max_height, $width, $height);

        //Set image
        if ($image_save_func == "imageJPG" || $image_save_func == "ImageJPEG") {
            if (!$image_save_func($imageC, $this->tmpfile, $jpgQuality)) {
                throw new Exception("Cannot save file " . $this->tmpfile);
            }//if
        } else {
            if (!$image_save_func($imageC, $this->tmpfile)) {
                throw new Exception("Cannot save file " . $this->tmpfile);
            }//if
        }//if

        //Set new main image
        $this->setNewMainImage($this->tmpfile);

        //Free memory!
        imagedestroy($imageC);

    }//function


    /**
     * Adds a watermark
     */
    public function addWatermark($imageWatermark)
    {

        //create Instance for Watermark
        $this->Watermark = new self($imageWatermark);
        $this->Watermark->setPathToTempFiles($this->pathToTempFiles);
        return $this->Watermark;

    }//function


    /**
     * Writes Watermark to the File
     * @param int $oapcity
     * @param int $marginH (margin in pixel from base image horizontally)
     * @param int $marginV (margin in pixel from base image vertically)
     *
     * @param string $positionWatermarkLeftRight
     *                 l = left
     *               c = center
     *               r = right
     *
     * @param string $positionWatermarkTopBottom
     *                 t = top
     *               c = center
     *               b = bottom
     */
    public function writeWatermark($opacity = 50, $marginH = 0, $marginV = 0, $positionWatermarkLeftRight = "c", $positionWatermarkTopBottom = "c")
    {

        //add Watermark
        list($image_create_func, $image_save_func) = $this->Watermark->getFunctionNames();
        $watermark = $image_create_func($this->Watermark->getImage());

        //get base image
        list($image_create_func, $image_save_func) = $this->getFunctionNames();
        $baseImage = $image_create_func($this->image);


        //Calculate margins

        if ($positionWatermarkLeftRight == "r") {
            $marginH = imagesx($baseImage) - imagesx($watermark) - $marginH;
        }//if

        if ($positionWatermarkLeftRight == "c") {
            $marginH = (imagesx($baseImage) / 2) - (imagesx($watermark) / 2) - $marginH;
        }//if

        if ($positionWatermarkTopBottom == "b") {
            $marginV = imagesy($baseImage) - imagesy($watermark) - $marginV;
        }//if

        if ($positionWatermarkTopBottom == "c") {
            $marginV = (imagesy($baseImage) / 2) - (imagesy($watermark) / 2) - $marginV;
        }//if


        //****************************
        //Add watermark and keep alpha channel of pngs.
        //The following lines are based on the code found on
        //http://ch.php.net/manual/en/function.imagecopymerge.php#92787
        //****************************

        // creating a cut resource
        $cut = imagecreatetruecolor(imagesx($watermark), imagesy($watermark));

        // copying that section of the background to the cut
        imagecopy($cut, $baseImage, 0, 0, $marginH, $marginV, imagesx($watermark), imagesy($watermark));

        // placing the watermark now
        imagecopy($cut, $watermark, 0, 0, 0, 0, imagesx($watermark), imagesy($watermark));
        imagecopymerge($baseImage, $cut, $marginH, $marginV, 0, 0, imagesx($watermark), imagesy($watermark), $opacity);

        //****************************
        //****************************


        //Set image
        if (!$image_save_func($baseImage, $this->tmpfile)) {
            throw new Exception("Cannot save file " . $this->tmpfile);
        }//if

        //Set new main image
        $this->setNewMainImage($this->tmpfile);

        //Free memory!
        imagedestroy($baseImage);
        unset($Watermark);

    }//function


    /**
     * Roates an image
     */
    public function rotate($degrees, $jpgQuality = 75)
    {

        //set some function stuff
        list($image_create_func, $image_save_func) = $this->getFunctionNames();

        //do it
        $source = $image_create_func($this->image);
        if (function_exists("imagerotate")) {
            $imageRotated = imagerotate($source, $degrees, 0, true);
        } else {
            $imageRotated = $this->rotateImage($source, $degrees);
        }

        if ($image_save_func == "ImageJPEG") {
            if (!$image_save_func($imageRotated, $this->tmpfile, $jpgQuality)) {
                throw new Exception("Cannot save file " . $this->tmpfile);
            }//if
        } else {
            if (!$image_save_func($imageRotated, $this->tmpfile)) {
                throw new Exception("Cannot save file " . $this->tmpfile);
            }//if
        }//if

        //Set new main image
        $this->setNewMainImage($this->tmpfile);

        return true;

    }//function


    /**
     * Sends image data to browser
     */
    public function display()
    {
        $mime = $this->getMimeType();
        header("Content-Type: " . $mime);
        readfile($this->image);
    }//function


    /**
     * Prints html code to display image
     */
    public function displayHTML($alt = false, $title = false, $class = false, $id = false, $extras = false)
    {
        print $this->getHTML($alt, $title, $class, $id, $extras);
    }//function


    /**
     * Creates html code to display image
     */
    public function getHTML($alt = false, $title = false, $class = false, $id = false, $extras = false)
    {

        //Build path
        $path = str_replace($_SERVER["DOCUMENT_ROOT"], "", $this->image);

        //Make code
        $code = '<img src="/' . $path . '" width="' . $this->getWidth() . '" height="' . $this->getHeight() . '"';
        if ($alt) {
            $code .= ' alt="' . $alt . '"';
        }
        if ($title) {
            $code .= ' title="' . $title . '"';
        }
        if ($class) {
            $code .= ' class="' . $class . '"';
        }
        if ($id) {
            $code .= ' id="' . $id . '"';
        }
        if ($extras) {
            $code .= ' ' . $extras;
        }
        $code .= ' />';

        //Output
        return $code;

    }//function


    /**
     * Saves image to file
     */
    public function save($filename, $path = "", $extension = "")
    {

        //add extension
        if ($extension == "") {
            $filename .= $this->getExtension(true);
        } else {
            $filename .= "." . $extension;
        }//if

        //add trailing slash if necessary
        if ($path != "") {
            $path = realpath($path) . DIRECTORY_SEPARATOR;
        }//if

        //create full path
        $fullPath = $path . $filename;

        //Copy file
        if (!copy($this->image, $fullPath)) {
            throw new Exception("Cannot save file " . $fullPath);
        }//if

        //Set new main image
        $this->setNewMainImage($fullPath);

        return true;

    }//function


    /************************************
     * /* CHECKERS
     * /************************************
     *
     * /**
     * Checks whether image is RGB
     * @return bool
     */
    public function isRGB()
    {
        if ($this->imageInfo["channels"] == 3) {
            return true;
        }//if
        return false;
    }//function


    /**
     * Checks whether image is RGB
     * @return bool
     */
    public function isCMYK()
    {
        if ($this->imageInfo["channels"] == 4) {
            return true;
        }//if
        return false;
    }//function

    /**
     * Checks ratio width:height
     * Examples:
     * Ratio must be 4:3 > checkRatio(4,3)
     * Ratio must be 4:3 or 3:4 > checkRatio(4,3, true)
     * @return bool
     */
    public function checkRatio($ratio1, $ratio2, $ignoreOrientation = false)
    {

        //get actual ratio
        $actualRatioWidthToHeight = $this->getRatioWidthToHeight();
        $actualRatioHeightToWidth = $this->getRatioHeightToWidth();

        //get ratio it should have
        $shouldBeRatio = $ratio1 / $ratio2;

        //does it match?
        if ($actualRatioWidthToHeight == $shouldBeRatio) {
            return true;
        }//if

        if ($ignoreOrientation == true && $actualRatioHeightToWidth == $shouldBeRatio) {
            return true;
        }//if

        return false;


    }//function


    /************************************
     * /* GETTERS
     * /************************************
     *
     *
     * /**
     * Returns function names
     */
    protected function getFunctionNames()
    {

        //set some function stuff
        switch ($this->getType()) {
            case 'jpeg':
                $image_create_func = 'ImageCreateFromJPEG';
                $image_save_func = 'ImageJPEG';
                break;

            case 'png':
                $image_create_func = 'ImageCreateFromPNG';
                $image_save_func = 'ImagePNG';
                break;

            case 'bmp':
                $image_create_func = 'ImageCreateFromBMP';
                $image_save_func = 'ImageBMP';
                break;

            case 'gif':
                $image_create_func = 'ImageCreateFromGIF';
                $image_save_func = 'ImageGIF';
                break;

            case 'vnd.wap.wbmp':
                $image_create_func = 'ImageCreateFromWBMP';
                $image_save_func = 'ImageWBMP';
                break;

            case 'xbm':
                $image_create_func = 'ImageCreateFromXBM';
                $image_save_func = 'ImageXBM';
                break;

            default:
                $image_create_func = 'ImageCreateFromJPEG';
                $image_save_func = 'ImageJPEG';
        }//switch


        return array($image_create_func, $image_save_func);

    }//function


    /**
     * returns info about the image
     */
    protected function getImage()
    {
        return $this->image;
    }//function


    /**
     * return info about the image
     */
    public function getImageInfo()
    {
        return $this->imageInfo;
    }//function

    /**
     * return info about the file
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }//function


    /**
     * Gets width of image
     * @return int
     */
    public function getWidth()
    {
        return $this->imageInfo["width"];
    }//function

    /**
     * Gets height of image
     * @return int
     */
    public function getHeight()
    {
        return $this->imageInfo["height"];
    }//function

    /**
     * Gets type of image
     * @return string
     */
    public function getExtension($withDot = false)
    {

        $extension = image_type_to_extension($this->imageInfo["imagetype"]);
        $extension = str_replace("jpeg", "jpg", $extension);
        if (!$withDot) {
            $extension = substr($extension, 1);
        }//if

        return $extension;
    }//function


    /**
     * Gets mime type of image
     * @return string
     */
    public function getMimeType()
    {
        return $this->imageInfo["mime"];
    }//function

    /**
     * Gets mime type of image
     * @return string
     */
    public function getType()
    {
        return substr(strrchr($this->imageInfo["mime"], '/'), 1);
    }//function


    /**
     * Get filesize
     * @return string
     */
    public function getFileSizeInBytes()
    {
        return filesize($this->image);
    }//function


    /**
     * Get filesize
     * @return string
     */
    public function getFileSizeInKiloBytes()
    {
        $size = $this->getFileSizeInBytes();
        return $size / 1024;
    }//function


    /**
     * Returns a human readable filesize
     * @author      wesman20 (php.net)
     * @author      Jonas John
     * @author        Manuel Reinhard
     * @version     0.3
     * @link        http://www.jonasjohn.de/snippets/php/readable-filesize.htm
     */
    public function getFileSize()
    {

        $size = $this->getFileSizeInBytes();

        // Adapted from: http://www.php.net/manual/en/function.filesize.php
        $mod = 1024;

        $units = explode(' ', 'B KB MB GB TB PB');
        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }//for

        //round differently depending on unit to use
        //(added by Manuel Reinhard)
        if ($i < 2) {
            $size = round($size);
        } else {
            $size = round($size, 2);
        }//if

        //return
        return $size . ' ' . $units[$i];
    }//function


    /**
     * Gets ratio width:height
     * @return float
     */
    public function getRatioWidthToHeight()
    {
        return $this->imageInfo["width"] / $this->imageInfo["height"];
    }//function

    /**
     * Gets ratio height:width
     * @return float
     */
    public function getRatioHeightToWidth()
    {
        return $this->imageInfo["height"] / $this->imageInfo["width"];
    }//function


    /************************************
     * /* OTHER STUFF
     * /************************************
     *
     *
     * /**
     * Replacement for imagerotate if it doesn't exist
     * As found on http://www.php.net/manual/de/function.imagerotate.php#93692
     */
    protected function rotateImage($img, $rotation)
    {
        $width = imagesx($img);
        $height = imagesy($img);
        switch ($rotation) {
            case 90:
                $newimg = @imagecreatetruecolor($height, $width);
                break;
            case 180:
                $newimg = @imagecreatetruecolor($width, $height);
                break;
            case 270:
                $newimg = @imagecreatetruecolor($height, $width);
                break;
            case 0:
                return $img;
                break;
            case 360:
                return $img;
                break;
        }

        if ($newimg) {
            for ($i = 0; $i < $width; $i++) {
                for ($j = 0; $j < $height; $j++) {
                    $reference = imagecolorat($img, $i, $j);
                    switch ($rotation) {
                        case 90:
                            if (!@imagesetpixel($newimg, ($height - 1) - $j, $i, $reference)) {
                                return false;
                            }
                            break;
                        case 180:
                            if (!@imagesetpixel($newimg, $width - $i, ($height - 1) - $j, $reference)) {
                                return false;
                            }
                            break;
                        case 270:
                            if (!@imagesetpixel($newimg, $j, $width - $i, $reference)) {
                                return false;
                            }
                            break;
                    }
                }
            }
            return $newimg;
        }
        return false;
    }//function


}//class

?>