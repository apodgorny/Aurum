<?

    /**
     * Fluid grid renderer (will not work in Facebook)
     * @author Alexander Podgorny
     */

    class Grid extends Object {
        const ALIGN_LEFT = 'left';
        const ALIGN_RIGHT = 'right';
        const ALIGN_CENTER = 'center';
        const FLOAT_RIGHT = 'float: right';
        
    	private static $_grids = array();
    	public static function BEGIN($sGridName, $nWidth, $sAlign=Grid::ALIGN_LEFT) {
    		self::$_grids[$sGridName] = $nWidth;
    		$sHTML = Dom::COMMENT('BEGIN '.$sGridName);
	    	$sHTML .= 
    	        '<div class="dgrid-row" style="zoom: 1;">'.
    	            '<div class="dgrid-col-l" style="width:'.($nWidth + 1).'px; text-align:'.$sAlign.';">'.
    		            '<div class="'.$sGridName.'-l">';
	    	return "\n".$sHTML."\n\n";
    	}
    	public static function SPLIT($sGridName, $nGap=0, $sAlign=Grid::ALIGN_RIGHT) {
    		$nWidth = self::$_grids[$sGridName];
	    	$sHTML = 
    		            '</div>'.
        		    '</div>'.
        		    '<div class="dgrid-col-r" style="text-align:'.$sAlign.';">'.
    		            '<div class="'.$sGridName.'-r">';
    		return "\n".$sHTML."\n\n";
    	}
    	public static function END($sGridName) {
    		$sHTML = 
    		            '</div>'.
                    '</div>'.
                '</div>';
    		$sHTML .= Dom::COMMENT('END '.$sGridName);
    		return "\n".$sHTML;
    	}
    }

?>