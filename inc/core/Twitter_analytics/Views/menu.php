<a 
    href="<?php _ec( get_module_url( "index/".$config['parent']['id'] ) )?>" 
    class="btn btn-outline btn-outline-soild me-2 mb-2 text-start actionItem <?php _ec( uri('segment', 3) == $config['parent']['id']?"active bg-light-primary":"" )?>"
    data-remove-other-active="true" 
    data-active="bg-light-primary" 
    data-result="html" 
    data-content="main-wrapper" 
    data-history="<?php _ec( get_module_url( "index/".$config['parent']['id'] ) )?>"
    >
    <i class="<?php _ec( $config['icon'] )?>" style="color: <?php _ec( $config['color'] )?>;" ></i> 
    <?php _e( $config['parent']['name'] )?>
</a>