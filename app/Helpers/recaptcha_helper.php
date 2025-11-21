<?php

if (!function_exists('validate_recaptcha')) {
    /**
     * Validate Google reCAPTCHA v3
     * 
     * @param string $recaptchaToken Token from frontend
     * @return array ['success' => bool, 'message' => string]
     */
    function validate_recaptcha($recaptchaToken)
    {
        // Skip validation on development environment
        if (ENVIRONMENT === 'development') {
            return [
                'success' => true,
                'message' => 'reCAPTCHA validation skipped in development mode'
            ];
        }

        // Check if reCAPTCHA is configured
        $recaptchaSecret = getenv('RECAPTCHA_SECRET_KEY');
        if (empty($recaptchaSecret)) {
            log_message('error', 'RECAPTCHA_SECRET_KEY is not configured');
            return [
                'success' => false,
                'message' => 'reCAPTCHA is not configured properly'
            ];
        }

        // Check if token is provided
        if (empty($recaptchaToken)) {
            return [
                'success' => false,
                'message' => 'reCAPTCHA token is missing'
            ];
        }

        // Validate reCAPTCHA with Google
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret'   => $recaptchaSecret,
            'response' => $recaptchaToken,
            'remoteip' => request()->getIPAddress()
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 10 // Add timeout
            ]
        ];

        try {
            $context = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);
            
            if ($result === false) {
                log_message('error', 'Failed to connect to Google reCAPTCHA API');
                return [
                    'success' => false,
                    'message' => 'Unable to verify reCAPTCHA. Please try again.'
                ];
            }

            $response = json_decode($result);

            if (!$response->success) {
                log_message('warning', 'reCAPTCHA validation failed: ' . json_encode($response));
                return [
                    'success' => false,
                    'message' => 'reCAPTCHA validation failed'
                ];
            }

            return [
                'success' => true,
                'message' => 'reCAPTCHA validated successfully'
            ];

        } catch (\Exception $e) {
            log_message('error', 'reCAPTCHA validation exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during reCAPTCHA validation'
            ];
        }
    }
}
