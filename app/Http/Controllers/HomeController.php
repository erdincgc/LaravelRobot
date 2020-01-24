<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(){
        $data['info_text'] = 'Controllerdan gelen metin';
        return \view('page2', $data);
    }
}
