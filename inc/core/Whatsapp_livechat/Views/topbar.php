<div class="d-flex align-items-stretch ms-3 border-start border-end">
    <div class="d-flex align-items-center">

        <form class="d-flex actionForm" action="<?php _e(get_module_url("info")) ?>" method="POST" data-result="html" data-content="ajax-result" date-redirect="false" data-loading="true">
            <select name="account" data-control="select2" data-hide-search="true" class="form-select form-select-sm bg-body fw-bold border-0 miw-130 auto-submit">
                <option value="" data-icon="fab fa-whatsapp" data-icon-color="#25d366" selected><span><?php _e("Select WhatsApp account") ?></span></option>
                <?php if (!empty($accounts)) : ?>

                    <?php foreach ($accounts as $key => $value) : ?>
                        <option value="<?php _ec($value->ids) ?>" data-img="<?php _ec(get_file_url($value->avatar)) ?>"><?php _ec($value->name) ?></option>
                    <?php endforeach ?>

                <?php else : ?>

                <?php endif ?>
            </select>
        </form>


    </div>
</div>