<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

GFForms::include_feed_addon_framework();

class FormNameAddOn extends GFAddOn {

    protected $_version = GRAVITYADD_FORMNAME_VERSION;
    protected $_min_gravityforms_version = '2.0.7.4';
    protected $_slug = 'gravityaddon';
    protected $_path = 'gf-formname/class-formname-GFAddOn.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Form Name AddOn';
    protected $_short_title = 'Form Name';

    private static $_instance = null;

    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new FormNameAddOn();
        }

        return self::$_instance;
    }

    public function init() {
        parent::init();

        add_filter( 'gform_form_tag', array ($this, 'form_tag') , 10, 2 );
        
        $enabled = $this->get_plugin_setting( 'datalayer' );
        if ( $enabled == 1 ) {
            add_filter( 'gform_submit_button', array($this, 'savename_onclick'), 10, 2 );
            add_action( 'wp_footer', array($this, 'datalayer_script'));
        }
        
    }

    public function plugin_settings_fields() {
  		return array(
  			array(
  				'title'  => esc_html__( 'Form Name Add-On Settings', 'gravityaddon' ),
  				'fields' => array(
                    array(
                        'label'      => esc_html__( 'Title Translation', 'gravityaddon' ),
                        'type'       => 'radio',
                        'name'       => 'gffn_format',
                        'tooltip'    => esc_html__( 'Describe how the form title will be formatted', 'gravityaddon' ),
                        'choices'    => array(
                            array(
                                'label' => __( 'None => "My Form Title"', 'gravityaddon' ),
                                'value' => 'none',
                                'default_value' => true,
                            ),
                            array(
                                'label' => __( 'Lowcase Hyphenated => "my-form-title"', 'gravityaddon' ),
                                'value' => 'lower',
                            ),
                            array(
                                'label' => __( 'Camel Case => "MyFormTitle"', 'gravityaddon' ),
                                'value' => 'camel',
                            ),
                        ),
                    ),
                    array(
                        'label'   => esc_html__( 'GTM dataLayer push', 'gravityaddon' ),
                        'tooltip'    => esc_html__( 'Push the form name to the GTM dataLayer on submission', 'gravityaddon' ),
                        'type'    => 'checkbox',
                        'name'    => 'datalayer',
                        'choices' => array(
                            array(
                                'label' => esc_html__( 'Enabled', 'simpleaddon' ),
                                'name'  => 'datalayer',
                            ),
                        ),
                    ),
  				)
  			)
        );
          
    }
      
    public function form_tag($form_tag, $form ) {
        $formname = $this->formname($form);

        if (strpos($form_tag, 'name=') === false) {
            $form_tag = substr($form_tag, 0, -1) .  " name='" . $formname . "'>";
        } else {
            $form_tag = preg_replace( "|name='(.*?)'|", "name='" . $formname . "'", $form_tag );
        }

        return $form_tag;
    }

    
    public function datalayer_script() {
        ?>
        <script>
        jQuery( document ).ready(function() {
            if(jQuery('.gform_confirmation_message').is(":visible")) { var formId=jQuery('.gform_confirmation_message').attr('id').replace('gform_confirmation_message_',''); var formName = sessionStorage.getItem('gformName'); dataLayer.push({'event':'gform.submit.success','GravityFormID':formId,'GravityFormName':formName}); }
            jQuery(document).bind('gform_confirmation_loaded', function(event, formId) { var formName = sessionStorage.getItem('gformName'); dataLayer.push({'event':'gform.submit.success','GravityFormID':formId,'GravityFormName':formName}); });
        });
        </script>
        <?php
    }

    public function savename_onclick( $button, $form ) {
        $dom = new DOMDocument();
        $dom->loadHTML( $button );
        $input = $dom->getElementsByTagName( 'input' )->item(0);
        $onclick = $input->getAttribute( 'onclick' );
        $onclick .= " sessionStorage.setItem('gformName', jQuery(this).closest('form').attr('name'));";
        $input->setAttribute( 'onclick', $onclick );
        return $dom->saveHtml( $input );
    }

    private function formname($form) {
        $formname = $form['title'];
        $translate = $this->get_plugin_setting( 'gffn_format' );
        if ($translate == 'lower') {
            $formname =  str_replace(" ", "-", strtolower($formname));
        } elseif ($translate == 'camel') {
            $formname =  str_replace(" ", "", ucwords($formname));
        }
        
        return urlencode($formname);
    }
}
