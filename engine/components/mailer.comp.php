<?php
namespace engine\components;
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require_once VENDOR.'autoload.php';

class Mailer{
    protected $settings;
    protected $config_name ="Mail";
    public $add_address = [];
    public $recipient_email;
    public $recipient_name;
    public $add_cc = [];
    public $reply_to;
	public $from_title ='';
	
	public function __constructor($config_name=''){
        $this->config_name = $config_name!=null?$config_name:"Mail";
    }
    public function sendMail($from,$subject,$body,$altbody=''){
            $this->settings = new Settings;
            $setting = $this->settings->config()[$this->config_name];
            $mail = new PHPMailer(true);              // Passing `true` enables exceptions
        try {			  
			//Server settings
            //$mail->SMTPDebug = 2;                  // Enable verbose debug output
            $mail->isSMTP();                         // Set mailer to use SMTP
            $mail->Host     = $setting["Host"];      //'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = $setting["SMTPAuth"];  // Enable SMTP authentication
            $mail->Username = $setting["Username"];  //'user@example.com'; // SMTP username
            $mail->Password = $setting["Password"];  // SMTP password
            $mail->SMTPSecure = $setting["SMTPSecure"]; // Enable TLS encryption, `ssl` also accepted
            $mail->Port     = $setting["Port"];
	    //Recipients
            $mail->setFrom($from, $this->from_title);
            $mail->addAddress($this->recipient_email, $this->recipient_name);   // Add a recipient
            $mail->addReplyTo($this->reply_to);
            if(!empty($this->add_address) && is_array($this->add_address)){
              foreach($this->add_address as $address){
                 $mail->addAddress($address); // Name is optional
              }
            }

             if(!empty($this->add_cc) && is_array($this->add_cc)){
              foreach($this->add_cc as $cc){
                  $mail->addCC($cc);}
            }

            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            $mail->isHTML(true);                                    // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altbody;
            $mail->send(); 
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
	
}