<?php
namespace happy\WebBundle\Util;

use Symfony\Component\Yaml\Parser;

/**
 * ImageGod
 *
 * @author tsetsee
 *
 */
class ImageGod 
{
    private $mwidth = 1000;
    private $vheight = 1000;
    private $thumbsizes = null;
    private $thumbfolder = null;
    private $rootfolder =  '/';

    const IMG_LANDSCAPE = 1;
    const IMG_PORTRAIT = 2;
    const IMG_LANDSCAPE_PORTRAIT = 3;

    public function __construct($rootfolder = null, $thumbfolder = null, $thumbsizes = null) {
        $this->rootfolder = $rootfolder;
        $this->thumbsizes = $thumbsizes;
        $this->thumbfolder = $thumbfolder;
    }

	/**
     * Өгөгдсөн зургийг maxWidth, maxHeight хэмжээнд багтахаар хэмжээг өөрчилнө.
     * Зөвхөн PNG/GIF/JPG зураг дэмжинэ
     * Алдааны кодууд:
     * 1: $rawImagePath-ийн хэмжээг авч чадахгүй байна
     * 2: $rawImagePath зургийн төрөл нь GIF/PNG/JPG биш байна 
     * 3: Бусад
     *
	 * @param string $rawImagePath
	 * @param string $resizedImagePath
	 * @param number $maxWidth
	 * @param number $maxHeight
	 * @param number $percent = 90
	 * @return integer
	 */
	public function resizeImageToMax($rawImagePath, $resizedImagePath, $maxWidth = null, $maxHeight = null, $percent = 90)
	{
        if($maxWidth === null)
            $maxWidth = $this->mwidth;
        if($maxHeight === null)
            $maxHeight = $this->vheight;

        $img_type = exif_imagetype($rawImagePath);
		list($width, $height) = $this->getWidthHeight($rawImagePath);

		if($width == -1)
			return 1;

        switch($img_type) {
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($rawImagePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($rawImagePath);
                break;
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($rawImagePath);
                break;
            default: return 2;
        }
		
		$newheight = $height;
		$newwidth = $width;
		if($width > $height && $width > $maxWidth)
		{
			$newheight = $maxWidth * $height /$width  ;
			$newwidth = $maxWidth;
		} else if($width <= $height && $height > $maxHeight)
		{
			$newwidth =  $maxHeight * $width / $height ;
			$newheight = $maxHeight;
		}
		$thumb = @imagecreatetruecolor($newwidth, $newheight);

        switch($img_type) {
            case IMAGETYPE_GIF:
                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);

                break;
            case IMAGETYPE_PNG:

                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);

                break;
        }
		
        if(!$thumb) {
            return 3;
        }
		
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		if(!file_exists(pathinfo($resizedImagePath, PATHINFO_DIRNAME)))
		{
			mkdir(pathinfo($resizedImagePath, PATHINFO_DIRNAME), 0755, true);
		}
   
        switch($img_type) {
            case IMAGETYPE_GIF:
                $success = imagegif($thumb, $resizedImagePath);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($thumb, $resizedImagePath, 9);
                break;
            case IMAGETYPE_JPEG:
                $success = imagejpeg($thumb, $resizedImagePath, $percent);
                break;
            default: return 2;
        }
		imagedestroy($source);
		imagedestroy($thumb);

	    if($success === false)
            return 3;    
		
		return 0;
	}

	/**
     * Өгөгдсөн зургийг maxWidth хэмжээнд багтахаар хэмжээг өөрчилнө.
     *
     * Зөвхөн PNG/GIF/JPG зураг дэмжинэ
     * Алдааны кодууд:
     * 1: $rawImagePath-ийн хэмжээг авч чадахгүй байна
     * 2: $rawImagePath зургийн төрөл нь GIF/PNG/JPG биш байна 
     * 3: Бусад
     *
	 * @param string $rawImagePath
	 * @param string $resizedImagePath
	 * @param number $maxWidth
	 * @param number $percent = 90
	 * @return integer
	 */
	public function resizeImageToMaxOnlyWidth($rawImagePath, $resizedImagePath, $maxWidth, $percent = 90)
	{
		list($width, $height) = $this->getWidthHeight($rawImagePath);
		if($width == -1)
			return 1;

        $img_type = exif_imagetype($rawImagePath);

        switch($img_type) {
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($rawImagePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($rawImagePath);
                break;
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($rawImagePath);
                break;
            default: return 2;
        }

		$newheight = $height;
		$newwidth = $width;
		if($width > $maxWidth)
		{
			$newheight = $maxWidth * $height /$width  ;
			$newwidth = $maxWidth;
		}
		$thumb = imagecreatetruecolor($newwidth, $newheight);

        switch($img_type) {
            case IMAGETYPE_GIF:
                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);

                break;
            case IMAGETYPE_PNG:

                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);

                break;
        }

		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		if(!file_exists(pathinfo($resizedImagePath, PATHINFO_DIRNAME)))
		{
			mkdir(pathinfo($resizedImagePath, PATHINFO_DIRNAME), 0755, true);
		}
        switch($img_type) {
            case IMAGETYPE_GIF:
                $success = imagegif($thumb, $resizedImagePath);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($thumb, $resizedImagePath, 9);
                break;
            case IMAGETYPE_JPEG:
                $success = imagejpeg($thumb, $resizedImagePath, $percent);
                break;
            default: return 2;
        }
		imagedestroy($source);
		imagedestroy($thumb);
		
	    if($success === false)
            return 3;    
		return 0;
	}

	/**
     * Өгөгдсөн зургийг maxHeight хэмжээнд багтахаар хэмжээг өөрчилнө.
     *
     * Зөвхөн PNG/GIF/JPG зураг дэмжинэ
     * Алдааны кодууд:
     * 1: $rawImagePath-ийн хэмжээг авч чадахгүй байна
     * 2: $rawImagePath зургийн төрөл нь GIF/PNG/JPG биш байна 
     * 3: Бусад
     *
	 * @param string $rawImagePath
	 * @param string $resizedImagePath
	 * @param number $maxWidth
	 * @param number $percent = 90
	 * @return integer
	 */
	public function resizeImageToMaxOnlyHeight($rawImagePath, $resizedImagePath, $maxHeight, $percent = 90)
	{
		list($width, $height) = $this->getWidthHeight($rawImagePath);
		if($width == -1)
			return 1;

        $img_type = exif_imagetype($rawImagePath);

        switch($img_type) {
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($rawImagePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($rawImagePath);
                break;
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($rawImagePath);
                break;
            default: return 2;
        }

		$newheight = $height;
		$newwidth = $width;
		if($height > $maxHeight)
		{
			$newwidth = $maxHeight * $width /$height  ;
			$newheight = $maxHeight;
		}
		$thumb = imagecreatetruecolor($newwidth, $newheight);

        switch($img_type) {
            case IMAGETYPE_GIF:
                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);

                break;
            case IMAGETYPE_PNG:

                $background = imagecolorallocate($thumb, 0, 0, 0);
                imagecolortransparent($thumb, $background);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);

                break;
        }

		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		if(!file_exists(pathinfo($resizedImagePath, PATHINFO_DIRNAME)))
		{
			mkdir(pathinfo($resizedImagePath, PATHINFO_DIRNAME), 0755, true);
		}
        switch($img_type) {
            case IMAGETYPE_GIF:
                $success = imagegif($thumb, $resizedImagePath);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($thumb, $resizedImagePath, 9);
                break;
            case IMAGETYPE_JPEG:
                $success = imagejpeg($thumb, $resizedImagePath, $percent);
                break;
            default: return 2;
        }
		imagedestroy($source);
		imagedestroy($thumb);
		
	    if($success === false)
            return 3;    
		return 0;
	}

	/**
	 * "дотоод" файлын систем дэх зурган файлын өндөр, өргөнийг буцаана.
	 * 
	 * 
	 * Жишээ: getWidthHeight('/resource/images/test.jpg') => [1500,600] Өргөн, Өндөр
	 * Алдаа гарсан тохиолдолд [-1,-1] буцаана
	 * 
	 * @param string $src
	 * @return multitype:number
	 */
	public function getWidthHeight($src) {
        $im = @getimagesize($src);
        if($im)
           return [$im[0], $im[1]];
        else {
           return [-1,-1];
        }
	}

    /**
     * Зургийн хэвтээ босоог шалгана
     *
     * @param string $src
     * @return integer
     */
    public function getImgOrientation($src) {
        list($width, $height) = $this->getWidthHeight($src);

        if($width > $height) {
            return $this::IMG_LANDSCAPE;
        } else if($width < $height){
            return $this::IMG_PORTRAIT;
        }

        return $this::IMG_LANDSCAPE_PORTRAIT;
    }
    /**
     * config.yml-ээс thumbsizes array-г авна
     *
     * @param string $localsrc
     * @param array $thumbsizes
     * @return integer
     */
    public function makeThumbs($localsrc, $thumbsizes = null) {
        if($thumbsizes === null) {
            if($this->thumbsizes === null)
                return 0;
        } else {
            $this->thumbsizes = $thumbsizes;
        }

        $subfolder = str_replace($this->rootfolder, '', pathinfo($localsrc, PATHINFO_DIRNAME));
        $filename = pathinfo($localsrc, PATHINFO_BASENAME);
        foreach($this->thumbsizes as $size) {
            $destfolder = $this->thumbfolder . "/" . $size . $subfolder;
            if(!(file_exists($destfolder) && is_dir($destfolder)))
                if(!@mkdir($destfolder, 0755, true))
                {
                    //folder vvsgej chadahgui bol
                }
            $destpath = $destfolder . "/" . $filename;

            if($this->getImgOrientation($localsrc) === $this::IMG_LANDSCAPE) {
                $this->resizeImageToMaxOnlyHeight($localsrc, $destpath, $size);
            } else {
                $this->resizeImageToMaxOnlyWidth($localsrc, $destpath, $size);
            }
        }
        return 0;
    }

    public function stampImage($stampurl, $rawImagePath, $stampedImagePath, $marge_right = 0, $marge_bottom = 0, $percent = 90)
    {

        list($sx, $sy) = $this->getWidthHeiht($stampurl);
        if($sx == -1)
            return false;

        $stamp = imagecreatefrompng($stampurl);
        $img_type = exif_imagetype($rawImagePath);

        switch($img_type) {
            case IMAGETYPE_GIF:
                $im = imagecreatefromgif($rawImagePath);
                break;
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($rawImagePath);
                break;
            case IMAGETYPE_JPEG:
                $im = imagecreatefromjpeg($rawImagePath);
                break;
            default: return 2;
        }

        imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

        switch($img_type) {
            case IMAGETYPE_GIF:
                $success = imagegif($im, $stampedImagePath);
                break;
            case IMAGETYPE_PNG:
                $success = imagepng($im, $stampedImagePath, 9);
                break;
            case IMAGETYPE_JPEG:
                $success = imagejpeg($im, $stampedImagePath, $percent);
                break;
            default: return 2;
        }

        imagedestroy($im);
        imagedestroy($stamp);
        if($success === false)
            return 3;

        return true;
    }

    /**
     * "дотоод" файлын систем дэх зурган файлын өндөр, өргөнийг буцаана.
     *
     *
     * Жишээ: getWidthHeight('/resource/images/test.jpg') => [1500,600] Өргөн, Өндөр
     * Алдаа гарсан тохиолдолд [-1,-1] буцаана
     *
     * @param string $src
     * @return multitype:number
     */
    public function getWidthHeiht($src) {
        $im = @getimagesize($src);
        if($im)
            return [$im[0], $im[1]];
        else {


            return [-1,-1];
        }
    }
}
