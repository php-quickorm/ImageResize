# PHP ImageResize Library
## What is it
A library implemented by PHP to **resize image smartly** and add water mark.

This library will help you resize the image according to the width and height defined and cut smartly to promise the length-to-width ratio, and add water mark in the mean time.

## Requirements
- PHP 5 +
- PHP GD
- composer
> Please assure that you have installed php-gd, and install it via `apt install php7.0-gd` or `apt install php5-gd` is recommended.

## Installation
Here are two ways to install:
- Using composer:
    ```
    composer require php-quickorm/image-resize
    ```

- or download the `ImageResize.php` to your project and import it by `require "ImageResize.php";`

## Usage

### Resize

```php
// Resize the firework.jpg to 800x600
$a = new ImageResize(realpath('firework.jpg'),800,600);

// Save to firework2.png;
$a->save("firework.png");

// or send as HTTP response
$a->render();
```

### Water Mark

```php
// Resize the firework.jpg to 800x600
$a = new ImageResize(realpath('firework.jpg'),800,600);

/*
* Add text to image
* The font size is 1 - 5 and the position only supports bottom-left, bottom-right, top-left, top-right 
*/

// use font size 5, color #ffffff and place at bottom-right with margin 10px
$a->addTextMark("Author:Rytia", 5, "#ffffff", "right", "bottom",10);
// use font size 4, color #ccccc and place at bottom-left with margin 15px
$a->addTextMark("Blog:www.zzfly.net", 4, "#cccccc", "left", "bottom",15);


/*
* Add water mark to image
* The position only supports bottom-left, bottom-right, top-left, top-right 
*/
$a->addImageMark(realpath('mark.jpg'),100,80,"right","bottom",50);

// Save to firework2.png;
$a->save("firework.png");

// or send as HTTP response
$a->render();

```

![img](http://wx4.sinaimg.cn/large/d3ea10bdgy1g05zo3tvfbj211e04n3z4.jpg)
