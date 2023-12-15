<div class="sub-sidebar bg-white d-flex flex-column flex-row-auto">
    <div class="d-flex mb-10 p-20">
        <div class="d-flex align-items-center w-lg-400px">
            <form class="w-100 position-relative">
                <div class="input-group sp-input-group">
                  <span class="input-group-text bg-light border-0 fs-20 bg-gray-100 text-gray-800" id="sub-menu-search"><i class="fad fa-search"></i></span>
                  <input type="text" class="form-control form-control-solid ps-15 bg-light border-0 search-input" data-search="group-item" name="search" value="" placeholder="<?php _e("Search")?>" autocomplete="off">
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex mb-10 p-l-20 p-r-20 m-b-12">
        <h3 class="text-gray-800 fw-bold"><?php _e( $title )?></h3>
    </div>

    <div class="sp-menu n-scroll sp-menu-two menu menu-column menu-fit menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 p-l-20 p-r-20 m-b-12 fw-5 h-100">
        <div class="d-none menu-item">
            <div class="menu-content pb-2">
                <span class="menu-section text-muted text-uppercase fs-12 ls-1">Dashboard</span>
            </div>
        </div>

        <?php if ( !empty($analytics) ): ?>
            
            <?php $count = 0; ?>

            <?php foreach ($analytics as $key => $analytic): ?>
                
                <?php if ( isset($analytic['accounts']) ): ?>
                    

                    <?php if (!empty( $analytic['accounts'] )): ?>
                        <div class="menu-item">
                            <div class="menu-content pb-2 p-b-10">
                                <span class="menu-section text-muted text-uppercase fs-12 ls-1">
                                    <i class="<?php _ec($analytic['config']['icon'])?> pe-1" style="color: <?php _ec($analytic['config']['color'])?>"></i> <?php _e( $analytic['name'] )?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                        <?php foreach ($analytic['accounts'] as $key => $value): ?>
                            <?php $count += 1; ?>
                            <div class="sp-menu-item plan-item mb-1 group-item" data-active="bg-light-primary" >
                                <a 
                                    class="sp-menu-item d-flex align-items-center px-2 py-2 rounded bg-hover-light-primary actionItem <?php _e( uri('segment', 4 )==$value->ids?'bg-light-primary':'' )?>" 
                                    data-active="bg-light-primary" 
                                    href="<?php _ec( get_module_url( "index/".$analytic['config']['parent']['id']."/". $value->ids ) )?>" 
                                    data-remove-other-active="true" 
                                    data-result="html" 
                                    data-content="main-wrapper" 
                                    data-remove-other-active="true" data-active="bg-light-primary"
                                    data-history="<?php _ec( get_module_url( "index/".$analytic['config']['parent']['id']."/". $value->ids ) )?>"
                                >
                                    <div class="d-flex mb-10 me-auto w-drop">
                                        <div class="d-flex align-items-center mb-10 ">
                                            <div class="w-40 h-40 m-r-10">
                                                <img src="<?php _ec( get_file_url($value->avatar) )?>" class="h-40 align-self-center border rounded b-r-6">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 text-over">
                                            <h5 class="custom-list-title fw-bold text-gray-800 mb-1 fs-14 text-over"><?php _ec( get_data($value, "name") )?></h5>
                                            <span class="text-gray-400 fs-12 text-over"><?php _e( ucfirst($value->category) )?></span>
                                        </div>
                                    </div>

                                </a>
                            </div>

                        <?php endforeach ?>
                        </div>
                        
                    <?php endif ?>
                    
                <?php endif ?>

            <?php endforeach ?>

            <?php if ($count == 0): ?>
                
                <div class="d-flex flex-column justify-content-center align-items-center text-gray-500 h-100 mih-300">
                    <img class="mh-190 mb-4" alt="" src="<?php _e( get_theme_url() ) ?>Assets/img/empty2.png">
                   <div>
                        <a class="btn btn-primary btn-sm b-r-30" href="<?php _e( base_url("account_manager") )?>" >
                            <i class="fad fa-plus"></i> <?php _ec("Add account")?>
                        </a>
                    </div>
                </div>

            <?php endif ?>
            
        <?php endif ?>
    </div>
</div>