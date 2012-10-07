<?
    /**
     * class Rectangle
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Rectangle extends Shape {
        private $_nX = 0;
        private $_nY = 0;
        private $_nW = 0;
        private $_nH = 0;
        
        public function __construct($nX=0, $nY=0, $nW=0, $nH=0) {
            $this->_nX = $nX;
            $this->_nY = $nY;
            $this->_nW = $nW;
            $this->_nH = $nH;
        }
        public function __get($sProperty) {
            switch ($sProperty) {
                case 'width':
                case 'w':
                    return $this->_nW;
                case 'height':
                case 'h':
                    return $this->_nH;
                case 'left':
                case 'x':
                    return $this->_nX;
                case 'top':
                case 'y':
                    return $this->_nY;
            }
            return null;
        } 
    }

?>