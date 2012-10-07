<?
    /**
     * This file is used to export all initializations from PHP to JS
     * The contents will appear in the global scope of JS on the page
     *  
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

?>

/* Begin initializations */
$ = jQuery;
$(function() {
    <?= JavaScript::getComponents() ?>
   // App.Dialog.dialogJoinStartup();
});
/* End initializations */