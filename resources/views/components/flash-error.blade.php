@props(['messages'])

@if ($messages)
    <div class="item-center flex w-full sm:max-w-md mt-6 px-6 py-4 bg-red-200 dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                        
        <div class="m-auto alert alert-success text-red-700 text-center h-14 w-14 mr-5">
            <div class="mt-2">
                <svg class="m-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff0000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 2H8L2 8V16L8 22H16L22 16V8L16 2Z" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 8V12" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 16.0195V16" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            </div>
        </div>    
        
        <div class="m-auto alert alert-success text-red-700 dark:text-red-500">

            PVE Authentication Failed - Please contact the support team.
            {{-- {{ session('failed') }} --}}
        </div>
        
    </div>
@endif
    {{-- <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul> --}}