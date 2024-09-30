<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}</style>
    <style>body{font-family:'Nunito',sans-serif}</style>
</head>
<body class="antialiased">
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        @if (Route::has('login'))
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="{{ url('/home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Home</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                    @endif
                @endauth
            </div>
        @endif
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <svg viewBox="0 0 651 192" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-16 w-auto text-gray-700 sm:h-20">
                    <g clip-path="url(#clip0)" fill="#EF3B2D">
                        <path d="M248.032 44.676h-16.466v100.23h47.394v-14.748h-30.928V44.676zM337.091 87.202c-2.101-3.341-5.083-5.965-8.949-7.875-3.865-1.909-7.756-2.864-11.669-2.864-5.062 0-9.69.931-13.89 2.792-4.201 1.861-7.804 4.417-10.811 7.661-3.007 3.246-5.347 6.993-7.016 11.239-1.672 4.249-2.506 8.713-2.506 13.389 0 4.774.834 9.26 2.506 13.459 1.669 4.202 4.009 7.925 7.016 11.169 3.007 3.246 6.609 5.799 10.811 7.66 4.201 1.861 8.828 2.792 13.89 2.792 3.931 0 7.79-.931 11.586-2.832 3.797-1.907 7.001-4.541 9.136-7.875 2.131-3.344 3.655-6.946 4.292-11.046h-15.138c-.651 2.05-1.619 3.813-2.849 5.305-2.198 2.696-5.363 4.034-9.094 4.034-3.933 0-7.191-.838-9.975-2.504-2.785-1.667-4.97-4.073-6.574-6.993-1.605-2.918-2.371-6.097-2.371-9.546 0-3.516.764-6.649 2.371-9.546 1.604-2.918 3.789-5.33 6.574-7.023 2.785-1.669 6.043-2.5 9.975-2.5 3.73 0 6.896 1.315 9.094 4.034 1.28 1.494 2.198 3.265 2.849 5.305h15.138c-.637-4.1-2.16-7.703-4.292-11.046zM465.217 47.884c-8.505 0-15.47 2.309-21.886 6.972-6.416 4.66-11.126 11.078-14.092 18.96l15.21 4.134c2.087-5.426 5.436-9.744 10.153-12.103 4.719-2.373 10.327-3.561 16.026-3.561 6.225 0 11.783 1.191 16.775 3.635 4.992 2.443 8.903 5.779 11.978 10.024 3.075 4.249 5.006 9.21 5.925 14.4 1.706 9.632-.99 21.131-9.728 27.99-8.733 6.871-22.076 10.523-39.367 10.523-11.327 0-21.733-1.803-29.786-5.388-8.053-3.594-14.538-9.063-18.761-15.619-4.224-6.558-6.628-13.777-6.628-21.533 0-9.081 2.547-17.275 7.619-24.597 5.07-7.319 12.074-13.437 20.245-17.356 8.171-3.918 17.252-5.877 27.164-5.877 9.064 0 17.435 1.912 24.034 5.18 6.598 3.263 11.375 8.006 14.55 13.487 3.177 5.481 5.113 11.582 5.938 18.255l15.134-3.68c-.677-5.831-2.221-11.362-4.607-16.374-2.389-5.018-5.752-9.447-10.265-13.203-4.514-3.759-10.098-6.762-16.23-8.789-6.136-2.021-12.807-3.023-19.429-3.023zm-74.162 51.133c-5.784 0-11.077 1.028-15.424 2.794-4.347 1.766-8.063 4.224-11.147 7.229-3.086 3.003-5.616 6.562-7.084 10.367-1.474 3.809-2.217 7.805-2.217 11.727 0 3.293.657 6.676 1.846 9.905 1.182 3.235 2.887 6.38 5.123 9.373 2.235 2.996 5.413 5.34 9.193 7.144 3.772 1.802 8.644 2.724 14.039 2.724 6.716 0 12.372-1.507 16.852-4.246 4.481-2.738 7.637-6.533 9.412-11.187 1.777-4.651 2.559-9.822 2.559-15.551 0-2.396-.234-4.675-.647-6.87l-49.639 13.779c.156 2.59.384 4.434.646 5.224.262.788.646 1.406 1.143 1.89.498.484 1.054.799 1.635.943.675.146 1.294.074 1.871-.262s1.056-.779 1.459-1.525c.396-.748.655-1.525.846-2.384.193-.869.289-1.733.289-2.617 0-6.957-1.123-12.645-3.428-16.827-2.296-4.183-5.412-7.31-9.256-9.29-3.845-1.981-8.556-2.999-14.378-2.999zm114.268 0c-5.784 0-11.077 1.028-15.424 2.794-4.347 1.766-8.063 4.224-11.147 7.229-3.086 3.003-5.616 6.562-7.084 10.367-1.474 3.809-2.217 7.805-2.217 11.727 0 3.293.657 6.676 1.846 9.905 1.182 3.235 2.887 6.38 5.123 9.373 2.235 2.996 5.413 5.34 9.193 7.144 3.772 1.802 8.644 2.724 14.039 2.724 6.716 0 12.372-1.507 16.852-4.246 4.481-2.738 7.637-6.533 9.412-11.187 1.777-4.651 2.559-9.822 2.559-15.551 0-2.396-.234-4.675-.647-6.87l-49.639 13.779c.156 2.59.384 4.434.646 5.224.262.788.646 1.406 1.143 1.89.498.484 1.054.799 1.635.943.675.146 1.294.074 1.871-.262s1.056-.779 1.459-1.525c.396-.748.655-1.525.846-2.384.193-.869.289-1.733.289-2.617 0-6.957-1.123-12.645-3.428-16.827-2.296-4.183-5.412-7.31-9.256-9.29-3.845-1.981-8.556-2.999-14.378-2.999z"/>
                    </g>
                    <defs>
                        <clipPath id="clip0">
                            <rect width="651" height="192" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Laravel</h2>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation — freeing you to create without sweating the small things.
                        </p>
                    </div>
                    <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Livewire</h2>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            Livewire is a full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of Laravel.
                        </p>
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tailwind CSS</h2>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            Tailwind CSS is a utility-first CSS framework for creating custom designs without having to leave your HTML.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/collapse.min.js" defer></script>
</body>
</html>
