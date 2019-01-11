<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/**
 * Class  EED_Attendee_Importer
 * 
 * This is where miscellaneous action and filters callbacks should be setup to
 * do your addon's business logic (that doesn't fit neatly into one of the
 * other classes in the mock addon)
 *
 * @package			Event Espresso
 * @subpackage		eea-attendee-importer
 * @author 				Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EED_Attendee_Importer extends EED_Module {

	/**
	 * @var 		bool
	 * @access 	public
	 */
	public static $shortcode_active = FALSE;



	/**
	 * @return EED_Attendee_Importer
	 */
	public static function instance() {
		return parent::get_instance( __CLASS__ );
	}



	 /**
	  * 	set_hooks - for hooking into EE Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks() {
		 EE_Config::register_route( 'attendee_importer', 'EED_Attendee_Importer', 'run' );
	 }

	 /**
	  * 	set_hooks_admin - for hooking into EE Admin Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks_admin() {
		 // ajax hooks
		 add_action( 'wp_ajax_get_attendee_importer', array( 'EED_Attendee_Importer', 'get_attendee_importer' ));
		 add_action( 'wp_ajax_nopriv_get_attendee_importer', array( 'EED_Attendee_Importer', 'get_attendee_importer' ));
	 }

	 public static function get_attendee_importer(){
		 echo wp_json_encode( array( 'response' => 'ok', 'details' => 'you have made an ajax request!') );
		 die;
	 }



	/**
	 *    config
	 *
	 * @return EE_Attendee_Importer_Config
	 */
	public function config(){
		// config settings are setup up individually for EED_Modules via the EE_Configurable class that all modules inherit from, so
		// $this->config();  can be used anywhere to retrieve it's config, and:
		// $this->_update_config( $EE_Config_Base_object ); can be used to supply an updated instance of it's config object
		// to piggy back off of the config setup for the base EE_Attendee_Importer class, just use the following (note: updates would have to occur from within that class)
		return EE_Registry::instance()->addons->EE_Attendee_Importer->config();
	}






	 /**
	  *    run - initial module setup
	  *
	  * @access    public
	  * @param  WP $WP
	  * @return    void
	  */
	 public function run( $WP ) {
		 add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
	 }






	/**
	 * 	enqueue_scripts - Load the scripts and css
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function enqueue_scripts() {
		//Check to see if the attendee_importer css file exists in the '/uploads/espresso/' directory
		if ( is_readable( EVENT_ESPRESSO_UPLOAD_DIR . "css/attendee_importer.css")) {
			//This is the url to the css file if available
			wp_register_style( 'espresso_attendee_importer', EVENT_ESPRESSO_UPLOAD_URL . 'css/espresso_attendee_importer.css' );
		} else {
			// EE attendee_importer style
			wp_register_style( 'espresso_attendee_importer', EE_ATTENDEE_IMPORTER_URL . 'css/espresso_attendee_importer.css' );
		}
		// attendee_importer script
		wp_register_script( 'espresso_attendee_importer', EE_ATTENDEE_IMPORTER_URL . 'scripts/espresso_attendee_importer.js', array( 'jquery' ), EE_ATTENDEE_IMPORTER_VERSION, TRUE );

		// is the shortcode or widget in play?
		if ( EED_Attendee_Importer::$shortcode_active ) {
			wp_enqueue_style( 'espresso_attendee_importer' );
			wp_enqueue_script( 'espresso_attendee_importer' );
		}
	}
 }
// End of file EED_Attendee_Importer.module.php
// Location: /wp-content/plugins/eea-attendee-importer/EED_Attendee_Importer.module.php
