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
					session()->destroy();
					return redirect()->to('/');
	    	}

	    	if ($idgroup == 1) {
	    		return redirect()->to('admin/dashboard');
	    	}

	    	if ($idgroup == 2) {
	    		return redirect()->to('bendahara/dashboard');
	    	}

	    	if ($idgroup == 3) {
	    		return redirect()->to('ketua/dashboard');
	    	}

	    	if ($idgroup == 3) {
	    		return redirect()->to('anggota/dashboard');
	    	}
	    }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}