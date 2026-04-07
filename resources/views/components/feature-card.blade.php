@props(['icon', 'title', 'description'])

<div class="flex flex-col items-center text-center p-8 bg-slate-50 rounded-3xl border border-slate-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 hover:bg-white group">
    <div class="w-16 h-16 bg-blue-50 group-hover:bg-blue-100 text-blue-900 rounded-2xl flex items-center justify-center mb-6 transition-colors duration-300">
        {!! $icon !!}
    </div>
    <h3 class="text-lg font-bold text-slate-900 mb-3">{{ $title }}</h3>
    <p class="text-slate-600 leading-relaxed text-sm">{{ $description }}</p>
</div>
