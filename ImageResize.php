<?php
class ImageResize
{
    private $path;
    private $filename;
    private $extension;
    private $width;
    private $height;

    private $rawResource;
    private $newResource;

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

$a = new ImageResize('test.jpg',170,128);
$a->save('a.png');