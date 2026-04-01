<div id="transactions-container">
    <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden mb-8 shadow-sm">
        <div class="p-6 border-b border-[#2E4B3D]/12 flex items-center justify-between">
            <h2 class="text-xl font-medium text-secondary">Transaction History</h2>
            <i class="ri-history-line text-gray-300 text-xl"></i>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FBF9] border-b border-[#2E4B3D]/5">
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Transaction ID</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Date</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Type</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Amount</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-[#F9FBF9]/50 transition-colors group">
                        <td class="px-6 py-5">
                            <span class="text-xs font-black text-secondary uppercase tracking-tighter"><?php echo e($trx->transaction_no); ?></span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-gray-500"><?php echo e($trx->created_at->format('M d, Y')); ?></span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-gray-100 group-hover:bg-white transition-colors">
                                <?php echo e($trx->type); ?>

                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-black text-secondary">₹ <?php echo e(number_format($trx->total_amount, 2)); ?></span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-1 h-1 rounded-full bg-emerald-600 animate-pulse"></span>
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <button onclick="openTransactionModal(<?php echo e($trx->toJson()); ?>)" class="px-4 py-2 bg-white border border-[#2E4B3D]/12 rounded-xl text-[10px] font-black text-secondary uppercase tracking-widest hover:bg-secondary hover:text-white hover:border-secondary transition-all shadow-sm">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-[#F9FBF9] rounded-full flex items-center justify-center mb-6 shadow-inner">
                                    <i class="ri-wallet-line text-4xl text-[#2E4B3D]/20"></i>
                                </div>
                                <h3 class="text-xl font-black text-secondary mb-2 tracking-tight">No Transactions Yet</h3>
                                <p class="text-gray-400 font-medium text-sm">Your financial vault is currently empty.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($transactions->hasPages()): ?>
        <div class="px-8 py-6 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Displaying <span class="text-secondary"><?php echo e($transactions->firstItem()); ?></span> - <span class="text-secondary"><?php echo e($transactions->lastItem()); ?></span> of <span class="text-secondary"><?php echo e($transactions->total()); ?></span> records
            </div>
            <div class="pagination-links">
                <?php echo e($transactions->links()); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views\partials\transactions-table.blade.php ENDPATH**/ ?>