@props(['active'=>false])

<a {{ $attributes }} class="{{ $active? 'bg-gray-900 text-white': 'text-gray-300'}}block rounded-md px-3 py-2 text-sm font-medium text-white" aria-current="page">{{ $slot }}</a>