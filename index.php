<?php
	session_start();
?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '.\vendor\autoload.php';

	$errore_nome = false;
	$errore_cognome = false;
	$errore_email = false;
	$errore_email_valida = false;
	$errore_messaggio = false;
	
	$nome = "";
	$cognome = "";
	$email = "";
	$messaggio = "";
	
	$output = "";
	
	// Recupero i valori inseriti nel form
	if(isset ($_POST['bottone']))
	{
		// nome
		if (isset($_POST['nome']))
		{
			$nome = trim($_POST['nome']);
			$errore_nome = empty($nome);
		}
		else
		{
			$errore_nome = true;
		}
		// cognome
		if (isset($_POST['cognome']))
		{
			$cognome = trim($_POST['cognome']);
			$errore_cognome = empty($cognome);
		}
		else
		{
			$errore_cognome = true;
		}
		
		// email
		if (isset($_POST['email']))
		{
			$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			if(empty($email))
			{
				$errore_email = true;
			}
			else
			{
				$errore_email_valida = !filter_var($email, FILTER_VALIDATE_EMAIL);
			}
		}
		else
		{
			$errore_email = true;
		}
		
		// messaggio
		if (isset($_POST['messaggio']))
		{
			$messaggio = trim($_POST['messaggio']);
			$errore_messaggio = empty($messaggio);
		}
		else
		{
			$errore_messaggio = true;
		}
		
		// Se non ci sono errori invio l'email
		if(!$errore_nome && !$errore_cognome && !$errore_email && !$errore_email_valida && !$errore_messaggio)
		{
			$mail = new PHPMailer(TRUE);
			
			try {
					$mail->SMTPDebug = 4;
					
					$mail->isSendmail();
					
					$mail->setFrom('vilone.giuseppe@gmail.com', 'Giuseppe Vilone');
				 
					$mail->addAddress('address@address.example', 'address@address.example');
				 
					$mail->Subject = 'Test';
				 
					$mail->Body = "nome: $nome" . "\n" . "cognome: $cognome" . "\n" . "email: $email" . "\n" . "messaggio: $messaggio";	
				 
					$mail->send();
				 }
				 catch (Exception $e)
				 {
					echo $e->errorMessage();
				 }
				 catch (\Exception $e)
				 {
					echo $e->getMessage();
				 }

		}
		
		$dati  = 'nome=' . urlencode($nome) . '&';
		$dati .= 'cognome=' . urlencode($cognome) . '&';
		$dati .= 'email=' . urlencode($email) . '&';
		$dati .= 'messaggio=' . urlencode($messaggio) . '&';
		$dati .= 'idgroup=751&';
		$dati .= 'mailing=7&';
		$dati .= 'delete=0';
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, 'http://mysitecurl.example/Test.php');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dati);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true); 

		$output = curl_exec($ch);
		
		if (curl_error($ch)) {
			$error_msg = curl_error($ch);
		}
		
		curl_close($ch);
		
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Form</title>

<!-- CSS -->

<link href="css/style.css" rel="stylesheet" type="text/css">

<!-- Jquery -->

<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script>
	

	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form").validate();
	});
</script>

</head>

<body>
<div class="w400">
	<?php
	if (!empty($output))
	{
	?>
	<div class="text-center"><em><?php echo "RISULTATO CHIAMATA cURL: $output"; ?></em></div>
	<?php
	}
	?>
	
	<?php
	if (isset($error_msg))
	{
	?>
	<div class="error"><?php echo "SI È VERIFICATO UN ERRORE: $error_msg"; ?></div>
	<?php
	}
	?>
	
	<div class="logo"> <img src="logo.png" alt=""/></div>
	<div class="text-center"><em>(* Cambi obbligatori)</em></div>
	<form id = "form" method = "POST">
		<!-- CASELLE DI TESTO --> 
		Nome *<br>
		<input type="text" id="nome" name="nome" placeholder="Nome" data-rule-required="true" data-msg-required="Per favore inserisci il tuo nome" value="<?php echo $nome ?>">
		<?php
			if ($errore_nome)
			{
			?>
		<label id="nome-error" class="error" for="nome">Per favore inserisci il tuo nome</label>
		<?php
			}
		?>
		<br>
		Cognome *<br>
		<input type="text" id="cognome" name="cognome" placeholder="Cognome"  data-rule-required="true" data-msg-required="Per favore inserisci il tuo cognome" value="<?php echo $cognome?>">
		<?php
			if ($errore_cognome)
			{
		?>
		<label id="cognome-error" class="error" for="cognome">Per favore inserisci il tuo cognome</label>
		<?php
			}
		?>
		<br>
		E-mail *<br>
		<input type="email" id="email" name="email" placeholder="E-mail" data-rule-required="true" data-rule-email="true" data-msg-required="Per favore inserisci un indirizzo e-mail"  data-msg-email="Per favore inserisci un indirizzo e-mail valido" value="<?php echo $email ?>">
		<?php
			if ($errore_email)
			{
			?>
		<label id="email-error" class="error" for="email">Per favore inserisci un indirizzo e-mail</label>
		<?php
			}
		?>
		<?php
			if ($errore_email_valida)
			{
			?>
		<label id="email-error" class="error" for="email">Per favore inserisci un indirizzo e-mail valido</label>
		<?php
			}
		?>
		<br>
		
		<!-- TEXTAREA --> 
		Messaggio *<br>
		<textarea rows="8" name="messaggio" id="messaggio" placeholder="Messaggio" maxlength="2000"  data-rule-required="true" data-msg-required="Per favore inserisci un messaggio"><?php echo $messaggio ?></textarea>
		<?php
			if ($errore_messaggio)
			{
		?>
		<label id="messaggio-error" class="error" for="messaggio">Per favore inserisci un messaggio</label>
		<?php
			}
		?>
		<br>
		
		<!-- SUBMIT -->
		<input id = "bottone" name = "bottone" type="submit" value="Invia">
	</form>
</div>

</body>
</html>
