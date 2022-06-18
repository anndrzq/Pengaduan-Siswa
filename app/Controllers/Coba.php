<?php

namespace App\Controllers;

class Coba extends BaseController
{
    public function notifikasi()
    {
        $data = [
            'user' => $this->user,
            'title' => 'Implementasi Pusher'
        ];
        return view('coba/notifikasi', $data);
    }
}
