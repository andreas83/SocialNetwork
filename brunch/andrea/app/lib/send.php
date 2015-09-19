<?php

class send
{

    /**
     * @param $name
     * @param $mail
     */
    static function welcome($name, $mail)
    {
        $subject = "Welcome to art.world";
        $html = file_get_contents(Config::get("basedir") . "app/template/mail/welcome.php");

        $html = str_replace(":name:", $name, $html);

        $headers = "From: hello@art.world\r\n";
        $headers .= "Reply-To: mailing@art.world\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";

        mail($mail, $subject, $html, $headers);
    }

    /**
     * @param $name
     * @param $activationkey
     * @param $mail
     */
    static function activationmail($name, $activationkey, $mail)
    {
        $subject = "Welcome to art.world";
        $html = file_get_contents(Config::get("basedir") . "app/template/mail/signup.php");
        $html = str_replace(":name:", $name, $html);
        $html = str_replace(":activationkey:", $activationkey, $html);

        $headers = "From: hello@art.world\r\n";
        $headers .= "Reply-To: mailing@art.world\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";

        mail($mail, $subject, $html, $headers);
    }


    static function digest()
    {
        $profil = new profil;
        $res = $profil->get_list();


        foreach ($res as $profil) {
            $subject = "Your personal digest from art.world";
            $html = file_get_contents(Config::get("basedir") . "app/template/mail/digest.php");
            $digest_html = file_get_contents(Config::get("basedir") . "app/template/mail/digestentry.php");

            $digest = new digest;
            $res = $digest->getDigest($profil->pid);
            $final = "";
            foreach ($res as $event) {
                $prepare = $digest_html;


                $location = explode(",", trim($event->location));
                $country = end($location);
                $city = prev($location);

                //upcoming event
                if (date("U") < $event->open && date("U") < $event->close) {
                    $msg = "Starts " . date("F d, Y", $event->open);
                    $css = "soon";
                }

                //current event
                if (date("U") > $event->open && date("U") < $event->close) {
                    $css = "upcoming";
                    $msg = "Ends " . date("F d, Y", $event->close);
                }

                //past event
                if (date("U") > $event->close) {
                    $css = "past";
                    $msg = date("F d", $event->open) . " - " . date("F d, Y", $event->close);
                }

                $prepare = str_replace(":eventtype:", $event->typ, $prepare);
                $prepare = str_replace(":eventname:", $event->eventname, $prepare);
                $prepare = str_replace(":venuename:", $event->venuename, $prepare);
                $prepare = str_replace(":city:", $city, $prepare);
                $prepare = str_replace(":country:", $country, $prepare);
                $prepare = str_replace(":timemsg:", $msg, $prepare);
                $final .= $prepare;
            }


            $html = str_replace(":name:", $profil->name, $html);
            $html = str_replace(":digest:", $final, $html);

            $headers = "From: digest@art.world\r\n";
            $headers .= "Reply-To: mailing@art.world\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            echo $html;
            mail($profil->mail, $subject, $html, $headers);
        }


    }
}
