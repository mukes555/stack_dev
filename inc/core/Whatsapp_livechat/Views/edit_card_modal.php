<div class="modal fade" id="editCardModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _ec("Edit Board") ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="actionForm" action="<?php _ec(get_module_url("update_card/" . $card->id)) ?>" method="POST" data-redirect="<?php _ec(get_module_url("index/chats/" . $account->token)) ?>">

                    <div class="mb-3">
                        <input type="text" class="form-control form-control-solid" id="funnel_name" name="name" placeholder="<?php _e("Enter Name") ?>" value="<?php _ec($card->name) ?>">
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control form-control-solid" id="funnel_desc" name="desc" placeholder="<?php _e("Enter Description") ?>" value="<?php _ec($card->desc) ?>">
                    </div>

                    <div class="mb-3">
                        <input type="number" class="form-control form-control-solid" id="funnel_order" name="order" placeholder="<?php _e("Enter Order") ?>" value="<?php _ec($card->order) ?>">
                    </div>

                    <div class="mb-3">
                        <label>Color</label>
                        <input type="color" class="form-control form-control-solid" id="funnel_color" name="color" placeholder="<?php _e("Select color") ?>" value="<?php _ec($card->color) ?>">
                    </div>


                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary"><?php _e("Save") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>