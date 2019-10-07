<?php

/**
 * Contact Form 7 to Killo.Dean
 *
 * @link              https://simonemontanari.com/
 * @since             1.0.0
 * @package           Europass_Cf7_Crm
 *
 * @wordpress-plugin
 * Plugin Name:       Contact Form 7 to Killo.Dean
 * Plugin URI:        europass-cf7-crm
 * Description:       Send Contact Form 7 requests to Killo.Dean CRM.
 * Version:           1.0.0 Beta
 * Author:            Simone Montanari
 * Author URI:        https://simonemontanari.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       europass-cf7-crm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EUROPASS_CF7_CRM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-europass-cf7-crm-activator.php
 */
function activate_europass_cf7_crm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-europass-cf7-crm-activator.php';
	Europass_Cf7_Crm_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-europass-cf7-crm-deactivator.php
 */
function deactivate_europass_cf7_crm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-europass-cf7-crm-deactivator.php';
	Europass_Cf7_Crm_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_europass_cf7_crm' );
register_deactivation_hook( __FILE__, 'deactivate_europass_cf7_crm' );

/**
 * Create option panel
 */
class Europass_Cf7_Crm_Settings {

	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings'  ) );

	}

	public function add_admin_menu() {

		add_options_page(
			esc_html__( 'Contact Form 7 Integration for Killo.Dean', 'europass-cf7-crm' ),
			esc_html__( 'Killo.Dean', 'europass-cf7-crm' ),
			'manage_options',
			'killo-dean',
			array( $this, 'page_layout' )
		);

	}

	public function init_settings() {

		register_setting(
			'ep_cf7_crm_group',
			'ep_cf7_crm_setting'
		);

		add_settings_section(
			'ep_cf7_crm_setting_section',
			'',
			false,
			'ep_cf7_crm_setting'
		);

		add_settings_field(
			'killo_dean_api_key',
			__( 'Killo.Dean Token', 'europass-cf7-crm' ),
			array( $this, 'render_killo_dean_api_key_field' ),
			'ep_cf7_crm_setting',
			'ep_cf7_crm_setting_section'
		);
        
		add_settings_field(
			'cf7_id',
			__( 'Contact Form 7 ID', 'europass-cf7-crm' ),
			array( $this, 'render_cf7_id_field' ),
			'ep_cf7_crm_setting',
			'ep_cf7_crm_setting_section'
		);
		
		add_settings_field(
			'killo_email',
			__( 'Email for API response', 'europass-cf7-crm' ),
			array( $this, 'render_killo_email_field' ),
			'ep_cf7_crm_setting',
			'ep_cf7_crm_setting_section'
		);
        
        add_settings_field(
			'killo_test_mode',
			__( 'Test mode', 'europass-cf7-crm' ),
			array( $this, 'render_killo_test_mode_field' ),
			'ep_cf7_crm_setting',
			'ep_cf7_crm_setting_section'
		);

	}

	public function page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'europass-cf7-crm' ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">' . "\n";
		echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
		echo '	<form action="options.php" method="post">' . "\n";

		settings_fields( 'ep_cf7_crm_group' );
		do_settings_sections( 'ep_cf7_crm_setting' );
		submit_button();

		echo '	</form>' . "\n";
		echo '</div>' . "\n";

	}

	function render_killo_dean_api_key_field() {

		// Retrieve data from the database.
		$options = get_option( 'ep_cf7_crm_setting' );

		// Set default value.
		$value = isset( $options['killo_dean_api_key'] ) ? $options['killo_dean_api_key'] : '';

		// Field output.
		echo '<input type="text" name="ep_cf7_crm_setting[killo_dean_api_key]" class="regular-text killo_dean_api_key_field" placeholder="' . esc_attr__( '', 'europass-cf7-crm' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'Ask token to info@killo.group.', 'europass-cf7-crm' ) . '</p>';

	}

	function render_cf7_id_field() {

		// Retrieve data from the database.
		$options = get_option( 'ep_cf7_crm_setting' );

		// Set default value.
		$value = isset( $options['cf7_id'] ) ? $options['cf7_id'] : '';

		// Field output.
		echo '<input type="number" name="ep_cf7_crm_setting[cf7_id]" class="regular-text cf7_id_field" placeholder="' . esc_attr__( '', 'europass-cf7-crm' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'The ID of the contact form used to send data to the CRM.', 'europass-cf7-crm' ) . '</p>';

	}

	function render_killo_email_field() {

		// Retrieve data from the database.
		$options = get_option( 'ep_cf7_crm_setting' );

		// Set default value.
		$value = isset( $options['killo_email'] ) ? $options['killo_email'] : '';

		// Field output.
		echo '<input type="email" name="ep_cf7_crm_setting[killo_email]" class="regular-text killo_email_field" placeholder="' . esc_attr__( '', 'europass-cf7-crm' ) . '" value="' . esc_attr( $value ) . '">';
		echo '<p class="description">' . __( 'This address will receive Killo.Dean API responses (default to blog administrator).', 'europass-cf7-crm' ) . '</p>';

	}
    
    function render_killo_test_mode_field() {

		// Retrieve data from the database.
		$options = get_option( 'ep_cf7_crm_setting' );

		// Set default value.
		$value = isset( $options['killo_test_mode'] ) ? $options['killo_test_mode'] : '';

		// Field output.
		echo '<input type="checkbox" name="ep_cf7_crm_setting[killo_test_mode]" class="killo_test_mode_field" value="checked" ' . checked( $value, 'checked', false ) . '> ' . __( '', 'europass-cf7-crm' );
		echo '<p class="description">' . __( 'Activate test mode to verify JSON Data (no information will be sent to Killo.Dean)', 'europass-cf7-crm' ) . '</p>';

	}

}

new Europass_Cf7_Crm_Settings;

/**
 * Helpers
 */
function course_type_to_code( string $code ) {
    
    switch ( $code ) {
        case 'INT':
            $course_id = array( 2, 128, 130 );
            return $course_id;
        case 'STA':
            $course_id = array( 128, 130 );
            return $course_id;
        case 'CON':
            $course_id = array( 3 );
            return $course_id;
        case 'GRA':
            $course_id = array( 4 );
            return $course_id;
        case 'PR1':
            $course_id = array( 6 );
            return $course_id;
        case 'PR2':
            $course_id = array( 5 );
            return $course_id;
    }
    
}

function course_end_date( string $start_date, $number_of_weeks  ) {
    
    $number_of_days = ( $number_of_weeks * 7 ) - 2;
    $course_lenght = '+' . $number_of_days . ' day';
	
	$end_date = DateTime::createFromFormat('Y-m-d', $start_date);
	$end_date->modify($course_lenght);
	
	return $end_date->format('Y-m-d');
    
}

/**
 * Send Contact Form 7 filled forms to Killo.Dean.
 */
function wpcf7_to_killo_dean( $contact_form ) {
    
    $form_ID = $contact_form->ID();
    
    // Get plugin options
    $options = get_option( 'ep_cf7_crm_setting' );

    $active_form = $options['cf7_id'];
    $token = $options['killo_dean_api_key'];
    $test_mode = $options['killo_test_mode'];

    if ( !empty( $options['killo_email'] ) ) {
        $email_to = $options['killo_email'];
    } else {
        $email_to = get_option( 'admin_email' );
    }
    
    // Verify on which contact form the integration is active
    if ( $form_ID == $active_form ) {
    
        $submission = WPCF7_Submission::get_instance();

        if ( $submission ) {
            $data = $submission->get_posted_data();
        }

        // Collect purchases information

        // Courses
        $course = $data['course'];

        // Holidays
        $holiday = $data['menu-holiday'];

        if ( $course ) {

            switch ( $course ) {
                case 'ITADU':
                    $course_type = $data['type-adults'];
                    $course_ids = course_type_to_code( $course_type );
                    $course_start = $data['date-courses-group'];
                    $weeks = $data['weeks-courses'];
                    $course_end = course_end_date( $course_start, $weeks);
                    break;
                case 'ITBEG':
                    $course_type = $data['type-beginners'];
                    $course_ids = course_type_to_code( $course_type );
                    $course_start = $data['date-courses-group'];
                    $weeks = $data['weeks-courses'];
                    $course_end = course_end_date( $course_start, $weeks);
                    break;
                case 'ITINT':
                    $course_ids = array( 2, 128, 130);
                    $course_start = $data['date-courses-group'];
                    $weeks = $data['weeks-courses'];
                    $course_end = course_end_date( $course_start, $weeks);
                    break;
                case 'ITEVE':
                    $course_ids = array( 9 );
                    $course_start = $data['date-courses-group'];
                    if ( $data['weeks-evening'] <= 12 ) { $weeks = $data['weeks-evening']; } elseif ( $data['weeks-evening-extra'] > 0 ) { $weeks = $data['weeks-evening-extra']; } else { $weeks = 1; } ;
                    $course_end = course_end_date( $course_start, $weeks);
                    break;
                case 'ITSUM':
                    $course_type = $data['type-summer']; 
                    $course_ids = course_type_to_code( $course_type );
                    $course_start = $data['date-courses-summer'];
                    $weeks = $data['weeks-courses'];
                    $course_end = course_end_date( $course_start, $weeks);
                    break;
                case 'ITPRI':
                    $course_type = $data['type-private'];
                    $course_ids = course_type_to_code( $course_type );
                    $course_start = $data['date-courses-group'];
                    $weeks = $data['weeks-courses'];
                    $course_hours = $data['lessons-private'] * $weeks;
                    $course_end = course_end_date( $course_start, $weeks);
                    break;
            }

        } elseif ( $holiday ) {

            switch ( $holiday ) {
                case 'HITA':
                    $course_type = $data['type-italian'];
                    if ( $course_type = 'STA' ) {
                        $course_ids = array( 10, 128, 130);
                    } elseif  ( $course_type = 'INT' ) {
                        $course_ids = array( 11, 2, 128, 130);
                    };
                    break;
                case 'HCOO':
                    $course_type = $data['type-cooking'];
                    if ( $course_type = 'CWF' ) {
                        $course_ids = array( 12, 128, 130);
                    } elseif  ( $course_type = 'CMS' ) {
                        $course_ids = array( 13, 128, 130);
                    };
                    break;
                case 'HPAI':
                    $course_type = $data['type-painting'];
                    if ( $course_type = 'PBA' ) {
                        $course_ids = array( 14, 128, 130);
                    } elseif  ( $course_type = 'PFU' ) {
                        $course_ids = array( 15, 128, 130);
                    };
                    break;
                case 'HWEL':
                    $course_ids = array( 16, 128, 130);
                    break;
                case 'HSUM':
                    $course_ids = array( 18, 128, 130);
                    $accommodation = $data['type-junior'];
                    if ( $accommodation != 'NO') {
                        $weeks = $data['weeks-holidays'];
                        switch ( $weeks ) {
                            case 1 :
                                $extra = 124;
                                break;
                            case 2 :
                                $extra = 125;
                                break;
                            case 3 :
                                $extra = 139;
                                break;
                            case 4 :
                                $extra = 126;
                                break;
                        };
                        array_push( $course_ids, $extra );
                    }
                    $notes = " - Stanza: " . $accommodation;
                    break;
                case 'HELBA':
                    $course_type = $data['type-elba'];
                    if ( $course_type = 'STA' ) {
                        $course_ids = array( 155, 128, 130);
                    } elseif  ( $course_type = 'INT' ) {
                        $course_ids = array( 156, 2, 128, 130);
                    };
                    $notes = " - Isola d'Elba";
                    break;
                case 'HSOLO':
                    $course_ids = array( 17, 128, 130);
                    break;
            }

            $course_start = $data['date-holidays'];
            $weeks = $data['weeks-holidays'];
            $course_end = course_end_date( $course_start, $weeks);

        }

        // Aggregate address fields
        $address = $data['address'] . ', ' . $data['postalcode'] . ' ' . $data['city'] . ', ' . $data['country'];
        
        // Photo consent
        if ( in_array( "1", $data['photo-consent'] ) ) {
            $photo = true;
        } else {
            $photo = false;
        }

        // Collect student information
        $student = array(
            'nome'                => $data['name-contact'],
            'cognome'             => $data['surname'],
            'bday'                => $data['birthdate'],
            'nazionalita_nome'    => $data['nationality'],
            'indirizzo'           => $address,
            'email'               => $data['email'],
            'tel'                 => $data['tel'],
            'occupazione_id'      => $data['profession'],
            'sesso_nome'          => $data['gender'],
            'livello_suggerito'   => '',
            'datapolicy'          => true,
            'newsletter'          => 0,
            'photoandvideo'       => $photo,
        );

        foreach( $course_ids as $course_id ) {
            
            switch ( $course_id ) {
                case 12 :
                case 13 :
                    $course_hours = 6 * $weeks;
                    break;
                case 2 :
                case 3 :
                case 4 :
                case 5 :
                case 6 :
                case 128 :
                case 130 :
                    $course_hours = 10 * $weeks;
                    break;
                case 14 :
                    $course_hours = 12 * $weeks;
                    break;
                case 9 :
                    $course_hours = 16 * $weeks;
                    break;
                case 10 :
                case 11 :
                case 15 :
                case 16 :
                    $course_hours = 20 * $weeks;
                    break;
            };

            // Prepare POST request body
            $body = array(
                'student'       => $student,
                'altro'         => 'Numero di settimane selezionato: ' . $weeks . ' ' . $notes,
                'corso_id'      => $course_id,
                'datainizio'    => $course_start,
                'datafine'      => $course_end,
                'ore'           => $course_hours,
                'prezzo'        => '0',
                'saldato'       => 0,
            );

            // Convert POST request Body to JSON
            $toCRM = json_encode( $body );

            // API Url
            $api_url = 'http://killo.software/api/' . $token . '/acquisti/';
            
            if ( ! $test_mode ) {

                $response = wp_remote_post( $api_url, array(
                    'body'        => $toCRM,
                    'data_format' => 'body',
                )); 

                // Simple error handling
                if ( is_wp_error( $response ) ) {

                    $msg  = "C'è stato un errore nell'invio dei dati al CRM. >>>>> ";
                    $msg .= "Errore: " . $error = $response->get_error_message() . " >>>>> ";
                    $msg .= "Il seguente contatto dovrà essere inserito manualmente su Killo.Dean. >>>>> ";
                    $msg .= $data['name-contact'] . ' ' . $data['surname'] . ', ' . $data['email'];

                    wp_mail( $email_to, 'Errore di connessione a Killo.Dean', $msg );

                } else {

                    $message = $response['body'] . " >>>>>>> " . $toCRM;

                    wp_mail( $email_to, 'Risposta API Killo.Dean', $message);
                }
                
            } else {
                
                $email_subject = 'TEST CF7 to Killo.Dean: ' . $data['surname'];
                
                $message = $toCRM . " >>>>>>> " . $api_url . " >>>>>>> No data was sent to Killo.Dean";
                
                wp_mail( $email_to, $email_subject, $message);
                
            }

        }
        
    }

}
add_action( 'wpcf7_mail_sent', 'wpcf7_to_killo_dean' );
