<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_param_manasuka;

class maintenance extends Controller
{
    public function index()
    {
        $data['title'] = 'Maintenance';
        return view('maintenance', $data);
    }
}