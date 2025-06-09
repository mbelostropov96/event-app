@php
// Force production mode to always use the built assets
$manifestPath = public_path('build/.vite/manifest.json');
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
    
    // Get the JS entry
    $jsEntry = $manifest['resources/js/app.js'] ?? null;
    $jsFile = $jsEntry['file'] ?? null;
    
    // Get CSS from the JS entry
    $cssFiles = $jsEntry['css'] ?? [];
}

// Get the current request URL and protocol
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? request()->getHost();
$baseUrl = $protocol . $host . '/build';
@endphp

@if(!empty($cssFiles))
    @foreach($cssFiles as $cssFile)
        <link rel="stylesheet" href="{{ $baseUrl . '/' . $cssFile }}">
    @endforeach
@endif

@if(isset($jsFile))
    <script type="module" src="{{ $baseUrl . '/' . $jsFile }}"></script>
@endif
