<?php
declare(strict_types=1);

class Feedback
{
    private $message;
    private $color;

    public function __construct($message, $color){
        $this->message = $message;
        $this->color = $color;
    }

    public function getFeedback() : string
    {
        return 
        '
        <script>
            document.getElementById("feedback").innerText = "' . $this->message . '"; 
            document.getElementById("feedback").style.color = "' . $this->color . '";
        </script>';
    }

}