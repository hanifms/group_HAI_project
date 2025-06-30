<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content Security Policy Configuration
    |--------------------------------------------------------------------------
    |
    | Content Security Policy (CSP) helps prevent XSS attacks, clickjacking,
    | and other code injection attacks by controlling which resources the
    | browser is allowed to load for a given page.
    |
    | SECURITY LEVELS:
    | - DEVELOPMENT: Includes 'unsafe-inline' for framework compatibility
    | - STAGING: Test with nonces and stricter policies
    | - PRODUCTION: Remove 'unsafe-*' directives, use nonces for inline scripts
    |
    | PRODUCTION RECOMMENDATIONS:
    | 1. Remove 'unsafe-inline' and 'unsafe-eval' from script-src
    | 2. Use nonces for necessary inline scripts: @cspNonce
    | 3. Replace wildcards with specific domains
    | 4. Enable report-only mode first to test policies
    | 5. Monitor CSP violation reports regularly
    |
    */

    'csp' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Content Security Policy
        |--------------------------------------------------------------------------
        |
        | Set this to true to enable CSP headers. You can disable this during
        | development if it interferes with debugging tools.
        |
        */
        'enabled' => env('CSP_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Report Only Mode
        |--------------------------------------------------------------------------
        |
        | When enabled, CSP violations will be reported but not blocked.
        | This is useful for testing CSP policies before enforcing them.
        | Set to true for development to avoid blocking functionality.
        |
        */
        'report_only' => env('CSP_REPORT_ONLY', false),

        /*
        |--------------------------------------------------------------------------
        | CSP Directives
        |--------------------------------------------------------------------------
        |
        | Define the Content Security Policy directives for your application.
        | Each directive controls what resources can be loaded from where.
        |
        */
        'directives' => [
            /*
            |--------------------------------------------------------------------------
            | Default Source
            |--------------------------------------------------------------------------
            |
            | The default-src directive serves as a fallback for other CSP directives.
            | If a specific directive is not defined, the browser will use default-src.
            |
            */
            'default-src' => [
                "'self'",
                'data:', // Allow data URLs
                'blob:', // Allow blob URLs
            ],

            /*
            |--------------------------------------------------------------------------
            | Script Sources
            |--------------------------------------------------------------------------
            |
            | Controls which scripts can be executed. This includes JavaScript files,
            | inline scripts, and eval() calls.
            |
            | SECURITY NOTE: 'unsafe-inline' and 'unsafe-eval' are kept for development
            | compatibility with Livewire and Alpine.js. For production, consider
            | implementing nonces and removing these unsafe directives.
            |
            */
            'script-src' => [
                "'self'",
                // Development compatibility - remove in production
                "'unsafe-inline'", // Needed for Livewire and Alpine.js
                // Note: 'unsafe-eval' removed for better security
                // Popular CDNs for libraries
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                // Development servers
                'http://localhost:5173', // Vite development server
                'http://127.0.0.1:5173', // Vite development server IPv4
                'ws://localhost:5173', // Vite WebSocket for hot reload
                'ws://127.0.0.1:5173', // Vite WebSocket IPv4
                'http://localhost:8000', // Laravel development server
                'http://127.0.0.1:8000', // Laravel development server IPv4
                // Framework-specific sources
                'https://livewire.laravel.com',
                'https://cdn.alpinejs.dev',
                // Allow blob: for dynamic script generation
                'blob:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Style Sources
            |--------------------------------------------------------------------------
            |
            | Controls which stylesheets can be applied. This includes CSS files,
            | inline styles, and style attributes.
            |
            */
            'style-src' => [
                "'self'",
                "'unsafe-inline'", // Allow inline styles (commonly needed for CSS frameworks and Livewire)
                'https://fonts.googleapis.com', // Allow Google Fonts
                'https://fonts.bunny.net', // Allow Bunny Fonts (used in this project)
                'https://cdn.jsdelivr.net', // Allow popular CDN for CSS libraries
                'https://unpkg.com', // Allow unpkg CDN
                'https://cdnjs.cloudflare.com', // Allow Cloudflare CDN
                'http://localhost:5173', // Vite development server
                'http://127.0.0.1:5173', // Vite development server IPv4
                'http://localhost:8000', // Laravel development server
                'http://127.0.0.1:8000', // Laravel development server IPv4
                // Allow blob: for dynamic styles
                'blob:',
                // Allow data: for inline CSS
                'data:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Image Sources
            |--------------------------------------------------------------------------
            |
            | Controls which images can be loaded. This includes img elements,
            | background images, and other image resources.
            |
            | SECURITY NOTE: Removed wildcard 'https:' and restricted to specific domains
            |
            */
            'img-src' => [
                "'self'",
                'data:', // Allow data URLs for images (base64 encoded images)
                'blob:', // Allow blob URLs (for file uploads/previews)
                // Specific trusted image sources
                'https://images.unsplash.com', // Popular image service
                'https://via.placeholder.com', // Placeholder service
                'https://picsum.photos', // Lorem Picsum
                'https://gravatar.com', // Gravatar for user avatars
                'https://*.gravatar.com', // Gravatar CDN
                // Add your specific image CDNs here instead of wildcard
                // 'https://your-cdn.example.com',
            ],

            /*
            |--------------------------------------------------------------------------
            | Font Sources
            |--------------------------------------------------------------------------
            |
            | Controls which fonts can be loaded. This includes web fonts from
            | external services like Google Fonts.
            |
            */
            'font-src' => [
                "'self'",
                'https://fonts.gstatic.com', // Allow Google Fonts
                'https://fonts.googleapis.com', // Allow Google Fonts API
                'https://fonts.bunny.net', // Allow Bunny Fonts (used in this project)
                'data:', // Allow data URLs for fonts
            ],

            /*
            |--------------------------------------------------------------------------
            | Connect Sources
            |--------------------------------------------------------------------------
            |
            | Controls which servers can be contacted via XMLHttpRequest, fetch(),
            | WebSocket, and EventSource connections.
            |
            */
            'connect-src' => [
                "'self'",
                'ws:', // Allow WebSocket connections (for development servers)
                'wss:', // Allow secure WebSocket connections
                'http://localhost:*', // Allow localhost connections for development
                'http://127.0.0.1:*', // Allow local IP connections for development
                'ws://localhost:*', // Allow WebSocket connections to localhost
                'ws://127.0.0.1:*', // Allow WebSocket connections to local IP
                'http://localhost:5173', // Vite development server
                'http://127.0.0.1:5173', // Vite development server IPv4
                'ws://localhost:5173', // Vite WebSocket for hot reload
                'ws://127.0.0.1:5173', // Vite WebSocket IPv4
                'http://localhost:8000', // Laravel development server
                'http://127.0.0.1:8000', // Laravel development server IPv4
                'https://api.example.com', // Add your API endpoints here
                // Livewire AJAX requests
                'https://livewire.laravel.com',
                // Allow data: URLs for AJAX
                'data:',
                // Allow blob: URLs
                'blob:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Media Sources
            |--------------------------------------------------------------------------
            |
            | Controls which audio and video sources can be loaded.
            |
            | SECURITY NOTE: Removed wildcard 'https:' and restricted to specific domains
            |
            */
            'media-src' => [
                "'self'",
                'data:', // Allow data URLs for media
                // Specific trusted media sources
                'https://www.youtube.com',
                'https://player.vimeo.com',
                'https://w.soundcloud.com',
                // Add your specific media CDNs here instead of wildcard
                // 'https://your-media-cdn.example.com',
            ],

            /*
            |--------------------------------------------------------------------------
            | Object Sources
            |--------------------------------------------------------------------------
            |
            | Controls which plugins can be loaded (like Flash, Java applets).
            | It's recommended to set this to 'none' for security.
            |
            */
            'object-src' => [
                "'none'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Frame Sources
            |--------------------------------------------------------------------------
            |
            | Controls which sources can be embedded as frames, iframes, or objects.
            |
            */
            'frame-src' => [
                "'self'",
                'https://www.youtube.com', // Allow YouTube embeds
                'https://www.google.com', // Allow Google Maps embeds
                'https://maps.google.com', // Allow Google Maps embeds
            ],

            /*
            |--------------------------------------------------------------------------
            | Child Sources
            |--------------------------------------------------------------------------
            |
            | Controls which sources can be loaded in frames and workers.
            | This is deprecated in favor of frame-src and worker-src.
            |
            */
            'child-src' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Form Action
            |--------------------------------------------------------------------------
            |
            | Controls which URLs can be used as form action targets.
            |
            */
            'form-action' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Frame Ancestors
            |--------------------------------------------------------------------------
            |
            | Controls which sources can embed this page in a frame.
            | This helps prevent clickjacking attacks.
            |
            */
            'frame-ancestors' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Base URI
            |--------------------------------------------------------------------------
            |
            | Controls which URLs can be used in the <base> element.
            |
            */
            'base-uri' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Manifest Source
            |--------------------------------------------------------------------------
            |
            | Controls which manifest files can be loaded.
            |
            */
            'manifest-src' => [
                "'self'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Worker Sources
            |--------------------------------------------------------------------------
            |
            | Controls which sources can be loaded as web workers, shared workers,
            | or service workers.
            |
            */
            'worker-src' => [
                "'self'",
                'blob:', // Allow blob URLs for workers
            ],

            /*
            |--------------------------------------------------------------------------
            | Script Source Elements
            |--------------------------------------------------------------------------
            |
            | Controls script elements specifically (complementary to script-src)
            |
            */
            'script-src-elem' => [
                "'self'",
                "'unsafe-inline'",
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://cdnjs.cloudflare.com',
                'http://localhost:5173',
                'http://127.0.0.1:5173',
                'http://localhost:8000',
                'http://127.0.0.1:8000',
                'blob:',
            ],

            /*
            |--------------------------------------------------------------------------
            | Style Source Elements
            |--------------------------------------------------------------------------
            |
            | Controls style elements specifically (complementary to style-src)
            |
            */
            'style-src-elem' => [
                "'self'",
                "'unsafe-inline'",
                'https://fonts.googleapis.com',
                'https://fonts.bunny.net',
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'http://localhost:5173',
                'http://127.0.0.1:5173',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | CSP Reporting
        |--------------------------------------------------------------------------
        |
        | Configure CSP violation reporting. When violations occur, the browser
        | can send reports to a specified endpoint for monitoring.
        |
        | For development, set CSP_REPORT_URI to /csp-report to enable logging
        | of CSP violations. In production, consider using a dedicated service.
        |
        */
        'report_uri' => env('CSP_REPORT_URI', '/csp-report'),
        'report_to' => env('CSP_REPORT_TO', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Additional Security Headers
    |--------------------------------------------------------------------------
    |
    | Configure additional security headers to enhance application security.
    |
    */

    'headers' => [
        /*
        |--------------------------------------------------------------------------
        | X-Frame-Options
        |--------------------------------------------------------------------------
        |
        | Controls whether the page can be displayed in a frame to prevent
        | clickjacking attacks. Options: DENY, SAMEORIGIN, ALLOW-FROM uri
        |
        */
        'x_frame_options' => env('X_FRAME_OPTIONS', 'SAMEORIGIN'),

        /*
        |--------------------------------------------------------------------------
        | X-Content-Type-Options
        |--------------------------------------------------------------------------
        |
        | Prevents browsers from MIME-sniffing a response away from the declared
        | content-type. Set to 'nosniff' to enable this protection.
        |
        */
        'x_content_type_options' => env('X_CONTENT_TYPE_OPTIONS', 'nosniff'),

        /*
        |--------------------------------------------------------------------------
        | X-XSS-Protection
        |--------------------------------------------------------------------------
        |
        | Enables the XSS filter in older browsers. Modern browsers use CSP instead.
        | Options: 0 (disable), 1 (enable), 1; mode=block (enable and block)
        |
        */
        'x_xss_protection' => env('X_XSS_PROTECTION', '1; mode=block'),

        /*
        |--------------------------------------------------------------------------
        | Referrer Policy
        |--------------------------------------------------------------------------
        |
        | Controls how much referrer information is sent with requests.
        | Options: no-referrer, no-referrer-when-downgrade, same-origin, etc.
        |
        */
        'referrer_policy' => env('REFERRER_POLICY', 'strict-origin-when-cross-origin'),

        /*
        |--------------------------------------------------------------------------
        | Permissions Policy
        |--------------------------------------------------------------------------
        |
        | Controls which browser features can be used by the page.
        | This replaces the older Feature-Policy header.
        |
        */
        'permissions_policy' => env('PERMISSIONS_POLICY', 'camera=(), microphone=(), geolocation=()'),

        /*
        |--------------------------------------------------------------------------
        | Strict Transport Security (HSTS)
        |--------------------------------------------------------------------------
        |
        | Forces browsers to use HTTPS connections. Only enable this if your
        | site is served over HTTPS and you want to enforce HTTPS-only access.
        |
        */
        'strict_transport_security' => [
            'enabled' => env('HSTS_ENABLED', false),
            'max_age' => env('HSTS_MAX_AGE', 31536000), // 1 year in seconds
            'include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
            'preload' => env('HSTS_PRELOAD', false),
        ],
    ],
];
