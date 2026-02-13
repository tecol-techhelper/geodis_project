<ol class="flex w-full rounded-md">
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!--[if BLOCK]><![endif]--><?php if(isset($item['url']) && count($items) - 1): ?>
            <li class="flex cursor-pointer text-sm text-blue-400 transition-colors duration-300 hover:text-blue-800 items-center">
                <i data-lucide="<?php echo e($item['icon']); ?>" class="w-4"></i>
                <a href="<?php echo e($item['url']); ?>" class="hover:underline px-2"><?php echo e($item['label']); ?></a>
                <span class="pointer-events-none mx-2 text-slate-800">
                    /
                </span>
            </li>
        <?php else: ?>
            <li class="flex cursor-pointer text-sm text-blue-800 transition-colors duration-300 items-center">
                <i data-lucide="<?php echo e($item['icon']); ?>" class="w-4"></i>
                <span class="px-2"><?php echo e($item['label']); ?></span>
            </li>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
</ol>
<?php /**PATH C:\Users\ASUS\Documents\Proyectos\geodis_project\resources\views/components/breadcrums.blade.php ENDPATH**/ ?>