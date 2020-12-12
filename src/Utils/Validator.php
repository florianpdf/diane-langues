<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use function Symfony\Component\String\u;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Validator
{
    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $username)) {
            throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (u($plainPassword)->trim()->length() < 6) {
            throw new InvalidArgumentException('The password must be at least 6 characters long.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if ($this->validate($email) == false) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    public function validateName(?string $name): string
    {
        if (empty($name)) {
            throw new InvalidArgumentException('The name can not be empty.');
        }

        return $name;
    }

    public function validateFirstName(?string $firstName): string
    {
        if (empty($firstName)) {
            throw new InvalidArgumentException('The first name can not be empty.');
        }

        return $firstName;
    }

    public function validateWebsiteName(?string $websiteName): string
    {
        if (empty($websiteName)) {
            throw new InvalidArgumentException('The Website name can not be empty.');
        }

        return $websiteName;
    }

    private function validate($email) { 
        return (boolean) filter_var($email, FILTER_VALIDATE_EMAIL); 
    } 
}