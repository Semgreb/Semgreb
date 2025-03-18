<?php

namespace modules\complaints\services\messages;



defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class FirstComplaintCreated extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $ticket_id = $params[0];

        return $ticket_id == 1;
    }

    public function getMessage(...$params)
    {
        return 'First Complaint Created! <br /> <span style="font-size:26px;">Did you know that you can embed Complaint Form (Setup->Settings->Support->Complaint Form) directly in your websites?</span>';
    }
}
