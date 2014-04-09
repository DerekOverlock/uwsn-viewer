<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/PHPMailer/PHPMailerAutoload.php";

class Mail {
    private $user;
    private $password;
    /** @var PHPMailer */
    private $mail;

    public function __construct() {
        $json = json_decode(file_get_contents(GMAIL_CREDENTIALS));
        $this->user = $json->username;
        $this->password = $json->password;
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->user;
        $this->mail->Password = $this->password;
        $this->mail->setFrom($this->user, "UWSN Web Simulator");
    }

    public function send($to, $subject, $message, $arrFiles = array()) {
        $this->mail->clearAttachments();
        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;
        $this->mail->msgHTML($message);
        foreach($arrFiles as $arrFile) {
            $this->mail->addAttachment($arrFile);
        }
        if(!$this->mail->send()) {
            echo "Mail error";
        } else {
         //   echo "Mail sent!";
        }
    }

}