<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 02/08/18
 * Time: 18:33
 */

namespace Financeiro\View;

use Financeiro\Auth\AuthInterface;

class TwigGlobals extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function getGlobals()
    {
        return [
            'Auth' => $this->auth
        ];
    }
}