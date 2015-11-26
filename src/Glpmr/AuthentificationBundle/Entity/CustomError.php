<?php

namespace Glpmr\AuthentificationBundle\Entity;
use Symfony\Component\HttpFoundation\Session\Session;

class CustomError
{
    static function showMessage($message)
    {
        $session = new Session();
        $session->getFlashBag()->add("notification", $message);
    }
}