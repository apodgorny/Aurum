<?
    /**
     * class Email
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

    class Email extends Object {

        /****************** PRIVATE *******************/

        private $_sSubject          = null;
        private $_sMessage          = null;
        private $_sTextOnlyMessage  = null;
        private $_sSender           = null;
        private $_aRecipients       = null;

        private function _send($sRecipient) {
            $sBoundary = md5(microtime().$sRecipient);
            $sHeaders  = 'From: '.trim($this->_sSender)."\r\n";
            $sHeaders .= 'Reply-To: '.trim($this->_sSender)."\r\n";
            $sHeaders .= 'Return-Path: '.trim($this->_sSender)."\r\n";
            $sHeaders .= 'Content-Type: multipart/alternative; boundary="'.$sBoundary.'"'."\r\n";
//          $sHeaders .= "CC: sombodyelse@noplace.com\r\n";
//          $sHeaders .= "BCC: hidden@special.com\r\n";
            $this->_sTextOnlyMessage = strip_tags($this->_sMessage);
            $sMessage =
<<<STR
--$sBoundary
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

$this->_sTextOnlyMessage

--$sBoundary
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

$this->_sMessage

--$sBoundary--
STR;

            if ($_ENV['ENABLE_EMAIL'] && !mail($sRecipient, $this->_sSubject, $sMessage, $sHeaders) ) {
               throw new Exception('Could not send email');
            }
        }

        /****************** PROTECTED *****************/
        /****************** PUBLIC ********************/

        public function __construct($sSubject=null, $sMessage=null, $sTextOnlyMessage=null) {
            parent::__construct();
            $this->_sSubject         = $sSubject;
            $this->_sMessage         = $sMessage;
            $this->_sTextOnlyMessage = $sTextOnlyMessage;
        }
        public function load($sUrl, $sTextOnlyUrl=null) {
            Debug::show('Loaded from: '.$sUrl);
            $this->_sMessage = file_get_contents($sUrl);
            if (preg_match('/<title>(.*)<\/title>/', $this->_sMessage, $aMatches)) {
                $this->_sSubject = $aMatches[1];
            }
            if ($sTextOnlyUrl) {
                $this->_sTextOnlyMessage = file_get_contents($sTextOnlyUrl);
            }
            return $this;
        }
        public function send($sSender, $aRecipients=array()) {
            $this->_sSender = $sSender;
            $this->_aRecipients = $aRecipients;

            foreach ($this->_aRecipients as $sRecipient) {
                Debug::show(
                    'Sending... ',
                    'From: '.$this->_sSender,
                    'To: '.$sRecipient,
                    'Subject: '.$this->_sSubject,
                    'Message: '. $this->_sMessage
                );
                $this->_send($sRecipient);
            }
            return $this;
        }
    }

?>