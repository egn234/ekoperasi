<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation" class="flex justify-center mt-6">
    <ul class="inline-flex items-center -space-x-px">

        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="block px-3 py-2 ml-0 leading-tight text-slate-500 bg-white border border-slate-200 rounded-l-lg hover:bg-slate-100 hover:text-slate-700 transition-colors">
                    <span class="sr-only">First</span>
                    <i data-lucide="chevrons-left" class="w-4 h-4"></i>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="block px-3 py-2 leading-tight text-slate-500 bg-white border border-slate-200 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                    <span class="sr-only">Previous</span>
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="block px-3 py-2 ml-0 leading-tight text-slate-300 bg-slate-50 border border-slate-200 rounded-l-lg cursor-not-allowed">
                    <i data-lucide="chevrons-left" class="w-4 h-4"></i>
                </span>
            </li>
            <li>
                <span class="block px-3 py-2 leading-tight text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </span>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="block px-3 py-2 leading-tight border <?= $link['active'] ? 'z-10 text-blue-600 border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700' : 'text-slate-500 bg-white border-slate-200 hover:bg-slate-100 hover:text-slate-700' ?> transition-colors">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="block px-3 py-2 leading-tight text-slate-500 bg-white border border-slate-200 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                    <span class="sr-only">Next</span>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="block px-3 py-2 leading-tight text-slate-500 bg-white border border-slate-200 rounded-r-lg hover:bg-slate-100 hover:text-slate-700 transition-colors">
                    <span class="sr-only">Last</span>
                    <i data-lucide="chevrons-right" class="w-4 h-4"></i>
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="block px-3 py-2 leading-tight text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            </li>
            <li>
                <span class="block px-3 py-2 leading-tight text-slate-300 bg-slate-50 border border-slate-200 rounded-r-lg cursor-not-allowed">
                    <i data-lucide="chevrons-right" class="w-4 h-4"></i>
                </span>
            </li>
        <?php endif ?>
    </ul>
</nav>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>