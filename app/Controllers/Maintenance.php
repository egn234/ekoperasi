<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Maintenance extends Controller
{
    public function index()
    {
        $data['title'] = 'Maintenance';
        return view('maintenance', $data);
    }
}