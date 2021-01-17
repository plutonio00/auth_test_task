<?php


namespace app\helper;


class SecurityHelper
{
    public static function generateRandomString(): string {
        return bin2hex(random_bytes(64));
    }
}