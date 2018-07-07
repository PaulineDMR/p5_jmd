<?php 

namespace jmd\controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class AboutController {

	private $msg;
    private $host;
    private $from;
    private $to;
    private $name;
    private $subject;
    private $message;
    private $email;
    private $err;

    private $secretKey;

    /**
     * [Get config for php mailer]
     * @param [string] $mailHost [mail host]
     * @param [string] $mailFrom [sender mail address]
     * @param [string] $mailTo   [receiver mail address]
     * @param [int] $secret   [secret key for api ReCaptcha]
     */
    public function __construct($mailHost, $mailFrom, $mailTo, $secret)
    {
        if (array_key_exists('email', $_POST)) {
        	$this->host = $mailHost;
	    	$this->from = $mailFrom;
	    	$this->to = $mailTo;
	    	$this->secretKey = $secret;
        	$this->validation();
        }
    }

	/**
     * [Display the About page]
     * 
     */
	public function render()
    {
		if (isset($_SESSION["mail-msg"])) {
			$this->msg = $_SESSION["mail-msg"];
			unset($_SESSION["mail-msg"]);
		}
		
		$twig = Twig::initTwig("src/views/");

		echo $twig->render('aboutTemplate.twig', ["msg" => $this->msg]);
	}

	/**
     * [Check the reCaptacha user action]
     * @param  [string] $code [Api analysis of the user action]
     * @return [string]       [Response success or not]
     */
    public function checkCode($code)
    {
		if (empty($code)) {
			return false;
		}
		$url = "https://www.google.com/recaptcha/api/siteverify?secret={$this->secretKey}&response={$code}";
		if(function_exists("curl_version")) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_TIMEOUT, 2);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($curl);
		} else {
			$reponse = file_get_contents($url);
		}

		if (empty($response) || is_null($response)) {
			return false;
		}

		$json = json_decode($response);
		return $json->success;
	}

    /**
     * [php validation of the user inputs in the contact form]
     * 
     */
	public function validation()
    { 
        if (array_key_exists('subject', $_POST)) {
            $this->subject = substr(strip_tags($_POST['subject']), 0, 255);
        } else {
            $this->subject = ' ';
        }
        
        if (array_key_exists('message', $_POST) && !empty($_POST['message'])) {
            $this->message = substr(strip_tags($_POST['message']), 0, 16384);
        } else {
            $this->message = '';
            $this->msg = 'Pas de message !';
            $this->err = true;
        }
        
        if (array_key_exists('name', $_POST)) {
            $this->name = substr(strip_tags($_POST['name']), 0, 255);
        } else {
            $this->name = '';
        }
        
        if (array_key_exists('email', $_POST) and \PHPMailer\PHPMailer\PHPMailer::validateAddress($_POST['email'])) {
            $this->email = $_POST['email'];
        } else {
            $this->msg .= "Erreur: adresse mail invalide";
            $this->err = true;
        }
    }
	
	/**
     * [gonfig phpMailer, check the reCaptcha reponse, send the email]
     * @param  [string] $code [reCaptacha response code]
     * @return [string]       [success or message to display to user]
     */
    public function contactMe($code)
    {
        if (!$this->err) {
            $mail = new PHPMailer;
            $mail->SMTPAuth = true;
            //$mail->Username = 'user@example.com';
            //$mail->Password = 'secret';
            $mail->SMTPSecure = 'ssl';  
            $mail->Host = $this->host;
            $mail->Port = 587;
            $mail->CharSet = 'utf-8';
            $mail->setFrom($this->from, 'Jeanne-Marie Desmares');
            $mail->addAddress($this->to);
            $mail->addReplyTo($this->email, $this->name);
            $mail->Subject = 'Formulaire de contact: ' . $this->subject;
            $mail->Body = <<<EOT
De: {$this->name}
Email: {$this->email}
Message: {$this->message}
EOT;
            if ($this->checkCode($code) == false) {
        		$this->msg .= "Vous devez confirmer que vous n'êtes pas un robot.";
        	} elseif (!$mail->send()) {
                $this->msg = "Désolé, une erreur s'est produite. Veuillez réessayer ultérieurement."; 
            } else {
                $this->msg = 'Votre message a bien été envoyé! Merci.';
            }
        }

        $_SESSION["mail-msg"] = $this->msg;

        header("location:index.php?action=about#contact");   
	}
}

