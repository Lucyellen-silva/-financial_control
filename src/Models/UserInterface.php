<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 02/08/18
 * Time: 19:32
 */

namespace Financeiro\Models;

interface UserInterface
{
    public function getId(): int;
    public function getFullname(): string;
    public function getEmail(): string;
    public function getPasswork(): string;
}