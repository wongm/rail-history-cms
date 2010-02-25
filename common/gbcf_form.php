
<!-- BEGIN: UNIVERSAL FORM BODY, THIS IS WHAT'S "INCLUDED" -->
<?php #########################################################
// Secure and Accessible PHP Contact Form v.2.0 by Mike Cherim
// There should be no need to edit anything in this file
###############################################################
// Get the config and set the form version
    $lock = "on";
   require_once("gbcf_config.php");
    $form_version = "v.2.0"; 
    $build = "20070414";

// Fix cases to prevent admin config errors
     $gb_possession = strtolower($gb_possession);
     $x_or_html = strtolower($x_or_html);
     $showcredit = strtolower($showcredit);
     $showprivacy = strtolower($showprivacy);

// Possession management conditions begin
if($gb_possession == "pers") {
     $i_or_we = "I";
     $me_or_us = "me";;
     $my_or_our = "my";
} else if ($gb_possession == "org") {
     $i_or_we = "we";
     $me_or_us = "us";
     $my_or_our = "our";
 } else {
     $i_or_we = "I";
     $me_or_us = "me";
     $my_or_our = "my";
}

// X/HTML choice negotiation
if($x_or_html == "xhtml") {
     $x_or_h_br = "<br />";
     $x_or_h_in = " /";
} else if($x_or_html == "html") {
     $x_or_h_br = "<br>";
     $x_or_h_in = "";
} else {
     $x_or_h_br = "<br />";
     $x_or_h_in = " /";
}

// Unique ID generators (random values would require a session)
     $fl = "$form_location";
     $fv = "$form_version";
     $fp = "$gb_possession";
     $fd = date("TOZ");

// The Pierre Modification
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
     $fh = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else{
     $fh = gethostbyaddr($_SERVER['REMOTE_ADDR']);
}

     $form_id = ''.$fd.''.$fp.''.$fl.''.$fv.''.$fh.'';
     $trap1_value = ''.$fp.''.$fv.''.$fh.''.$fl.''.$fd.'';
     $send_value = ''.$fh.''.$fd.''.$fv.''.$fp.''.$fl.'';
     $form_id = strtoupper(trim(rtrim(str_replace(array("&", "/", "#", "\\", ":", "%", "|", "^", ";", "@", "?", "+", "$", ".", "~", "-", "=", "_", " ",), 'PjT31cXa', $form_id)))); 
     $trap1_value = strtoupper(trim(rtrim(str_replace(array("&", "/", "#", "\\", ":", "%", "|", "^", ";", "@", "?", "+", "$", ".", "~", "-", "=", "_", " ",), 'Hr2WgPmz', $trap1_value)))); 
     $send_value = strtoupper(trim(rtrim(str_replace(array("&", "/", "#", "\\", ":", "%", "|", "^", ";", "@", "?", "+", "$", ".", "~", "-", "=", "_", " ",), 'Li8s7bkd', $send_value)))); 
     $send_value = "GB$send_value";

    echo'<div id="gb_form_div"><!-- BEGIN: Secure and Accessible PHP Contact Form '.$form_version.' by Mike Cherim (http://green-beast.com/) -->'."\n";

  if ($_POST) {

// Posted variables
     $name = $_POST['name'];           
     $email = $_POST['email'];      
     $phone = '';//$_POST['phone'];     
     $url = '';//$_POST['url'];
     $reason = '';//$_POST['reason'];       
     $message = $_POST['message'];     
     $formid = $_POST['GB'.$form_id.''];
     $trap1 = $_POST['GB'.$trap1_value.''];
     $trap2 = $_POST['p-mail'];
     $spamq = '';//$_POST['spamq']; 
     $gbcc = @$_POST['gbcc'];
     $ltd = date("l, F jS, Y \\a\\t g:i a", time()+$time_offset*60*60);
     $ip = getenv("REMOTE_ADDR");
     $hr = $_POST['referer'];
     $hst = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
     $ua = $_SERVER['HTTP_USER_AGENT'];

// Strip slashes, html, php, binary, and scrub posted vars
     $name = stripslashes(strip_tags(trim($name)));
     $email = stripslashes(strip_tags(trim(strtolower($email))));
     $phone = stripslashes(strip_tags(trim($phone)));
     $url = stripslashes(strip_tags(trim($url)));
     $reason = stripslashes(strip_tags(trim($reason)));
     $message = stripslashes(strip_tags(trim($message)));
     $spamq = strtolower(trim($spamq));
     $gb_randoma = strtolower(trim($gb_randoma));
     $ltd = stripslashes(strip_tags(trim($ltd)));
     $ip = stripslashes(strip_tags(trim($ip)));
     $hr = stripslashes(strip_tags(trim($hr)));
     $hst = stripslashes(strip_tags(trim($hst)));
     $ua = stripslashes(strip_tags(trim($ua)));
     $formid = stripslashes(strip_tags(trim($formid)));
     $send_value = stripslashes(strip_tags(trim($send_value)));

// Email header
     $gb_email_header = "From: $gb_email_address\n"."Reply-To: $email\n"."MIME-Version: 1.0\n"."Content-type: text/plain; charset=\"utf-8\"\n"."Content-transfer-encoding: quoted-printable\n\n"; 

// Strip more html, php, and binary, then scrub 
     $gb_email_header = stripslashes(strip_tags(trim($gb_email_header)));

// Identify exploits
     $head_expl = "/(bcc:|cc:|document.cookie|document.write|onclick|onload)/i";
     $inpt_expl = "/(content-type|to:|bcc:|cc:|document.cookie|document.write|onclick|onload)/i";

// Modify referrer to counter bogus www/no.www mismatch errors
     $form_location = strtolower(trim(rtrim(str_replace(array("http", "www", "&", "/", "#", "\\", ":", "%", "|", "^", ";", "@", "?", "+", "$", ".", "~", "-", "=", "_", " ",), '', $form_location)))); 
     $new_referrer = strtolower(trim(rtrim(str_replace(array("http", "www", "&", "/", "#", "\\", ":", "%", "|", "^", ";", "@", "?", "+", "$", ".", "~", "-", "=", "_", " ",), '', $_SERVER['HTTP_REFERER'])))); 

// Carbon Copy request negotiation
if($gbcc == "gbcc") {
     $gb_cc = ", $email";
     $cc_notify1 = "".$x_or_h_br."<small>(A carbon copy has also been sent to this address.)</small>";
     $cc_notify2 = "(Copy sent)";
     $cc_notify3 = "";
} else {
     $gb_cc = "";
     $cc_notify1 = ""; 
     $cc_notify2 = ""; 
     $cc_notify3 = "";
} 
// check for blank email
if(!isset($email) || empty($email)){
	$email = 'railgeeelong@gmail.com';
}

// Required fields need stuffing or get an error showing fields needed
if(!isset($name,$email,/*$reason,*/$message/*,$spamq*/) || empty($name) || empty($email) || /*empty($reason) || */empty($message) /*|| empty($spamq)*/){
     echo('   <p class="error">Error - Required Field(s) Missing</p><p> The following required fields were not filled in. Using your "Back" button, please go back and fill in all required fields.</p>');
     echo('      <dl>'."\n");
     echo('       <dt>Empty Field(s):</dt>'."\n");
if(empty($name)) { 
     echo('        <dd>"Enter your name"</dd>'."\n"); 
}
if(empty($email)) { 
     echo('        <dd>"Enter your email address"</dd>'."\n"); 
}
/*if(empty($reason)) { 
     echo('        <dd>&#8220;Select a contact reason&#8221;</dd>'."\n"); 
}*/
if(empty($message)) { 
     echo('        <dd>"Enter your message"</dd>'."\n"); 
}
//if(empty($spamq)) { 
     //echo('        <dd>&#8220;'.$gb_randomq.'&#8221;</dd>'."\n"); 
//}
     echo('      </dl>'."\n");
} else {

// Or the email doesn't seem to be properly formed or has illegal email characters
if(!ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})", "$email")) {
     echo('   <p class="error">Error - Invalid Email Address</p><p>The email address you have submitted seems to be invalid. Using your "Back" button, please go back and check the address you entered. You can just leave it blank if you want to ;-) .</p>');

// Anti-spam trap 1
} else if($trap1 !== "") {
     echo('   <p class="error">Error - Anti-Spam Trap 1 Field Populated</p><p>You populated a spam trap anti-spam input so you must be a spambot. Go away!</p>'."\n");

// Anti-spam trap 2
} else if($trap2 !== "") {
     echo('   <p class="error">Error - Anti-Spam Trap 2 Field Populated</p><p>You populated a spam trap anti-spam input that is meant to confuse automated spam-sending machines. If you accidently entered data in this field, using your &#8220;Back&#8221; button, please go back and remove it before submitting this form. Sorry for the confusion.</p>'."\n");

// Input length error tripping
} else if(strlen($name) > 40 || strlen($email) > 40 || strlen($phone) > 30 || strlen($url) > 60 || strlen($gbcc) > 4) {
	echo('   <p class="error">Error - Input Maxlength Violation</p><p>Certain inputs have been populated beyond that which is allowed by the form. Therefore you must be trying to post remotely and are probably a spambot. Go away!</p>'."\n");

// Contact reason validation
//} else if(!in_array($reason, $gb_options)) { 
       //echo('   <h'.$gb_heading.' class="formhead" id="results">Results: <span class="error">'.$error_heading.'</span></h'.$gb_heading.'>
     //<p><span class="error">Contact Reason Violation:</span> You have tried to post a &#8220;Contact Reason&#8221; which doesn&#8217;t exist in '.$my_or_our.' menu. Therefore you must be trying to post remotely and are probably a spambot. Go away!</p>'."\n");

// Check the IP black list
} else if(in_array($ip, $ip_blacklist)) { 
	echo('   <p class="error">Error - Blacklisted IP Address</p><p>Sorry, but your IP address has been blocked. Perhaps you have abused your form submission privileges in the past. If you&#8217;ve sent spam to '.$me_or_us.' in the past, this could be the reason.</p>'."\n");

// Form value confirmation
} else if($formid !== "GB".$form_id."") {
	echo('   <p class="error">Error - Form ID Value Mismatch</p><p>The submitted ID does not match registered ID of this form which means you&#8217;re trying to post remotely so this mean you must be a spambot. Go away!</p>'."\n");

// My long version of Jem's exploit killer
} else if(preg_match($head_expl, $gb_email_header) || preg_match($inpt_expl, $name) || preg_match($inpt_expl, $email) || preg_match($inpt_expl, $phone) || preg_match($inpt_expl, $url) || preg_match($inpt_expl, $message)) {
     echo('   <h'.$gb_heading.' class="formhead" id="results">Results: <span class="error">'.$error_heading.'</span></h'.$gb_heading.'>
     <p><span class="error">Injection Exploit Detected:</span> It seems that you&#8217;re possibly trying to apply a header or input injection exploit in '.$my_or_our.' form. If you are, please stop at once! If not, using your &#8220;Back&#8221; button, please go back and check to make sure you haven&#8217;t entered <strong>content-type</strong>, <strong>to:</strong>, <strong>bcc:</strong>, <strong>cc:</strong>, <strong>document.cookie</strong>, <strong>document.write</strong>, <strong>onclick</strong>, or <strong>onload</strong> in any of the form inputs. If you have and you&#8217;re trying to send a legitimate message, for security reasons, please find another way of communicating these terms.</p>'."\n");

// Let match the referrer to ensure it's sent from here and not elsewhere
} else if($new_referrer !== $form_location) {
echo('   <p class="error">Error - Referrer Missing or Mismatch</p><p>It looks like you&#8217;re trying to post remotely or you have blocked referrers on your user agent or browser. Using your &#8220;Back&#8221; button, please go back and try again or use '.$my_or_our.' regular email, <a href="mailto:'.$gb_email_address.'?subject='.$gb_website_name.'%20Backup%20Email%20[Referrer Missing or Mismatch]">'.$gb_email_address.'</a>, to circumvent Referrer Mismatch.</p><p><small><strong class="error">Attention Site Admin:</strong> Be sure to double check the last section in the form&#8217;s configuration file and edit accordingly. If &#8220;Form Location&#8221; is manually entered, make sure it matches the page URL <em>exactly</em> &#8212; as seen on your browser&#8217;s address bar. A misconfigured URL is typically the cause of this error.</small></p>'."\n");

// Anti-spam verification
//} else if($spamq !== "$gb_randoma") {
     //echo('   <h'.$gb_heading.' class="formhead" id="results">Results: <span class="error">'.$error_heading.'</span></h'.$gb_heading.'>
     //<p><span class="error">Anti-Spam Question/Answer Mismatch:</span> The answer you supplied to the anti-spam question is incorrect. Using your &#8220;Back&#8221; button, please go back and try again or use '.$my_or_our.' regular email, <a href="mailto:'.$gb_email_address.'?subject='.$gb_website_name.'%20Backup%20Email%20[Anti-Spam Question/Answer Mismatch]">'.$gb_email_address.'</a>, if having Anti-Spam question difficulty.</p>'."\n");

// And now let's see if the variable for submit matches what's required
} else if(!(isset($_POST[''.$send_value.'']))) {
echo('   <p class="error">Error - Submit Variable Mismatch</p><p>It looks like you&#8217;re trying to post remotely as the submit variable is unmatched. Using your &#8220;Back&#8221; button, please go back and try again  or try '.$my_or_our.' regular email, <a href="mailto:'.$gb_email_address.'?subject='.$gb_website_name.'%20Backup%20Email%20[Submit Variable Mismatch]">'.$gb_email_address.'</a>, to circumvent Variable Mismatch.</p>'."\n");

// Holy smokes, looks like all's cool and we can send the message
} else {
     $gb_content = "Hello $gb_contact_name,\n\nYou are being contacted via $gb_website_name by $name. $name has provided the following information so you may contact them:\n\n   Email: $email $cc_notify2\n   Message:\n   $message\n\n\n--------------------------\nOther Data and Information:\n   IP Address: $ip\n   Time Stamp: $ltd\n   Referrer: $hr\n   Host: $hst\n   User Agent: $ua\n   Resolve IP Whois: http://www.arin.net/whois/\n\n";
     //echo str_replace("\n","<br>",$gb_content);
     $gb_ccmail = "Hello $name,\n\nThis is a copy of the email you sent to $gb_website_name. You successfully sent the following information:\n\n   Email: $email $cc_notify3\n   Message:\n   $message\n\n\n--------------------------\nOther Data and Information:\n   Time Stamp: $ltd\n\n";
     //echo str_replace("\n","<br>",$gb_ccmail);

// Remove tags and slashes from content-including header then trim it again
     $gb_content = stripslashes(strip_tags(trim($gb_content)));
     $gb_ccmail = stripslashes(strip_tags(trim($gb_ccmail)));

// The mail function helps, let's send this stuff
// Marcus edit - mail server address
	ini_set("SMTP","mail.optusnet.com.au");
	ini_set(sendmail_from,$gb_email_address);
	mail("$gb_email_address", "[$gb_website_name] Contact from $name", $gb_content, $gb_email_header);

if($gb_cc !== "") {
     mail("$gb_cc", "[Copy] Email sent to $gb_website_name", $gb_ccmail, $gb_email_header);
}

// And let's inform the user and show them what they sent
     echo('   <h'.$gb_heading.' class="formhead" id="results">Results: <span class="success">'.$success_heading.'</span> <small>[ <a href="'.$hr.'">Reset Form</a> ]</small></h'.$gb_heading.'>
    <p><span class="success">Message Sent:</span> You have successfully sent a message to '.$me_or_us.', '.$name.'. You submitted the following information:</p> 
     <ul>
      <li><span class="items">Name:</span> '.$name.'</li>');
      
if($email != 'railgeeelong@gmail.com')
{   
	echo '<li><span class="items">Email:</span> <a href="mailto:'.$email.'">'.$email.'</a> '.$cc_notify1.'</li>';
}
     echo( '</ul>
    <dl id="result_dl_blockq">
      <dt>Message:</dt>
       <dd>
        <blockquote cite="'.$hr.'">
         <p>'.$message.'</p>
         <p><cite>&#8212;'.$name.'</cite></p>
        </blockquote>
       </dd>
     </dl>
     <dl>
      <dt><small>Time Stamp:</small></dt>
       <dd><small>Form Submitted: '.$ltd.'</small></dd>
     </dl>'."\n");
  }
 }
} else { 
// No errors so far? No successes so far? No confirmation? Hmm. Maybe the user needs a contact form
?>
 <h<?php echo(''.$gb_heading.''); ?> class="main_formhead"><?php echo(''.$gb_website_name.''); ?> Contact Form</h<?php echo(''.$gb_heading.''); ?>>
<?php
if(!function_exists('mail')) {
    echo('<p><strong class="error">Warning!</strong> It seems that the <abbr><span class="abbr" title="PHP Hypertext Preprocessor">PHP</span></abbr> <strong>mail()</strong> function isn&#8217;t enabled on your server. Sorry, but to use this plugin this function must be enabled. Please contact your web hosting provider to ask if they will enable this function for your domain. Optionally, should your web hosting provider deny your request, you may want to try this <a href="http://mikecherim.com/experiments/php_email_protector.php">PHP Email Protector</a> script.</p>');
} ?>
   <form id="gb_form" method="post" action="<?php echo(''.htmlentities($_SERVER["PHP_SELF"]).''); ?>#results">
   <input type="hidden" name="referer" id="referer" value="<? echo str_replace("=", "=3D", str_replace("&", "&amp;", $_SERVER["HTTP_REFERER"])); ?>" />
   <input type="hidden" name="ipaddress" id="ipaddress" value="<? echo $_SERVER['REMOTE_ADDR']; ?>" />
<!-- Form Intro -->
   <fieldset id="formwrap">
      <legend id="mainlegend" style="cursor:help;" title="Note: Code and markup will be removed from all fields!">Contact <?php echo(''.$me_or_us.''); ?>
<?php 
if($showprivacy == "yes") {
      echo('   <small class="privacy">[&nbsp;<a tabindex="'.$tab_privacy.'" href="'.$privacyurl.'" title="Review '.$my_or_our.' privacy policy">Privacy</a>&nbsp;]</small></legend>'); 
} else {
      echo('</legend>');
}
?> 
<!-- Required Info -->
     <? /*   <fieldset>
       <legend>Contact info:</legend> */ 
       echo '<table><tr><td>Referer: </td><td>'.$_SERVER["HTTP_REFERER"].'</td></tr>';
       ?>
        <tr><td><label for="name">Name: </label></td><td> <input tabindex="<?php echo(''.$tab_name.''); ?>" class="med" type="text" name="name" id="name" size="35" maxlength="40" value="" /></td></tr>
<? 

/*      </fieldset>
<!-- Optional Info -->
      /<fieldset>
       <legend>Optional contact info:</legend>
        <label for="phone">Enter your phone number<?php echo(''.$x_or_h_br.''); ?><input tabindex="<?php echo(''.$tab_phone.''); ?>" class="med" type="text" name="phone" id="phone" size="35" maxlength="30" value=""<?php echo(''.$x_or_h_in.''); ?>></label><?php echo(''.$x_or_h_br.''); ?> 
        <label for="url">Enter your website address<?php echo(''.$x_or_h_br.''); ?><input tabindex="<?php echo(''.$tab_url.''); ?>" class="med" type="text" name="url" id="url" size="35" maxlength="60" value="http://"<?php echo(''.$x_or_h_in.''); ?>></label>
      </fieldset>
<!-- Required Form Options -->
      <fieldset>
       <legend>Required contact reason:</legend>
        <label for="reason">Select a contact reason<?php echo(''.$x_or_h_br.''); ?> 
         <select tabindex="<?php echo(''.$tab_reason.''); ?>" class="med" style="cursor:pointer;" name="reason" id="reason">
          <option value="" selected="selected">Please make a selection</option> 
<?php
    reset($gb_options);
  while (list(, $gb_opts) = each($gb_options)) {
echo('          <option value="'.$gb_opts.'">'.$gb_opts.'</option>'."\n"); 
} 
?>
         </select>
        </label>
       </fieldset>
<!-- Required Form Comments Area -->
      <fieldset>
       <legend>Comments:</legend>*/?>
        <tr><td><label for="message">Message: </label></td><td><textarea tabindex="<?php echo(''.$tab_message.''); ?>" class="textbox" rows="12" cols="60" name="message" id="message"></textarea></td></tr>
        <tr><td><label for="email">Email address <br/>(optional): </label></td><td><input tabindex="<?php echo(''.$tab_email.''); ?>" class="med" type="text" name="email" id="email" size="35" maxlength="40" value=""<?php echo(''.$x_or_h_in.''); ?>>
<? 	/*        </fieldset>
    
<!-- Required anti spam confirmation -->
      <fieldset>
       <legend>Required anti-spam question:</legend>
        <label title="No worries, the text entered here is case-insensitive" for="spamq"><?php echo(''.$gb_randomq.''); ?> <input tabindex="<?php echo(''.$tab_spam.''); ?>" class="short" type="text" name="spamq" id="spamq" size="15" maxlength="30" value=""<?php echo(''.$x_or_h_in.''); ?>> <small class="whythis" title="This confirms you're a human user">- <a tabindex="<?php echo(''.$tab_why.''); ?>" href="#spamq" style="cursor:help;">Why ask? <span>This confirms you&#8217;re a human user.</span></a></small></label><?php echo(''.$x_or_h_br.''); ?> 
     */ ?>
<!-- Special anti-spam input: hidden type -->
        <input type="hidden" name="GB<?php echo(''.$trap1_value.''); ?>" id="GB<?php echo(''.$trap1_value.''); ?>" alt="Cherim-Hartmann Anti-Spam Trap One" value=""<?php echo(''.$x_or_h_in.''); ?>>
<!-- Special anti-spam input: non-displayed type -->
       <div style="position:absolute; top: -9000px; left:-9000px;"><?php echo(''.$x_or_h_br.''); ?> 
        <label for="p-mail"><small><strong>Note:</strong> The input below should <em>not</em> be filled in. It is a spam trap. Please ignore it. If you populate this input, the form will return an error.</small><?php echo(''.$x_or_h_br.''); ?> 
        <input type="text" name="p-mail" id="p-mail" alt="Cherim-Hartmann Anti-Spam Trap Two" value=""<?php echo(''.$x_or_h_in.''); ?>>
        </label>
       </div>
<!-- Special anti-spam form id field -->
        <input type="hidden" name="GB<?php echo(''.$form_id.''); ?>" id="GB<?php echo(''.$form_id.''); ?>" alt="Form ID Field" value="GB<?php echo(''.$form_id.''); ?>"<?php echo(''.$x_or_h_in.''); ?>>
    <? /*  </fieldset>
<!-- Form Buttons -->
      <fieldset>
       <legend>Time to send it to <?php echo(''.$me_or_us.''); ?>:</legend> */ ?>
<?php if(@$show_cc == "yes") {
    echo('         </td></tr><tr><td><label for="gbcc"><input tabindex="'.$tab_cc.'" class="checkbox" type="checkbox" name="gbcc" id="gbcc" value="gbcc"'.$x_or_h_in.'> <small>Check this box if you want a carbon copy of this email.</small></label>'); 
} else {
    echo(''."\n");
}

echo '</td></tr></table>';

?>          <input tabindex="<?php echo(''.$tab_submit.''); ?>" style="cursor:pointer;" class="button" type="submit" alt="Click Button to <?php echo(''.$send_button.''); ?>" value="<?php echo(''.$send_button.''); ?>" name="<?php echo(''.$send_value.''); ?>" id="<?php echo(''.$send_value.''); ?>" title="Click Button to Submit Form"<?php echo(''.$x_or_h_in.''); ?>> 
<?php 

if(@$showcredit == "yes") {
      echo('          <p class="creditline"><small style="cursor:help;">Secure and Accessible <abbr><span class="abbr" title="PHP Hypertext Preprocessor">PHP</span></abbr> Contact Form <span title="'.$build.'">'.$form_version.'</span> by <a href="http://green-beast.com/">Mike Cherim</a>.</small></p>'."\n"); 
} else {
      echo('          <!--B'.$build.'-->'."\n");
}
?>      </fieldset>
  <? /*    </fieldset>	*/  ?>
  </form>
  
  
<?php 
}
     echo('</div><!-- END: Secure and Accessible PHP Contact Form '.$form_version.' by Mike Cherim (http://green-beast.com/) -->'."\n");
########################################################## ?>
<!-- END: UNIVERSAL FORM BODY, THIS IS WHAT'S "INCLUDED" -->
