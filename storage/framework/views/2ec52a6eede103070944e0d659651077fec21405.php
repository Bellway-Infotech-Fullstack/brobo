<?php
$businessSetting = \App\Models\BusinessSetting::where(['key'=>'footer_text'])->first();
$footerText = (isset($businessSetting) && !empty($businessSetting)) ? $businessSetting->value : '';
?>
<div class="footer">
    <div class="row justify-content-between align-items-center">
        <div class="col">
            <p class="font-size-sm mb-0">
                

                <span
                    class="d-none d-sm-inline-block">{!!!!}</span>
            </p>
        </div>
        <div class="col-auto">
            <div class="d-flex justify-content-end">
                <!-- List Dot -->
                <ul class="list-inline list-separator">
                    <li class="list-inline-item">
                        <a class="list-separator-link" href="<?php echo e(route('admin.business-settings.business-setup')); ?>"><?php echo e(__('messages.business')); ?> <?php echo e(__('messages.setup')); ?></a>
                    </li>

                    <li class="list-inline-item">
                        <a class="list-separator-link" href="<?php echo e(route('admin.settings')); ?>"><?php echo e(__('messages.profile')); ?></a>
                    </li>

                    <li class="list-inline-item">
                        <!-- Keyboard Shortcuts Toggle -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="tio-home-outlined"></i>
                            </a>
                        </div>
                        <!-- End Keyboard Shortcuts Toggle -->
                    </li>
                </ul>
                <!-- End List Dot -->
            </div>
        </div>
    </div>
</div>
<?php /**PATH /opt/lampp/htdocs/brobo/resources/views/layouts/admin/partials/_footer.blade.php ENDPATH**/ ?>