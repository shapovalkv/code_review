<?php
namespace Modules\Media\Helpers;
use Illuminate\Support\Facades\Storage;

/**
 * Resize image class will allow you to resize an image
 *
 * Can resize to exact size
 * Max width size while keep aspect ratio
 * Max height size while keep aspect ratio
 * Automatic while keep aspect ratio
 */
class ResizeImage
{
    private $ext;
    private $image;
    private $newImage;
    private $origWidth;
    private $origHeight;
    private $resizeWidth;
    private $resizeHeight;
    /**
     * Class constructor requires to send through the image filename
     *
     * @param string $filename - Filename of the image you want to resize
     */
    public function __construct( $filename )
    {
        if (Storage::disk('s3')->exists($filename)) {
            $this->setImage($filename);
        } else {
            throw new \Exception('Image ' . $filename . ' can not be found, try another image.');
        }
    }
    /**
     * Set the image variable by using image create
     *
     * @param string $filename - The image filename
     */
    private function setImage( $filename )
    {
        $mime = Storage::disk('s3')->mimeType($filename);
        $this->ext = $mime;
        switch($this->ext)
        {
            // Image is a JPG
            case 'image/jpg':
            case 'image/jpeg':
                // create a jpeg extension
                $this->image = imagecreatefromjpeg(Storage::disk('s3')->url($filename));
                break;
            // Image is a GIF
            case 'image/gif':
                $this->image = @imagecreatefromgif(Storage::disk('s3')->url($filename));
                break;
            // Image is a PNG
            case 'image/png':
                $this->image = @imagecreatefrompng(Storage::disk('s3')->url($filename));
                break;
            // Mime type not found
            default:
                throw new \Exception("File is not an image, please use another file type.", 1);
        }
        $this->origWidth = imagesx($this->image);
        $this->origHeight = imagesy($this->image);
    }
    /**
     * Save the image as the image type the original image was
     *
     * @param  String[type] $savePath     - The path to store the new image
     * @param  string $imageQuality 	  - The qulaity level of image to create
     *
     * @return Saves the image
     */
    public function saveImage($savePath, $imageQuality="100", $download = false)
    {
        switch($this->ext)
        {
            case 'image/jpg':
            case 'image/jpeg':
                // Check PHP supports this file type
                if (imagetypes() & IMG_JPG) {
                    ob_start();
                    imagejpeg($this->newImage, null, $imageQuality);
                    $imageContents = ob_get_clean();
                }
                break;
            case 'image/gif':
                // Check PHP supports this file type
                if (imagetypes() & IMG_GIF) {
                    ob_start();
                    imagegif($this->newImage);
                    $imageContents = ob_get_clean();
                }
                break;
            case 'image/png':
                $invertScaleQuality = 9 - round(($imageQuality/100) * 9);
                // Check PHP supports this file type
                if (imagetypes() & IMG_PNG) {
                    ob_start();
                    imagepng($this->newImage, null, $invertScaleQuality);
                    $imageContents = ob_get_clean();
                }
                break;
        }

        if (isset($imageContents)) {
            Storage::disk('s3')->put($savePath, $imageContents);
            Storage::disk('uploads')->put($savePath, $imageContents);
        }

        if($download)
        {
            header('Content-Description: File Transfer');
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename= ".$savePath."");
            readfile($savePath);
        }
        imagedestroy($this->newImage);
    }
    /**
     * Resize the image to these set dimensions
     *
     * @param  int $width        	- Max width of the image
     * @param  int $height       	- Max height of the image
     * @param  string $resizeOption - Scale option for the image
     *
     * @return Save new image
     */
    public function resizeTo( $width, $height, $resizeOption = 'default' )
    {
        switch(strtolower($resizeOption))
        {
            case 'exact':
                $this->resizeWidth = $width;
                $this->resizeHeight = $height;
                break;
            case 'maxwidth':
                $this->resizeWidth  = $width;
                $this->resizeHeight = $this->resizeHeightByWidth($width);
                break;
            case 'maxheight':
                $this->resizeWidth  = $this->resizeWidthByHeight($height);
                $this->resizeHeight = $height;
                break;
            default:
                if($this->origWidth > $width || $this->origHeight > $height)
                {
                    if ( $this->origWidth > $this->origHeight ) {
                        $this->resizeHeight = $this->resizeHeightByWidth($width);
                        $this->resizeWidth  = $width;
                    } else if( $this->origWidth < $this->origHeight ) {
                        $this->resizeWidth  = $this->resizeWidthByHeight($height);
                        $this->resizeHeight = $height;
                    }
                } else {
                    $this->resizeWidth = $width;
                    $this->resizeHeight = $height;
                }
                break;
        }
        $this->newImage = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
        imagecopyresampled($this->newImage, $this->image, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->origWidth, $this->origHeight);
    }
    /**
     * Get the resized height from the width keeping the aspect ratio
     *
     * @param  int $width - Max image width
     *
     * @return Height keeping aspect ratio
     */
    private function resizeHeightByWidth($width)
    {
        return floor(($this->origHeight/$this->origWidth)*$width);
    }
    /**
     * Get the resized width from the height keeping the aspect ratio
     *
     * @param  int $height - Max image height
     *
     * @return Width keeping aspect ratio
     */
    private function resizeWidthByHeight($height)
    {
        return floor(($this->origWidth/$this->origHeight)*$height);
    }
}
