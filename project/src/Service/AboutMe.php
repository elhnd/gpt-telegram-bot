<?php

namespace App\Service;

class AboutMe
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $me;

    /**
     * Undocumented function
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->me = file_get_contents($path);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getMe()
    {
        return $this->me;
    }
}
