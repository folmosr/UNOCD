<?php  
/** 
 * This is a component to send email from CakePHP using PHPMailer 
 * @link http://bakery.cakephp.org/articles/view/94 
 * @see http://bakery.cakephp.org/articles/view/94 
 */ 

class EmailComponent  extends Component
{ 
  /** 
   * Send email using SMTP Auth by default. 
   */ 
    var $from         = 'UNOCD'; 
    var $fromName     = 'Cake PHP-Mailer'; 
    var $smtpUserName = 'pedido@unocd.com';  // SMTP username 
    var $smtpPassword = '$unocd2013'; // SMTP password 
    var $smtpHostNames= 'mail.unocd.com';  // specify main and backup server 
    var $port = 465;
	var $smtpSecure = 'ssl';
	var $text_body = NULL; 
    var $html_body = NULL; 
    var $to = NULL; 
    var $toName = NULL; 
    var $subject = NULL; 
    var $cc = NULL; 
    var $bcc = NULL; 
     var $template = 'email/default'; 
    var $attachments = null; 
 
    var $controller; 

    function startup( Controller $controller ) { 
      $this->controller = $controller; 
    } 

    function bodyText() { 
    /** This is the body in plain text for non-HTML mail clients 
     */ 
     // ob_start(); 
      $temp_layout = $this->controller->layout; 
      $this->controller->layout = '';  // Turn off the layout wrapping 
      $mail = $this->controller->render($this->template . '_text');  
      //$mail = ob_get_clean(); 
      $this->controller->layout = $temp_layout; // Turn on layout wrapping again 
      return $mail; 
    } 

    function bodyHTML() { 
    /** This is HTML body text for HTML-enabled mail clients 
     */ 
      //ob_start(); 
      $temp_layout = $this->controller->layout; 
      $this->controller->layout = 'email';  //  HTML wrapper for my html email in /app/views/layouts 
	  $mail = $this->controller->render($this->template . '_html'); //  
      
	  //$mail = ob_get_clean(); 
      $this->controller->layout = $temp_layout; // Turn on layout wrapping again 
      
      return $mail; 
    } 

    function attach($filename, $asfile = '') { 
      if (empty($this->attachments)) { 
        $this->attachments = array(); 
        $this->attachments[0]['filename'] = $filename; 
        $this->attachments[0]['asfile'] = $asfile; 
      } else { 
        $count = count($this->attachments); 
        $this->attachments[$count+1]['filename'] = $filename; 
        $this->attachments[$count+1]['asfile'] = $asfile; 
      } 
    } 


    function send($recipients = NULL) 
    { 
      App::import('Vendor', 'PHPMailer', array('file' => 'phpmailer'.DS.'class.phpmailer.php'));  
      $mail = new PHPMailer(); 
      $mail->IsSMTP();            // set mailer to use SMTP 
      $mail->SMTPAuth = true;     // turn on SMTP authentication 
	  $mail->SMTPSecure = $this->smtpSecure ; // secure transfer enabled REQUIRED for GMail
  	  $mail->Port = $this->port; // or 587
      $mail->Host   = $this->smtpHostNames; 
      $mail->Username = $this->smtpUserName; 
      $mail->Password = $this->smtpPassword; 
  	  $mail->AddEmbeddedImage(realpath("img/unnamed.png"), "logo_unocd", "unnamed.png");
	  $mail->SetFrom($this->from, $this->fromName);
      if(count($recipients) > 0)
	  {
    	for($i = 0; $i < count($recipients); $i++):
		   if($recipients[$i]['Usuario']['rol_id']==1)
		  	 	$mail->AddAddress($recipients[$i]['Usuario']['correo'], $recipients[$i]['Usuario']['apellido']. ' '. $recipients[$i]['Usuario']['nombre']);
			 else
			 	$mail->AddCC($recipients[$i]['Usuario']['correo'], $recipients[$i]['Usuario']['apellido']. ' '. $recipients[$i]['Usuario']['nombre']);
		 endfor;
	  }else
	  	  $mail->AddAddress($this->to, $this->toName ); 
      $mail->CharSet  = 'UTF-8'; 
      $mail->WordWrap = 50;  // set word wrap to 50 characters 

    if (!empty($this->attachments)) { 
      foreach ($this->attachments as $attachment) { 
        if (empty($attachment['asfile'])) { 
          $mail->AddAttachment($attachment['filename']); 
        } else { 
          $mail->AddAttachment($attachment['filename'], $attachment['asfile']); 
        } 
      } 
    } 

    $mail->IsHTML(true);  // set email format to HTML 

    $mail->Subject = $this->subject; 
    $mail->Body    = $this->bodyHTML(); 
    $result = $mail->Send(); 
    if(!$result) 
		$result = $mail->ErrorInfo; 
    return $result; 
    } 
} 
?>