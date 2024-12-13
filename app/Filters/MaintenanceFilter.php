<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {  
        if (getenv('MAINTENANCE') === 'true' && $request->uri->getPath() !== 'maintenance') {
            return redirect()->to('/maintenance');
        }
        
        if (getenv('MAINTENANCE') === 'false' && $request->uri->getPath() === 'maintenance') {
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
