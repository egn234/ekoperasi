<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
	    if(session()->get('logged_in')){
	    	$flag = session()->get('flag');
	    	$idgroup = session()->get('idgroup');

	    	if ($flag == 0) {
					session_destroy();
					return redirect()->to('/');
	    	}

	    	if ($idgroup == 1) {
	    		return redirect()->to('admin/dashboard');
	    	}

	    	if ($idgroup == 2) {
	    		return redirect()->to('dashboard_bendahara');
	    	}

	    	if ($idgroup == 3) {
	    		return redirect()->to('dashboard_ketua');
	    	}

	    	if ($idgroup == 3) {
	    		return redirect()->to('dashboard_anggota');
	    	}
	    }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}