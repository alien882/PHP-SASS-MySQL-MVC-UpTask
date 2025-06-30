<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public function __construct(public $email, public $nombre, public $token)
    {
    }

    public function enviarConfirmacion()
    {
        $mail = new PHPMailer();

        // configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV["EMAIL_USUARIO"];
        $mail->Password = $_ENV["EMAIL_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV["EMAIL_PORT"];

        $mail->setFrom("correo@correo.com", "uptask.com"); // remitente
        $mail->addAddress($this->email, $this->nombre); // destinatario
        $mail->Subject = "Confirma tu cuenta";
        $mail->isHTML();
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Body = "
            <p>
                <strong>Hola $this->nombre</strong>
                Has creado tu cuenta en UpTask,
                solo debes confirmarla presionando el 
                siguiente enlace
            </p>
            <p>Presiona aquí: 
                <a href='" . $_ENV['APP_URL'] . "/confirmacion-cuenta?token=$this->token'>
                    Confirmar Cuenta
                </a>
            </p>
            <p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje</p>
        ";

        $mail->send();
    }

    public function enviarInstrucciones()
    {
        $mail = new PHPMailer();

        // configurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV["EMAIL_USUARIO"];
        $mail->Password = $_ENV["EMAIL_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV["EMAIL_PORT"];

        $mail->setFrom("correo@correo.com", "uptask.com"); // remitente
        $mail->addAddress($this->email, $this->nombre); // destinatario
        $mail->Subject = "Reestablece tu password";
        $mail->isHTML();
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Body = "
            <p>
                <strong>Hola $this->nombre</strong>
                Has solicitado reestablecer tu password, sigue el siguiente
                enlace para hacerlo
            </p>
            <p>Presiona aquí: 
                <a href='" . $_ENV['APP_URL'] . "/reestablecer-password?token=$this->token'>
                    Reestablecer Password
                </a>
            </p>
            <p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje</p>
        ";

        $mail->send();
    }
}
