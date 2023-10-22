<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Postmark\PostmarkClient;

class EmailController extends Controller
{
    public function sendEmail($toEmail, $subject, $htmlBody, $textBody)
    {
        // Créez une instance de PostmarkClient
        $client = new PostmarkClient(env('POSTMARK_TOKEN'));

        $fromEmail = "cedric.atzori@le-campus-numerique.fr";
        $tag = "example-email-tag";
        $trackOpens = true;
        $trackLinks = "None";
        $messageStream = "outbound";

        // Envoyez un e-mail :
        $sendResult = $client->sendEmail(
            $fromEmail,
            $toEmail,
            $subject,
            $htmlBody,
            $textBody,
            $tag,
            $trackOpens
        // Les autres paramètres peuvent être laissés à NULL si vous ne souhaitez pas les utiliser.
        );
    }

}
