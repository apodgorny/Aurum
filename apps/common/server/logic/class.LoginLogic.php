<?
    /**
     * Login Logic
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class LoginLogic {

        /***************** PRIVATE *******************/

        private static function _isLocalRequest() {
            return $_SERVER['SERVER_NAME'] == '127.0.0.1';
        }

        /***************** PRIVATE *******************/

        /**
         * Checks to see if the login pair is valid
         * @param string $sEmail User Email
         * @param string $sPassword Password
         * @return
         */
        public static function login($sEmail, $sPassword) {
            if (!trim($sEmail) || !trim($sPassword)) {
                return false;
            }
            $oMember = new MemberModel();
            $oMember->loadByEmail($sEmail);
            $sPasswordMd5 = md5($sPassword.$oMember['salt']);
            if ($sPasswordMd5 == $oMember['password']){
                LoginLogic::beginLogin($oMember['id']);
                return true;
            }
            return false;
        }
        public static function getLoggedInMember() {
            if (LoginLogic::hasLogin()) {
                return new MemberModel($_SESSION[$_ENV['LOGIN_SESSION_KEY']]['member_id']);
            }
            return null;
        }

        /**
         * Initializes login series
         * @return null
         */
        public static function beginLogin($nMemberId) {
            $_SESSION[$_ENV['LOGIN_SESSION_KEY']] = array(
                'expire'    => time() + $_ENV['LOGIN_LIFETIME'],
                'useragent' => $_SERVER['HTTP_USER_AGENT'],
                'ip'        => $_SERVER['REMOTE_ADDR'],
                'member_id' => $nMemberId
            );
            MemberModel::updateLoginTime($nMemberId);
        }

        /**
         * Ends login series, and redirects to re-login
         * @return null
         */
        public static function endLogin($sMessage='', $bRedirectBack=false, $bRedirectToLogin=true) {
            if (self::hasLogin()) {
                $oMember = self::getLoggedInMember();
                EventLogic::logEvent(
                    EventLogic::EVENT_MEMBER_LOGOUT,
                    $oMember['id'],
                    'member'
                );
            }

            unset($_SESSION[$_ENV['LOGIN_SESSION_KEY']]);
            $sUrl = Navigation::link('site', 'login', 'login', $bRedirectBack
                ? array('redirect_url' => urlencode(Navigation::linkHere()))
                : ''
            );

            if ($bRedirectToLogin) {
                Notification::construct()->set($sMessage);
                Navigation::redirectToUrl($sUrl);
            }
        }
        /**
         * Continues login series, extends time interval during which
         * the session remains valid
         * @return null
         */
        public static function extendLogin() {
            if (LoginLogic::hasLogin()) {
                $_SESSION[$_ENV['LOGIN_SESSION_KEY']]['expire'] =
                    time() + $_ENV['LOGIN_LIFETIME'];
            }
        }
        /**
         * Predicates if login series are active
         * @return null
         */
        public static function hasLogin() {
            return isset($_SESSION[$_ENV['LOGIN_SESSION_KEY']]);
        }
        /**
         * Authenticate
         */
        public static function authenticate() {
            if (self::_isLocalRequest()) {
                return true;
            }
            if (!LoginLogic::hasLogin()) {
                LoginLogic::endLogin(
                    Text::getText('You must be logged-in to access this page'),
                    true
                );
                return;
            }
            if ($_SESSION[$_ENV['LOGIN_SESSION_KEY']]['expire'] - time() <= 0) {
                LoginLogic::endLogin(
                    Text::getText('Your login has expired').'. '.
                    Text::getText('Please re-login').'.',
                    true
                );
                return;
            }
            if ($_SESSION[$_ENV['LOGIN_SESSION_KEY']]['useragent'] != $_SERVER['HTTP_USER_AGENT'] ||
                $_SESSION[$_ENV['LOGIN_SESSION_KEY']]['ip']        != $_SERVER['REMOTE_ADDR']) {
                LoginLogic::endLogin(
                    Text::getText('Your login is corrupted').'. '.
                    Text::getText('Please re-login').'.',
                    true
                );
                return;
            }
            LoginLogic::extendLogin();
        }
    }

?>
