<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
    @php
        function formatFileSize($bytes, $decimals = 2)
        {
            if ($bytes < 1024) {
                return $bytes . ' B';
            }
            $units = ['KB', 'MB', 'GB', 'TB'];
            $i = floor(log($bytes, 1024));
            return round($bytes / pow(1024, $i), $decimals) . ' ' . $units[$i - 1];
        }
    @endphp
    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
        <li><strong>Extension de Archivo:</strong> {{ $row->file_extension }} </li>
        <li><strong>Fecha de Cargue:</strong> {{ $row->uploaded_at }} </li>
        <li><strong>Tamaño de archivo:</strong> {{ formatFileSize($row->file_size) }} </li>
    </ul>
</div>
