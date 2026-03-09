@php
$icons = [
    'pdf'     => ['color' => 'text-red-500', 'label' => 'PDF'],
    'word'    => ['color' => 'text-blue-500', 'label' => 'DOC'],
    'ppt'     => ['color' => 'text-orange-500', 'label' => 'PPT'],
    'excel'   => ['color' => 'text-green-500', 'label' => 'XLS'],
    'image'   => ['color' => 'text-violet-500', 'label' => 'IMG'],
    'archive' => ['color' => 'text-yellow-500', 'label' => 'ZIP'],
    'text'    => ['color' => 'text-slate-500', 'label' => 'TXT'],
    'file'    => ['color' => 'text-slate-400', 'label' => 'FILE'],
];
$icon = $icons[$type] ?? $icons['file'];
@endphp
<span class="text-xs font-bold {{ $icon['color'] }}">{{ $icon['label'] }}</span>
