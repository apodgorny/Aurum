<?

 	class Validation extends Object {

		/****************** PRIVATE *******************/
		private static $_aErrorMessages = array();
		private static $_aRegExp = array();

		private static function _init() {
			self::$_aErrorMessages = array(
				self::RULE_ALPHANUMERIC     => Text::getText('may only contain letters or digits'),
				self::RULE_NUMERIC 		    => Text::getText('must be a number'),
				self::RULE_ALPHA		    => Text::getText('may only contain letters'),
				self::RULE_MIN_SIZE_1	    => Text::getText('must be at least one character long'),
				self::RULE_MIN_SIZE_2	    => Text::getText('must be at least two characters long'),
				self::RULE_MIN_SIZE_4       => Text::getText('must be at least 4 characters long'),
				self::RULE_MIN_SIZE_6       => Text::getText('must be at least six characters long'),
				self::RULE_MIN_SIZE_10      => Text::getText('must be at least 10 characters long'),
				self::RULE_MIN_SIZE_50      => Text::getText('must be at least 50 characters long'),
				self::RULE_MAX_SIZE_16	    => Text::getText('may not be longer than 16 characters'),
				self::RULE_MAX_SIZE_25	    => Text::getText('may not be longer than 25 characters'),
				self::RULE_MAX_SIZE_30	    => Text::getText('may not be longer than 30 characters'),
				self::RULE_MAX_SIZE_50	    => Text::getText('may not be longer than 50 characters'),
				self::RULE_MAX_SIZE_400	    => Text::getText('is too long. Please try to fit in 400 characters'),
				self::RULE_MAX_SIZE_1000	=> Text::getText('is too long. Please try to fit in 1000 characters'),
				self::RULE_MAX_SIZE_5000	=> Text::getText('is too long. Please try to fit in 5000 characters'),
				self::RULE_EMAIL	        => Text::getText('must be a valid email address'),
				self::RULE_PASSWORD	        => Text::getText('must be 6 to 20 characters long and cannot contain newlines'),
				self::RULE_TITLE            => Text::getText('may only contain letters, digits, spaces and punctuation characters'),
				self::RULE_NAME             => Text::getText('must only contain valid characters')
			);

			self::$_aRegExp = array(
				self::RULE_ALPHANUMERIC     => '/^[a-z0-9]*$/si',
				self::RULE_NUMERIC 		    => '/^[0-9]*$/si',
				self::RULE_ALPHA		    => '/^[a-z]*$/si',
				self::RULE_MIN_SIZE_1	    => '/.{1,}/si',
				self::RULE_MIN_SIZE_2	    => '/.{2,}/si',
				self::RULE_MIN_SIZE_4       => '/.{4,}/si',
				self::RULE_MIN_SIZE_6       => '/.{6,}/si',
				self::RULE_MIN_SIZE_10      => '/.{10,}/si',
				self::RULE_MIN_SIZE_50      => '/.{50,}/si',
				self::RULE_MAX_SIZE_16	    => '/^[\s\S]{0,16}$/si',
				self::RULE_MAX_SIZE_25	    => '/^[\s\S]{0,25}$/si',
				self::RULE_MAX_SIZE_30	    => '/^[\s\S]{0,30}$/si',
				self::RULE_MAX_SIZE_50	    => '/^[\s\S]{0,50}$/si',
				self::RULE_MAX_SIZE_400     => '/^[\s\S]{0,400}$/si',
				self::RULE_MAX_SIZE_1000    => '/^[\s\S]{0,1000}$/si',
				self::RULE_MAX_SIZE_5000    => '/^[\s\S]{0,5000}$/si',
				self::RULE_EMAIL            => '/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/si',
				self::RULE_PASSWORD	        => '/^[\s\S]{6,20}$/si',
				self::RULE_TITLE	        => '/^[a-z0-9\\:\-\/ \.,&]*$/si',
				self::RULE_NAME             => '/^[a-z\\:\-\/ \.,\' ]*$/si'
			);
		}

		private static function _getError($sValue, $nRule) {
//		    Debug::trace($sValue, $nRule);
			if (self::isValid($sValue, $nRule)) {
				return null;
			}
			return self::$_aErrorMessages[$nRule];
		}

		/****************** PUBLIC *******************/

		const RULE_ALPHANUMERIC     = 1;
		const RULE_NUMERIC          = 2;
		const RULE_ALPHA            = 3;
		const RULE_EMAIL            = 4;
		const RULE_PASSWORD         = 5;
		const RULE_TITLE            = 6;
        const RULE_NAME             = 7;
		const RULE_MIN_SIZE_1       = 10;
		const RULE_MIN_SIZE_2       = 100;
		const RULE_MIN_SIZE_4       = 11;
		const RULE_MIN_SIZE_6       = 12;
		const RULE_MIN_SIZE_10      = 13;
		const RULE_MIN_SIZE_50      = 14;
		const RULE_MAX_SIZE_16      = 20;
		const RULE_MAX_SIZE_25      = 21;
		const RULE_MAX_SIZE_30      = 22;
		const RULE_MAX_SIZE_50      = 23;
		const RULE_MAX_SIZE_400     = 24;
		const RULE_MAX_SIZE_1000    = 25;
		const RULE_MAX_SIZE_5000    = 25;

		/**
		 * @param string $sValue String value to validate
		 * @param array $aRules @example $aRules = array(
		 *      Validation::RULE_ALPHANUMERIC => null | 'Custom error message for alphanumeric validation'
		 *  )
		 */
		public static function getErrors($sValue, $aRules) {
			self::_init();
			$aErrors = array();
			foreach ($aRules as $nRule=>$sCustomError) {
				$sError = self::_getError($sValue, $nRule);
				if ($sError) {
					$aErrors[$nRule] = $sCustomError ? $sCustomError : $sError;
				}
			}
			if (count($aErrors) > 0) {
				return $aErrors;
			}
			return null;
		}
		public static function isValid($sValue, $nRule) {
		    return preg_match(self::$_aRegExp[$nRule], $sValue);
	    }
	}

?>