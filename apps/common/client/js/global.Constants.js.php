<?
    /**
     * This file is used to export all constants from PHP to JS
     * The contents will appear in the global scope of JS on the page
     *  
     * @author Alexander Podgorny <ap.coding@gmail.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */

?>

	App = {}; 
    App.Env = {
    	HTTP_ROOT : '<?= $_ENV['HTTP_ROOT']?>',
    	MVC_ROOT  : '<?= $_ENV['MVC_ROOT']?>'
        /* Constants go here */
    }; 
