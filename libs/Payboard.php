<?php

    //we will wrapp it within the hooks in meanwhile
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');
    require_once(ABSPATH . WPINC . '/pluggable.php');

    class Payboard {
        
        
        
        
        public function __construct($file) {
            
            PayboardVars::getInstance()->file = $file;
            PayboardVars::getInstance()->pluginDir = plugin_dir_path($file);
            PayboardVars::getInstance()->pluginUrl = plugin_dir_url($file);
            PayboardVars::getInstance()->pluginOptionKey = "payboard_options";
            
            $this->registerHooks();
        }
        
        
        
        
        private function registerHooks(){
            add_action('admin_menu', array(&$this,'addOptionsPage'));
            add_action('wp_ajax_payboard-apikey-save', array(&$this, 'payboardApiKeySave'));
            add_action('wp_footer', array(&$this, 'outputPayboardJavaScript'));
            add_action( 'wp_enqueue_scripts', array('Payboard', 'includeJavascripts') );
        }
        
        
        
        
        public function addOptionsPage(){
            $mypage = add_menu_page('Payboard Plugin Page', 'Payboard', 'manage_options', PayboardVars::getInstance()->pluginOptionKey, array(&$this,'renderPayboardPage'));
            add_action( "admin_print_scripts-$mypage", array($this, 'payboard_admin_head') );
        }
        
        
        
        
        public function payboard_admin_head() {
            wp_enqueue_script('payboardjs', PayboardVars::getInstance()->pluginUrl.'js/payboard.js', array('jquery'), 1.0);
            wp_enqueue_style( 'payboardcss', PayboardVars::getInstance()->pluginUrl."css/payboard.css" , false, 1.0, 'all' );
        }
        
        
        
        
        public function renderPayboardPage(){
            
            $payboardApiKey = get_option('payboard_api_key');
            if(!$payboardApiKey){
                $payboardApiKey = "";
            }
            ?>
            <div class="payboard_settings_wrapp">
                <div class="apikey_settings">
                    <div class="apikey_title">
                        Payboard Settings
                    </div>
                
                    <div class="form_wrapper">
                        <form action="" id="payboard-apikey-form" name="payboard-api-form" method="post">

                            <input type="hidden" name="action" value="payboard-apikey-save">
                            <?php wp_nonce_field("payboard-settings-group-options"); ?>
                            
                            <div class="option-block">
                                <span class="option-label">Insert your Api Key</span>: <br/>
                                <input type="text" name="apikey" value="<?php echo $payboardApiKey; ?>" size="50">
                            </div>

                            <?php submit_button('Save Changes', 'primary', 'submit-1'); ?>

                            <div class="spinner-container">
                                <span id="payboard-apikey-form-spiner" class="spinner" style="display:none"></span>
                            </div>
                            
                            
                            
                            <div id="apikey_status_message"></div>
                            <div style="clear:both;"></div>
                            <h3>Need a Payboard account? </h3>Request an Extended 60-day free trial for WordPress today: <br><br>Email <strong><a href="mailto:wordpress@payboard.com?subject=Wordpress%20API%20Request&body=Website%20Address:" >wordpress@payboard.com</a></strong> or visit <strong><a href="http://www.Payboard.com/Wordpress-Signup-Booster" target="_blank">http://www.Payboard.com/Wordpress-Signup-Booster</a></strong> 
                        </form>
                    </div>
                </div>
            </div>
  <?php }
  
        
        
        
        public function payboardApiKeySave(){
            $status = true;
            $message = 'Api Key successfuly saved';
            
            if(isset($_POST['apikey']) && !empty($_POST['apikey'])){
                $payboardapiKey = $_POST['apikey'];
            }else{
                $status = false;
                $message = "Please insert valid Api Key!";
            }
            
            if(isset($_POST['_wpnonce'])) {
                $wpNonce = $_POST['_wpnonce'];
            }
            
            //we need to save our data into database...
            if(!isset($wpNonce) || !wp_verify_nonce( $wpNonce, 'payboard-settings-group-options' )) {
                $status = false;
                $message = "Permission denied!";
            }
            
            if($status){
                update_option('payboard_api_key', $payboardapiKey);
            }
            
            
            $resultarray = array(
                        'status' => $status,
                        'message' => $message,
            );
            
            header('Content-type: application/json');
            echo json_encode($resultarray);
            die();
        }
        
        
        
        
        public function outputPayboardJavaScript(){
            $payboardApiKey = get_option('payboard_api_key');
            if($payboardApiKey){ ?>
                <!-- Begin Payboard Script -->
                <script type="text/javascript">
                    var payboardCallback = function() {
                        Payboard.Events.trackPage();
                    };
                    var payboardScript = document.createElement("script");
                    payboardScript.src = '//d3px1qgagsf6ei.cloudfront.net/Scripts/<?php echo $payboardApiKey; ?>';
                    if (payboardScript.addEventListener) {
                        payboardScript.addEventListener('load', payboardCallback, false);
                    } else if (payboardScript.readyState) {
                        payboardScript.onreadystatechange = payboardCallback;
                    }
                    document.body.appendChild(payboardScript);
                </script>
                <!-- End Payboard Script -->
     <?php }
        }
        
        public static function includeJavascripts(){
            wp_enqueue_script( 'jquery' );
        }
 
    }
    
    
    
    

?>