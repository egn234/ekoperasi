<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Maintenance Mode Filter
 * 
 * This filter checks if the application is in maintenance mode based on the 
 * 'MAINTENANCE' environment variable in the .env file.
 */
class MaintenanceFilter implements FilterInterface
{
    /**
     * Check if maintenance mode is enabled before the request is processed.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Use env() helper for robust environment variable checking
        $isMaintenance = (bool) env('MAINTENANCE', false);

        // Use getUri()->getPath() and trim for reliable comparison
        $currentPath = trim($request->getUri()->getPath(), '/');

        // Redirect to maintenance page if mode is active and we're not already there
        if ($isMaintenance && $currentPath !== 'maintenance') {
            return redirect()->to('/maintenance');
        }

        // Redirect back to home if maintenance mode is disabled but user is on the maintenance page
        if (!$isMaintenance && $currentPath === 'maintenance') {
            return redirect()->to('/');
        }

        return null;
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
