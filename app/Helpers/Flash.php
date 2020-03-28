<?php

namespace App\Helpers;

class Flash
{
    /**
     * @param string $message
     * @param string $type
     */
    public function message($message, $type)
    {
        $title = 'Info!';

        if ($type === 'success') {
            $title = 'Success!';
        } elseif ($type === 'error') {
            $title = 'Error!';
        }

        session()->flash('flash_message', [
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }
}
