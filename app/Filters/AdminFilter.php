<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
		if(!session()->get('logged_in')){
				$alert = view('partials/notification-alert', 
						['notif_text' => 'Session Habis',
						 'status' => 'warning']
						);

					session()->setFlashdata('notif_login', $alert);
				return redirect()->to('/');
	    }else{
	    	$flag = session()->get('flag');
	    	$idgroup = session()->get('idgroup');

	    	if ($flag == 0) {
				$alert = view('partials/notification-alert', 
					['notif_text' => 'Akun ini sudah tidak aktif',
					 'status' => 'danger']
					);

				session()->setFlashdata('notif_login', $alert);
				return redirect()->to('/');
	    	}

	    	if ($idgroup == 2) {
	    		return redirect()->to('dashboard_bendahara');
	    	}

	    	if ($idgroup == 3) {
	    		return redirect()->to('dashboard_ketua');
	    	}

	    	if ($idgroup == 4) {
	    		return redirect()->to('dashboard_anggota');
	    	}
	    }        
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}