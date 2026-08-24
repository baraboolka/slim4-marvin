<?php

$mailing = array();

$mailing["smtp"]["isSMTP"] = false;

$mailing["smtp"]["SMTP-host"]       = "smtp.example.com";
$mailing["smtp"]["SMTP-auth"]       = true;
$mailing["smtp"]["SMTP-user"]       = "user@example.com";
$mailing["smtp"]["SMTP-password"]   = "secret";
$mailing["smtp"]["SMTP-secure"]     = "";//PHPMailer::ENCRYPTION_SMTPS
$mailing["smtp"]["SMTP-port"]       = 465;

$mailing["from"]                    = "from@example.com"; 
/**//**//**/