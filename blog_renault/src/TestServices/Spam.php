<?php

namespace App\TestServices;

class Spam
{
    private $forbidenString;
    private $logger;

    public function __construct($forbidenString)
    {
        $this->forbidenString = $forbidenString;
        $this->logger = new Logger();
    }

    public function isSpam($message, $request, $translator)
    {
        $i = 0;
        while($i < count($this->forbidenString)) {
            if($message == $this->forbidenString[$i]) {
                $ip = $request->server->get('REMOTE_ADDR');
                $this->logger->info($translator->trans("Ceci est un spam") . " : " . $ip);
                return true;
            }
            $i++;
        }
        return false;
    }
}
