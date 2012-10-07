<?

    /**
      * Class Image
      * @author Alexander Podgorny
      * @license GPL
      */

    class Image extends Object {
        const RESIZE_FILL       = 2;    // When resizing, constrain proportions, keep largest dimension
        const RESIZE_FIT        = 3;    // When resizing, constrain proportions, keep smallest dimension
        const RESIZE_STRETCH    = 1;    // When resizing, do not constrain proportions, keep both dimensions
        
        const TYPE_JPEG         = 'jpg';
        const TYPE_GIF          = 'gif';
        const TYPE_PNG          = 'png';

        private $_sFileName     = '';
        private $_oImage        = null;

        public function __construct($sFileName = null) {
            if ($sFileName) {
                $this->open($sFileName);
            }
        }

        public function __destruct() {
            imagedestroy($this->_oImage);
        }
        
        public function __get($sProperty) {
            switch ($sProperty) {
                case 'width': 
                    return imagesx($this->_oImage);
                case 'height':
                    return imagesy($this->_oImage);
            }
            return null;
        }
        
        public function open($sFileName) {
            if (!file_exists($sFileName)) {
                throw new Exception('File could not be found: '.$sFileName);
            }
            $this->_sFileName = $sFileName;
            $sExtension = Files::getExtension($sFileName);
            switch ($sExtension) {
                case 'jpg':
                case 'jpeg':
                    $this->_oImage = imagecreatefromjpeg($sFileName);
                    break;
                case 'gif':
                    $this->_oImage = imagecreatefromgif($sFileName);
                    break;
                case 'png':
                    $this->_oImage = imagecreatefrompng($sFileName);
                    break;
                default:
                    throw new Exception('Unknown file type: '.$sFileName);
            }
            return $this;
        }

        public function resize($nW, $nH, $nOption=self::RESIZE_FIT) {
            $nSrcRatio = $this->width / $this->height;
            $nDstRatio = $nW / $nH;

            switch ($nOption) {
                case self::RESIZE_STRETCH:
                    // do nothing;
                    break;
                case self::RESIZE_FILL:
                    if ($nDstRatio > $nSrcRatio) {
                        $nH = $nW / $nSrcRatio;
                    } else {
                        $nW = $nSrcRatio * $nH;
                    }
                    break;
                case self::RESIZE_FIT:
                default:
                    if ($nDstRatio > $nSrcRatio) {
                        $nW = $nSrcRatio * $nH;
                    } else {
                        $nH = $nW / $nSrcRatio;
                    }
                    break;
            }
            $nW = floor($nW);
            $nH = floor($nH);
            $oImageResized = imagecreatetruecolor($nW, $nH);
            imagecopyresampled(
                $oImageResized,
                $this->_oImage,
                0, 0, 0, 0,
                $nW,
                $nH,
                $this->width,
                $this->height
            );
            imagedestroy($this->_oImage);
            $this->_oImage = $oImageResized;
            $this->width = $nW;
            $this->height = $nH;
            return $this;
        }
        
        public function crop($nX=0, $nY=0, $nW=0, $nH=0) {
            if ($nX instanceof Rectangle) {
                $oRect = $nX;
                $nX = $oRect->x;
                $nY = $oRect->y;
                $nW = $oRect->w;
                $nH = $oRect->h;
            }
            // Crop square in the middle by default
            if (!$nW || !$nH) {
                if ($this->width > $this->height) {
                    $nW = $nH = $this->height;
                    $nX = floor(($this->width - $nW) / 2);
                    $nY = 0;
                } else {
                    $nW = $nH = $this->width;
                    $nX = 0;
                    $nY = floor(($this->height - $nH) / 2);
                }
            }
            
            $oNewImage = imagecreatetruecolor($nW, $nH);
            imagecopy($oNewImage, $this->_oImage, 0, 0, $nX, $nY, $nW, $nH);
            $this->_oImage = $oNewImage;
            return $this;
        }
        
        public function save($sFileName = null, $nQuality = null) {
            if (!$sFileName) {
                $sFileName = $this->_sFileName;
            }
            $sExtension = strtolower(strrchr($sFileName, '.'));
            if (!$sFileName) {
                throw new Exception('Could not save file. File name is not supplied');
            }
            switch ($sExtension) {
                case '.jpg':
                case '.jpeg':
                    if ($nQuality !== null) {
                        // otherwise use built in default
                        if ($nQuality < 0) {
                            $nQuality = 0;
                        }
                        if ($nQuality > 100) {
                            $nQuality = 100;
                        }
                    }
                    imagejpeg($this->_oImage, $sFileName, $nQuality);
                    break;
                case '.gif':
                    imagegif($this->_oImage, $sFileName);
                    break;
                case '.png':
                    if ($nQuality !== null) {
                        // otherwise use built in default
                        if ($nQuality > 9) {
                            $nQuality = 9;
                        }
                        if ($nQuality < 0) {
                            $nQuality = 0;
                        }
                    }
                    imagepng($this->_oImage, $sFileName, $nQuality);
                    break;
                default:
                    throw new Exception('Unknown file type: '.$sFileName);
                    break;
            }
            return $this;
        }

        public function output($nType = self::TYPE_JPEG, $nQuality = null) {
            switch ($nType) {
                case self::TYPE_JPEG:
                    if ($nQuality) {
                        // otherwise use built in default
                        if ($nQuality < 0) {
                            $nQuality = 0;
                        }
                        if ($nQuality > 100) {
                            $nQuality = 100;
                        }
                    }
                    header('Content-type: image/jpeg');
                    imagejpeg($this->_oImage, null, $nQuality);
                    break;
                case self::TYPE_GIF:
                    header('Content-type: image/gif');
                    imagegif($this->_oImage, null);
                    break;
                case self::TYPE_PNG:
                    if ($nQuality) {
                        // otherwise use built in default
                        if ($nQuality > 9) {
                            $nQuality = 9;
                        }
                        if ($nQuality < 0) {
                            $nQuality = 0;
                        }
                    }
                    header('Content-type: image/png');
                    imagepng($this->_oImage, null, $nQuality);
                    break;
                default:
                    throw new Exception('Unknown file type');
                    break;
            }
            return $this;
        }
    }

?>