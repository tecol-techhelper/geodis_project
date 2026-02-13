<div wire:poll.10s wire:key="notifications-bell" class="relative justify-center" x-data="{ open: false }">
    
    <button @click="open = !open" class="button"
        class="relative flex items-center justify-center bg-black-400 border-full
        relative w-6 md:w-10 h-auto flex items-center justify-center focus:outline-none">
        <svg viewBox="0 0 448 512" class="bell">
            <path
                d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z">
            </path>
        </svg>
        <!--[if BLOCK]><![endif]--><?php if($unreadCount > 0): ?>
            <span class="absolute top-0 right-0 bg-blue-600 text-white text-xs rounded-full px-1">
                <?php echo e($unreadCount); ?>

            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </button>

    
    <div x-show="open" @click.outside="open = false"
        class="absolute right-0 z-10 mt-2 bg-white border rounded shadow w-64">
        <ul class="max-h-60 overflow-y-auto p-2">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="border-b py-1 text-sm cursor-pointer hover:bg-gray-100"
                    wire:click="markAsRead(<?php echo e($notif->id); ?>)">
                    <strong><?php echo e($notif->title); ?></strong><br>
                    <?php echo e($notif->message); ?><br>
                    <span class="text-xs text-gray-500">Orden: <?php echo e($notif->purchase_order); ?></span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="py-2 text-center text-gray-500">Sin notificaciones</li>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </ul>

        <!--[if BLOCK]><![endif]--><?php if($unreadCount > 0): ?>
            <button wire:click="markAllAsRead" class="w-full text-blue-600 py-1 hover:bg-gray-100">
                Marcar todas como leídas
            </button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\Users\ASUS\Documents\Proyectos\geodis_project\resources\views/livewire/services/notifications-bell.blade.php ENDPATH**/ ?>