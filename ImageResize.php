<?php
/**
 * Copyright (c) 2019 PHP-QuickORM ImageResize
 * Author: Rytia Leung
 * Email: Rytia@Outlook.com
 * Github: github.com/php-quickorm/ImageResize
 */

class ImageResize
{
    private $path;
    private $filename;
    private $extension;
    private $width;
    private $height;

    private $rawResource;
    private $newResource;

    /**
     * ImageResize constructor.
     * @param string $path
     * @param int $width
     * @param int $height
     * @param string $sourceFormat
     */
    public function __construct($path, $width = 170, $height = 128, $sourceFormat = null)
    {

        // File and new size
        $this->path = $path;
        $this->width = $width;
        $this->height = $height;

        if (is_null($sourceFormat)) {
            list($this->filename,$this->extension) = explode(".",$path);
        } else {
            $this->extension = $sourceFormat;
        }

        if(!in_array($this->extension,['png', 'jpg', 'gif'])){
            trigger_error('no supported format.');
        }

        $this->generate();
    }

    /**
     * Generate image
     */
    private function generate(){
        // Get new sizes
        list($oldWidth, $oldHeight) = getimagesize($this->path);

        $condition = $oldWidth / $oldHeight;
        $resizeWidthPercent = $this->width / $oldWidth;
        $resizeHeightPercent = $this->height / $oldHeight;

        $offsetHeight = 0;
        $offsetWidth = 0;

        if ($condition  < ($this->width / $this->height)){
            $offsetHeight = ( $resizeWidthPercent * $oldHeight - $this->height ) * 0.5 / ($resizeWidthPercent);
        } else {
            $offsetWidth = ( $resizeHeightPercent * $oldWidth - $this->width ) * 0.5 / ($resizeHeightPercent);
        }

        // Load
        $this->newResource = imagecreatetruecolor($this->width, $this->height);


        switch ($this->extension){
            case 'jpg' : $this->rawResource = imagecreatefromjpeg($this->path); break;
            case 'png' : $this->rawResource = imagecreatefrompng($this->path); break;
            case 'gif' : $this->rawResource = imagecreatefromgif($this->path); break;
        }

        // Resize
        imagecopyresized($this->newResource, $this->rawResource, 0, 0, $offsetWidth, $offsetHeight, $this->width, $this->height, $oldWidth - 2 * $offsetWidth, $oldHeight - 2 * $offsetHeight);

    }

    /**
     * Add water mark to image
     * @param string $path
     * @param int $width
     * @param int $height
     * @param string $positionX
     * @param string $positionY
     * @param int $margin
     */
    public function addImageMark($path, $width = 50, $height = 50, $positionX = "right", $positionY = "bottom", $margin = 10){

        $im = (new ImageResize($path, $width, $height))->getImageResource();

        if (strtolower($positionX) == "right"){
            $dstX = $this->width - $width - $margin;
        } else {
            $dstX = $margin;
        }

        if (strtolower($positionY) == "bottom"){
            $dstY = $this->height - $height - $margin;
        } else {
            $dstY = $margin;
        }

        imagecopy($this->newResource, $im, $dstX, $dstY, 0, 0, $width, $height);

    }

    /**
     * Add text to image
     * @param string $text
     * @param int $fontSize
     * @param string $color
     * @param string $positionX
     * @param string $positionY
     * @param int $margin
     */
    public function addTextMark($text, $fontSize = 5, $color = "#0000ff", $positionX = "right", $positionY = "bottom", $margin = 10){

        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);

        if (strtolower($positionX) == "right"){
            $dstX = $this->width - $textWidth - $margin;
        } else {
            $dstX = $margin;
        }

        if (strtolower($positionY) == "bottom"){
            $dstY = $this->height - $textHeight - $margin;
        } else {
            $dstY = $margin;
        }

        $textColor = imagecolorallocate($this->newResource, hexdec($color[1].$color[2]), hexdec($color[3].$color[4]), hexdec($color[5].$color[6]));

        imagestring ( $this->newResource , $fontSize, $dstX , $dstY , $text , $textColor );
    }

    /**
     * Get PHP GD resource
     * @return resource
     */
    public function getImageResource()
    {
        return $this->newResource;
    }


    /**
     * Save the image as file
     * @param string $path
     * @param null $format
     */
    public function save($path, $format = null){

        if (is_null($format)) {
            list($filename,$extension) = explode(".",$path);
        } else {
            $extension = $format;
        }

        if(!in_array($extension,['png', 'jpg', 'gif'])){
            trigger_error('no supported format.');
        }
        switch ($extension){
            case 'jpg' :
                imagejpeg($this->newResource, $path);
                break;
            case 'png' :
                imagepng($this->newResource, $path);
                break;
            case 'gif' :
                imagegif($this->newResource, $path);
                break;
        }

    }

    /**
     * Send HTTP Response
     */
    public function render(){
        switch ($this->extension){
            case 'jpg' :
                header('Content-Type: image/jpeg');
                imagejpeg($this->newResource);
                break;
            case 'png' :
                header('Content-Type: image/png');
                imagepng($this->newResource);
                break;
            case 'gif' :
                header('Content-Type: image/gif');
                imagegif($this->newResource);
                break;
        }
    }
}