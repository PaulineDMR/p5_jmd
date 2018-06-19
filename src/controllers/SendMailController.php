<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

namespace jmd\controllers;

/**
 * 
 */
class SendMailController extends Controller {
	
	public function ContactMe() {
		
		//récupère les infos du formulaire de contact
		//paramètre l'objet mail de php mailer
		$msg = '';
		//Don't run this unless we're handling a form submission
		if (array_key_exists('email', $_POST)) {
    
    		//Create a new PHPMailer instance
    		$mail = new \PHPMailer\PHPMailer\PHPMailer;

    		//Tell PHPMailer to use SMTP - requires a local mail server
    		//Faster and safer than using mail()
    		$mail->isSMTP();
    		$mail->Host = 'smtp.gmail.com'; // à changer lorsque sur serveur Amen
    		$mail->Port = 25;

    		//Use a fixed address in your own domain as the from address
    		//**DO NOT** use the submitter's address here as it will be forgery
    		//and will cause your messages to fail SPF checks
    		$mail->setFrom('pauline.desmares@gmail.com', 'Pauline');

    		//Send the message to yourself, or whoever should receive contact for submissions
    		$mail->addAddress('pauline.desmares@aliceadsl.fr');

    		//Put the submitter's address in a reply-to header
    		//This will fail if the address provided is invalid,
    		//in which case we should ignore the whole request
    		if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
       		 	$mail->Subject = $_POST['subject'];

        		//use HTML
        		$mail->isHTML(true);

        		// Set email format to HTML
    			$mail->Body =  $_POST['message'];
        		
        		//Build a simple message body
        		$mail->AltBody = $_POST['message'];
        		//Send the message, check for errors
        		if (!$mail->send()) {
            		//The reason for failing to send will be in $mail->ErrorInfo
            		//but you shouldn't display errors to users - process the error, log it on your server.
            		$msg = 'Désolé, une erreur s\'est produite. Veuillez réessayer ultérieurement.';
        		} else {
            		$msg = 'Votre message a été envoyé! Merci.';
        		}
    		} else {
        		$msg = 'Adresse mail invalide, message ignoré.';
    		}
		}
		header("Location: http://jmd.pdmrweb.com/index.php?action=about#contact");
	}
/*?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact form</title>
</head>
<body>
<h1>Contact us</h1>
<?php if (!empty($msg)) {
    echo "<h2>$msg</h2>";
} ?>
<form method="POST">
    <label for="name">Name: <input type="text" name="name" id="name"></label><br>
    <label for="email">Email address: <input type="email" name="email" id="email"></label><br>
    <label for="message">Message: <textarea name="message" id="message" rows="8" cols="20"></textarea></label><br>
    <input type="submit" value="Send">
</form>
</body>
</html>
		//renvoie vers la page contact pour informer que le mail a été correctement envoyé
		
	}

	public function mailNewComment()
	{
		# code...
	}
}*/

}