<?php

namespace Fastre\PhpFtpAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->forward('FastrePhpFtpAdminBundle:Accounts:list');
    }
}
