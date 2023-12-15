<div class="sub-sidebar bg-white d-flex flex-column flex-row-auto">
    <div class="d-flex mb-10 p-20">
        <div class="d-flex align-items-center w-lg-400px">
            <form class="w-100 position-relative ">
                <div class="input-group sp-input-group">
                    <span class="input-group-text bg-light border-0 fs-20 bg-gray-100 text-gray-800" id="sub-menu-search"><i class="fad fa-search"></i></span>
                    <input type="text" class="form-control form-control-solid ps-15 bg-light border-0 search-input" data-search="sp-menu-item" name="search" value="" placeholder="<?php _e("Search") ?>" autocomplete="off">
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex mb-10 p-l-20 p-r-20 m-b-12">
        <h3 class="text-gray-800 fw-bold"><?php _e($title) ?></h3>
    </div>

    <form class="actionForm" action="<?php _e(get_module_url("info")) ?>" method="POST" data-result="html" data-content="ajax-result" data-redirect="false" data-loading="true">

        <div class="menu-item">

            <select name="account" data-control="select2" data-hide-search="true" class="form-select form-select-sm bg-body fw-bold border-0 miw-130 auto-submit">
                <option value="" data-icon="fab fa-whatsapp" data-icon-color="#25d366" selected><span><?php _e("Select WhatsApp account") ?></span></option>
                <?php if (!empty($accounts)) : ?>

                    <?php foreach ($accounts as $key => $value) : ?>
                        <option value="<?php _ec($value->ids) ?>" data-img="<?php _ec(get_file_url($value->avatar)) ?>"><?php _ec($value->name) ?></option>
                    <?php endforeach ?>

                <?php else : ?>

                <?php endif ?>
            </select>

        </div>
    </form>

    <div class="menu-item">
        <div class="menu-content pb-2 p-b-10">
            <span class="menu-section text-muted text-uppercase fs-12 ls-1">
                <?php _e('Options') ?>
            </span>
        </div>
    </div>
    <div class="sp-menu n-scroll sp-menu-two menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 m-b-12 fw-5 h-100">
        <div class="card b-r-10 mb-5">
            <div class="ajax-result">
                <?php _ec($this->include('Core\Whatsapp\Views\empty'), false); ?>
            </div>
        </div>
    </div>
</div>