@props(['id', 'image', 'type', 'ville', 'prix', 'note'])

<a href="{{ route('annonces.show', $id) }}" class="group block cursor-pointer">
    <!-- Image Skeleton / Placeholder -->
    <div class="aspect-[4/3] w-full overflow-hidden rounded-xl bg-gray-200 mb-4 relative">
        <img src="{{ $image }}" alt="{{ $type }} à {{ $ville }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <button class="absolute top-3 right-3 text-white hover:text-red-500 transition-colors p-2 z-10" onclick="event.preventDefault();">
            <svg class="h-6 w-6 filter drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    </div>
    
    <!-- Details -->
    <div class="flex justify-between items-start">
        <div>
            <h3 class="font-semibold text-slate-900 text-[15px] leading-tight">{{ $ville }}</h3>
            <p class="text-slate-500 text-sm mt-1 whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]">{{ $type }}</p>
            <div class="mt-2 text-slate-900">
                <span class="font-semibold text-[15px]">{{ $prix }} DH</span> <span class="text-sm font-normal text-slate-500">par nuit</span>
            </div>
        </div>
        <div class="flex items-center gap-1 text-sm font-medium text-slate-700">
            <svg class="h-[14px] w-[14px] text-slate-900 translate-y-[-1px]" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            {{ $note }}
        </div>
    </div>
</a>
