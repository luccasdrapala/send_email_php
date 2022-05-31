<?php

    //importando biblioteca/scripts PHPMailer
    require "./PHPMailer/src/Exception.php";
    //require "./PHPMailer/src/OAuth.php";
    require "./PHPMailer/src/OAuthTokenProvider.php";
    require "./PHPMailer/src/PHPMailer.php";
    require "./PHPMailer/src/POP3.php";
    require "./PHPMailer/src/SMTP.php";
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //abstração superficial de uma mensagem de email
    class Mensagem {

        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        public function mensagemValida() {//valida se o campo foi preenchido ou não
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
            return false;
            }
            return true;
        }
    }

    $mensagem = new Mensagem();//instanciando novo objeto mensagem e recebendo variaveis do metodo POST
    $mensagem->__set('para', $_POST['para']);
    $mensagem->__set('assunto', $_POST['assunto']);
    $mensagem->__set('mensagem', $_POST['mensagem']);

    if(!$mensagem->mensagemValida()){//valindando mensagem (feito de forma simples e facil)
        echo 'Mensagem não é valida';
        die();
    }

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'luccasdrapala2@gmail.com';                     //SMTP username
        $mail->Password   = '685283685283';                               //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('luccasdrapala2@gmail.com', 'Luccas Drapala');
        $mail->addAddress($mensagem->__get('para'), 'Kauêzerass');     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information'); //email default de reply
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = $mensagem->__get('mensagem');; //This is the body in plain text for non-HTML mail clients

        $mail->send();
        echo 'Email enviado com sucesso';
    } catch (Exception $e) {
        echo "Não foi possivel enviar este email! Por favor tente novamente: {$mail->ErrorInfo}";
    }

?>