<?php

namespace AlexWinter\Framework;

final class App 
{
    public static function make(): self
    {
        return new App();
    }

    public function run(): void
    {
        echo "running";
    }
}