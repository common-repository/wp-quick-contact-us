<?php
/* 
Plugin Name: WP Quick Contact Us
Plugin URI: http://dmitritech.com
Description: A plugin to creating floating contact form on home, pages or posts.   
Author: Binish Prabhakar 
Author URI: http://wordpressnutsandbolts.blogspot.in/
Version: 1.0
*/
		
// add WP Quick Contact menu in admin settings

define('WP_QUICK_CONTACT','wp-quick-contact-us');
define('WP_QUICK_CONTACT_PATH',plugins_url(WP_QUICK_CONTACT));
define('SITE_ADMIN_URL',get_admin_url());

add_action( 'init', 'wp_quick_contact_init' );
global $wpdb;

function wp_quick_contact_init(){
	add_action( 'admin_menu', 'wp_quick_contact_admin_menu' );
}

function wp_quick_contact_admin_menu() {
	global $wpdb;
	add_menu_page('WP Quick Contact', 'Quick Contact', 'manage_options', 'wp-quick-contact', 'wp_quick_contact_admin', WP_QUICK_CONTACT_PATH.'/images/icon.png');
}

// add admin style
add_action('admin_print_styles', 'wp_quick_contact_admin_css');
function wp_quick_contact_admin_css(){
	wp_register_style('wp-quick-contact-admin-style', WP_QUICK_CONTACT_PATH .'/css/admin-style.css');
	wp_enqueue_style('wp-quick-contact-admin-style');
}

//add admin script
add_action('wp_print_scripts', 'wp_quick_contact_admin_script');
function wp_quick_contact_admin_script(){
	if(is_admin() && $_GET['page'] == 'wp-quick-contact'){
		wp_enqueue_script('jquery');
	}
}

//add user script and styles
add_action('wp_head', 'wp_quick_contact_user_script');
function wp_quick_contact_user_script(){
	wp_enqueue_script("jquery");
	
	wp_register_script('wp-quick-contact-user-script', WP_QUICK_CONTACT_PATH .'/js/script.js');
	wp_localize_script('wp-quick-contact-user-script','WPCVajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script('wp-quick-contact-user-script');

	wp_register_style('wp-quick-contact-user-style', WP_QUICK_CONTACT_PATH .'/css/style.css');
	wp_enqueue_style('wp-quick-contact-user-style');
}

// add WP Quick Contact Admin Area
function wp_quick_contact_admin(){
?>

<div class="wrap">
  <div class="wp-quick-contact-icon"><br>
  </div>
  <h2>WP Quick Contact</h2>
  <div class="wp-quick-contact-left">
    <form method="post" action="">
      <input type="hidden" name="process_wp_quick_contact" value="process" />
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Write your message to display:</h3>
          <p>
          <div class="wp-quick-contact-textarea">
            <table border="0">
              <tr>
                <td>To email ID</td>
                <td>:</td>
                <td><label>
                  <input type="text" name="wp_quick_contact_toemail" size="30" value="<?php echo get_option('wp_quick_contact_toemail'); ?>"/>
                  </label></td>
              </tr>
              <tr>
                <td>Bcc</td>
                <td>:</td>
                <td><input type="text" name="wp_quick_contact_bcc" size="30" value="<?php echo get_option('wp_quick_contact_bcc'); ?>"/></td>
              </tr>
              <tr>
                <td>Message Title</td>
                <td>:</td>
                <td><input type="text" name="wp_quick_contact_title" size="30" value="<?php echo get_option('wp_quick_contact_title'); ?>"/></td>
              </tr>
            </table>
          </div>
          </p>
        </div>
      </div>
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Disply Settings:</h3>
          <p>
          <div class="wp-quick-contact-textarea">
            <table border="0">
              <tr>
                <td>Show the WP Quick Contact on site</td>
                <td>:</td>
                <td><input type="radio" name="wp_quick_contact_show_on_site" value="yes" <?php if(get_option('wp_quick_contact_show_on_site') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="wp_quick_contact_show_on_site" value="no" <?php if(get_option('wp_quick_contact_show_on_site') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
              <tr>
                <td>Show on home page</td>
                <td>:</td>
                <td><input type="radio" name="wp_quick_contact_show_on_home" value="yes" <?php if(get_option('wp_quick_contact_show_on_home') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="wp_quick_contact_show_on_home" value="no" <?php if(get_option('wp_quick_contact_show_on_home') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
              <tr>
                <td>Show on all page</td>
                <td>:</td>
                <td><input type="radio" name="wp_quick_contact_show_on_page" value="yes" <?php if(get_option('wp_quick_contact_show_on_page') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="wp_quick_contact_show_on_page" value="no" <?php if(get_option('wp_quick_contact_show_on_page') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
              <tr>
                <td>Show on all posts</td>
                <td>:</td>
                <td><input type="radio" name="wp_quick_contact_show_on_post" value="yes" <?php if(get_option('wp_quick_contact_show_on_post') == 'yes'){ echo 'checked="checked"'; } ?> />
                  Yes
                  <input type="radio" name="wp_quick_contact_show_on_post" value="no" <?php if(get_option('wp_quick_contact_show_on_post') == 'no'){ echo 'checked="checked"'; } ?> />
                  No </td>
              </tr>
            </table>
          </div>
          </p>
        </div>
      </div>
      <div class="metabox-holder" style="width: 100%;float:left;">
        <div class="postbox">
          <h3>Save Changes:</h3>
          <p>
          <div class="wp-quick-contact-textarea">
            <table border="0">
              <tr>
                <td>Save the changes</td>
              </tr>
              <tr>
                <td><input type="submit" value="Save Changes " class="button-primary" name="submit"></td>
              </tr>
            </table>
          </div>
          </p>
        </div>
      </div>
    </form>
  </div>
  <div class="wp-quick-contact-right">
    <div class="metabox-holder" style="width: 100%;float:left;">
      <div class="postbox">
        <h3>Developer Info:</h3>
        <p>
        <div class="wp-quick-contact-textarea" style="text-align:center;"><a href="http://dmitritech.com/" target="_blank" title="dmitri tech solution"><img src="<?php echo WP_QUICK_CONTACT_PATH; ?>/images/dmitri-logo.png" alt="dmitri tech solution" /></a></div>
        </p>
      </div>
    </div>
    <div class="metabox-holder" style="width: 100%;float:left;">
      <div class="postbox">
        <h3>Join with us:</h3>
        <p>
        <div class="wp-quick-contact-textarea" style="text-align:center;">
          <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fdmitritechs&amp;width=280&amp;height=300&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:280px; height:300px;" allowTransparency="true"></iframe>
        </div>
        </p>
      </div>
    </div>
    <div class="metabox-holder" style="width: 100%;float:left;">
      <div class="postbox">
        <h3>The Wordpress Development Company:</h3>
        <p>
        <div class="wp-quick-contact-textarea" style="text-align:center;"> <a href="http://www.hirewordpressguru.com/" target="_blank" title="Hire Wordpress Guru - A wordpress development Company"><img src="<?php echo WP_QUICK_CONTACT_PATH; ?>/images/hire-wordpress-guru.jpg" alt="Hire Wordpress Guru" /></a> </div>
        </p>
      </div>
    </div>
  </div>
</div>
<?php
}

// set the options 
register_activation_hook(__FILE__,'set_wp_quick_contact_options');
function set_wp_quick_contact_options(){
	add_option('wp_quick_contact_toemail','','');
	add_option('wp_quick_contact_bcc','','');
	add_option('wp_quick_contact_title','','');
	
	add_option('wp_quick_contact_show_on_site','yes','');
	add_option('wp_quick_contact_show_on_home','yes','');
	add_option('wp_quick_contact_show_on_page','yes','');
	add_option('wp_quick_contact_show_on_post','yes','');
	
}

// reset the options
register_uninstall_hook(__FILE__,'unset_wp_quick_contact_options');
function unset_wp_quick_contact_options(){ 
	delete_option('wp_quick_contact_toemail');
	delete_option('wp_quick_contact_bcc');
	delete_option('wp_quick_contact_title');
	
	delete_option('wp_quick_contact_show_on_site');
	delete_option('wp_quick_contact_show_on_home');
	delete_option('wp_quick_contact_show_on_page');
	delete_option('wp_quick_contact_show_on_post');
	
}

//add user scripta and styles
add_action('wp_head', 'wp_quick_contact_user_script_style');
function wp_quick_contact_user_script_style(){
	wp_enqueue_script("jquery");
	
	wp_register_style('wp-quick-contact-style', WP_QUICK_CONTACT_PATH.'/css/style.css');
	wp_enqueue_style('wp-quick-contact-style');
}

// processing the form
if($_POST['process_wp_quick_contact'] == "process") { 
   update_option('wp_quick_contact_toemail',$_REQUEST['wp_quick_contact_toemail']);
   update_option('wp_quick_contact_bcc',$_REQUEST['wp_quick_contact_bcc']);
   update_option('wp_quick_contact_title',$_REQUEST['wp_quick_contact_title']);
   
   update_option('wp_quick_contact_show_on_site',$_REQUEST['wp_quick_contact_show_on_site']);
   update_option('wp_quick_contact_show_on_home',$_REQUEST['wp_quick_contact_show_on_home']);
   update_option('wp_quick_contact_show_on_page',$_REQUEST['wp_quick_contact_show_on_page']);
   update_option('wp_quick_contact_show_on_post',$_REQUEST['wp_quick_contact_show_on_post']);
}

//add Contact Form
add_action('wp_footer', 'wp_quick_contact_add_show_contact');
function wp_quick_contact_add_show_contact(){
	if(get_option('wp_quick_contact_show_on_site') == 'yes'){
	    $display    = true;
		$homeOption = get_option('wp_quick_contact_show_on_home');
		$pageOption = get_option('wp_quick_contact_show_on_page');
		$postOption = get_option('wp_quick_contact_show_on_post');
		
		$getID = get_the_ID();
		
		if($homeOption == 'yes' && is_home()){
		  $display =  true ;
		}elseif($pageOption == 'yes' && is_page()){
		  $display =  true ;
		}elseif($postOption == 'yes' && is_single()){
		  $display =  true ;
		}else{
		  $display =  false ;
		}
		if($display){
	?>
<div class="wp-quick-contact-outer">
<form method="post" name="wp-quick-contact-form" id="wp-quick-contact-form">
  <div class="wp-quick-contact-inner">
    <div class="wp-quick-contact-head">Quick Contact <a class="slide-indicate">^</a> </div>
    <div class="wp-quick-contact-area">
      <div class='wpqc-success'>We want to thank you for contacting us through our website and let you know we have received your information. A member of our team will be promptly respond back to you.</div>
      <div class="wp-quick-line">
        <div class="wp-quick-field"><input name="cname" type="text" id="cname" onblur="if(this.value=='')this.value='Full Name'" onfocus="if(this.value=='Full Name')this.value=''" value="Full Name" /></div>
      </div>
      <div class="wp-quick-line">
        <div class="wp-quick-field"><input name="cphone" type="text" id="cphone" onblur="if(this.value=='')this.value='Phone Number'" onfocus="if(this.value=='Phone Number')this.value=''" value="Phone Number"/></div>
      </div>
      <div class="wp-quick-line">
        <div class="wp-quick-field"><input name="cemail" type="text" id="cemail" onblur="if(this.value=='')this.value='Email Address'" onfocus="if(this.value=='Email Address')this.value=''" value="Email Address"/></div>
      </div>
      <div class="wp-quick-line">
        <div class="wp-quick-field"><input name="csubject" type="text" id="csubject" onblur="if(this.value=='')this.value='Subject'" onfocus="if(this.value=='Subject')this.value=''" value="Subject"/></div>
      </div>
      <div class="wp-quick-line">
        <div class="wp-quick-field"><textarea name="cmsg" id="cmsg" onblur="if(this.value=='')this.value='Message'" onfocus="if(this.value=='Message')this.value=''">Message</textarea></div>
      </div>
      <div class="wp-quick-line">
        <input type="hidden" name="mail" value="sent" />
        <div class="wp-quick-submit"><input type="submit" name="submit" value="SEND" /></div><div class="wp-qc-loading"><img src="<?php echo WP_QUICK_CONTACT_PATH;?>/images/loading.gif"></div>
      </div>
    </div>
  </div>
 </form>
</div>
<?php
	  }
	}
}


/*--------------------------------------------------------------------------------------*/
/*                                      AJAX HANDLING FUNCTION                          */
/*--------------------------------------------------------------------------------------*/

// creating Ajax call for WordPress  
add_action( 'wp_ajax_nopriv_wpQuickContact', 'wpQuickContact' );  
add_action( 'wp_ajax_wpQuickContact', 'wpQuickContact' );  

function wpQuickContact(){
 header('Content-type: application/json');
 if(isset($_POST) && $_POST['mail'] == 'sent'){
	extract($_POST);
	$error  = array();
	$result = '';
	if(!$cname || $cname == 'Full Name' ){
		$error[] = array('id'=>'#cname','msg'=>'error');
	}
	
	if(!$cphone || $cphone == 'Phone Number' ){
		$error[] = array('id'=>'#cphone','msg'=>'error');
	}
		
	if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($cemail))){
		$error[] = array('id'=>'#cemail','msg'=>'error');
	}
	
	if(!$csubject || $csubject == 'Subject' ){
		$error[] = array('id'=>'#csubject','msg'=>'error');
	}	
	
	if(!$cmsg || $cmsg == 'Message' ){
		$error[] = array('id'=>'#cmsg','msg'=>'error');
	}	
	
	if(empty($error)){
		$result  ='OK';
		$emailTo = get_option('wp_quick_contact_toemail');
		$bcc     = get_option('wp_quick_contact_bcc');
		$subject = $csubject;
		$title   = get_option('wp_quick_contact_title');
		$body    ='<html>
<body>
<table width="100%" height="241" border="0" cellpadding="4" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;">
  <tr>
    <td height="30" colspan="4"><strong>'.$title.'</strong></td>
  </tr>
  <tr>
    <td width="487"  height="21">Full Name</td>
    <td width="1">:</td>
    <td width="734">'.$cname.'</td>
  </tr>
  <tr>
    <td width="487"  height="21">Phone</td>
    <td width="1">:</td>
    <td width="734">'.$cphone.'</td>
  </tr>
  <tr>
    <td height="21">Email</td>
    <td>:</td>
    <td>'.$cemail.'</td>
  </tr>
  <tr>
    <td height="21">Message</td>
    <td>:</td>
    <td>'.$cmsg.'</td>
  </tr>
  <tr>
    <td colspan="3" height="5"></td>
  </tr>
</table>
</body>
</html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		if($bcc != ''){
			$headers .= 'Bcc: <'.$bcc.'>' . "\r\n"; 
		}
		$headers .= 'From:'.$cname.'<'.$cemail.">\r\n" .'Reply-To:<'.$cemail.">\r\n".'X-Mailer: PHP/'.phpversion();
		$suc      =  mail($emailTo,$subject,$body,$headers);
	}else{
	  $result ='ERROR';
	}
	$arr = array("result" => $result, "errors" => $error);
	echo json_encode($arr);
}	
die();
}
?>
