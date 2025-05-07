<div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
        <li><strong>Área:</strong> {{ $row->user_area }}</li>
        <li><strong>Rol:</strong> {{ $row->role->rol_key}}</li>
    </ul>
</div>
