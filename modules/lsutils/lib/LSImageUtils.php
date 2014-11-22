<?php

useLib('LSPoint', 'lsutils');

class LSImageUtils {
    
    public static function getImageSize($path) {
        $imageInfo = getimagesize($path);
        
        if ($imageInfo === false) {
            return false;
        }
        
        return new LSPoint($imageInfo[0], $imageInfo[1]);
    }
    
    public static function createScaledImage($path, $scale, $destinationPath = null) {
        $imageInfo = getimagesize($path);
        
        if ($imageInfo === false) {
            return false;
        }
        
        $imageType = $imageInfo[2];
        $image = null;
        
        if($imageType == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($path);
        } else if ($imageType == IMAGETYPE_GIF) {
            $image = imagecreatefromgif($path);
        } else if ($imageType == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($path);
        }
        
        if ($image !== null) {
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);
            $destinationPath = $destinationPath === null ? $path : $destinationPath;
            $width = $imageWidth * $scale;
            $height = $imageHeight * $scale;
            $newImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);

            if($imageType == IMAGETYPE_JPEG) {
                return imagejpeg($newImage, $destinationPath);
            } else if ($imageType == IMAGETYPE_GIF) {
                return imagegif($newImage, $destinationPath);
            } else if ($imageType == IMAGETYPE_PNG) {
                return imagepng($newImage, $destinationPath);
            } else {
                return false;
            }

        }
        
        return false;
    }
    
    public static function createThumbnailImage($path, $width, $height, $destinationPath = null) {
        $imageInfo = getimagesize($path);
        
        if ($imageInfo === false) {
            return false;
        }
        
        $imageType = $imageInfo[2];
        $image = null;
        
        if($imageType == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($path);
        } else if ($imageType == IMAGETYPE_GIF) {
            $image = imagecreatefromgif($path);
        } else if ($imageType == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($path);
        }
        
        if ($image !== null) {
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);
            $widthRatio = $imageWidth / $width;
            $heightRatio = $imageHeight / $height;
            $destinationPath = $destinationPath === null ? $path : $destinationPath;
            
            if ($widthRatio > 1 || $heightRatio > 1) {
                if ($widthRatio > $heightRatio) {
                    $width = $imageWidth / $widthRatio;
                    $height = $imageHeight / $widthRatio;
                } else {
                    $width = $imageWidth / $heightRatio;
                    $height = $imageHeight / $heightRatio;
                }
                
                $newImage = imagecreatetruecolor($width, $height);
                imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);
                
                if($imageType == IMAGETYPE_JPEG) {
                    return imagejpeg($newImage, $destinationPath);
                } else if ($imageType == IMAGETYPE_GIF) {
                    return imagegif($newImage, $destinationPath);
                } else if ($imageType == IMAGETYPE_PNG) {
                    return imagepng($newImage, $destinationPath);
                } else {
                    return false;
                }
                
            } else {
                return copy($path, $destinationPath);
            }
        }
        
        return false;
    }
    
    public static function createStretchedImage($path, $width, $height, $destinationPath = null) {
        $imageInfo = getimagesize($path);
        
        if ($imageInfo === false) {
            return false;
        }
        
        $imageType = $imageInfo[2];
        $image = null;
        
        if($imageType == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($path);
        } else if ($imageType == IMAGETYPE_GIF) {
            $image = imagecreatefromgif($path);
        } else if ($imageType == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($path);
        }
        
        if ($image !== null) {
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);
            $destinationPath = $destinationPath === null ? $path : $destinationPath;
            $newImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);

            if($imageType == IMAGETYPE_JPEG) {
                return imagejpeg($newImage, $destinationPath);
            } else if ($imageType == IMAGETYPE_GIF) {
                return imagegif($newImage, $destinationPath);
            } else if ($imageType == IMAGETYPE_PNG) {
                return imagepng($newImage, $destinationPath);
            } else {
                return false;
            }

        }
        
        return false;
    }
    
}
