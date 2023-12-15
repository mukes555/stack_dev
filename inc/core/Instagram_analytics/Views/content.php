<form class="actionForm" action="<?php _e( base_url("instagram_analytics/insights/".uri("segment", 4)) )?>" method="POST" data-result="html" data-content="insights" date-redirect="false" data-loading="false">
    <div class="container my-5">
        <div class="d-flex justify-content-end mb-4">
            <div class="daterange"></div>
        </div>
    </div>
</form>
<div class="insights">
	<?php _ec( $this->include('Core\Analytics\Views\loading'), false);?>
</div>

<script type="text/javascript">
    $(function(){
    	Core.datarange();
    });
</script>