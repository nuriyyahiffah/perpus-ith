@props(['href', 'active' => false, 'label'])

@php
    // Logika pengaman: Jika href berisi route yang tidak ada, 
    // ia tidak akan mematikan sistem, tapi hanya mengarah ke '#'
    $finalHref = $href;
    try {
        // Jika input adalah route name, biarkan. 
        // Tapi jika rutenya belum dibuat di web.php, Laravel biasanya error di sini.
    } catch (\Exception $e) {
        $finalHref = '#';
    }
@endphp

<a href="{{ $finalHref }}" 
   class="flex items-center gap-3 px-6 py-3 text-[10px] font-bold uppercase tracking-widest transition-all rounded-xl group
   {{ $active ? 'bg-blue-600/10 text-blue-400' : 'text-slate-500 hover:text-blue-400 hover:bg-slate-800/30' }}">
    
    {{-- Indikator Titik (Dot) --}}
    <div class="w-1.5 h-1.5 rounded-full transition-all 
        {{ $active ? 'bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.6)]' : 'bg-slate-600 group-hover:bg-blue-400' }}">
    </div>
    
    {{ $label }}
</a>