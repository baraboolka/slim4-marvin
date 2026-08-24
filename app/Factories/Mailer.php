<?php

declare(strict_types = 1);

namespace Factories;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    private $config;

    //constructor receives container instance
    public function __construct(array $config)
    {

        //base mailing settings
        $this->config = $config;

    }

    public function __invoke(array $settings): PHPMailer {
        //$settings - current mailing settings
        $mail = new PHPMailer(true);

        if( $this->config["server"]["isSMTP"] ){

            $mail->isSMTP();
            $mail->Host       = $this->config['smtp']['host'];
            $mail->SMTPAuth   = $this->config['smtp']['auth'];
            $mail->Username   = $this->config['smtp']['username'];
            $mail->Password   = $this->config['smtp']['password'];
            $mail->SMTPSecure = $this->config['smtp']['secure'];
            $mail->Port       = $this->config['smtp']['port'];

        }

        $mail->setFrom($this->config["from"]);
        $mail->addAddress($settings["to"]);
        $mail->isHTML(true);

        $mail->Subject = $settings["subject"];
        $mail->Body = $settings["text"];
        
        return $mail;

    }

}