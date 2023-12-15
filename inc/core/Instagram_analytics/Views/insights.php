<?php if ($status == "success"): ?>
<div class="container my-5">
    
    <div class="d-flex align-items-center mb-5">
        <img src="<?php _ec( $page_info['profile_picture_url'] )?>" class="img-thumbnail me-3 border rounded-circle w-70">
        <div>
            <h2 class="mb-0"><?php _ec( $page_info['name'] )?></h2>
            <div class="text-gray-500">@<?php _ec( $page_info['username'] )?></div>
        </div>
    </div>

    <div class="row">
        
        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-primary w-52 h-52 text-primary m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-heart"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _e( $total_followers )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Followers")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-success w-52 h-52 text-success m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-megaphone"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _e( $total_page_reach )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Reach")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-danger w-52 h-52 text-danger m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-handshake"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _e( $total_page_impressions )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Impressions")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-warning w-52 h-52 text-warning m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-users"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _e( $total_page_profile_visits )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Profile Visits")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-info w-52 h-52 text-info m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-file-alt"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _e( $total_media )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Published Posts")?></div>
            </div>
        </div>

    </div>

    <ul class="nav nav-pills mb-4 m-t-40 bg-light-dark rounded" id="pills-tab">
        <li class="nav-item">
            <button class="nav-link bg-active-white text-gray-700 px-4 py-3 active text-active-success" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#analytic_growth" type="button" role="tab"><?php _e("Growth")?></button>
        </li>
        <li class="nav-item">
            <button class="nav-link bg-active-white text-gray-700 px-4 py-3 text-active-success" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#analytic_demographics" type="button" role="tab"><?php _e("Demographics")?></button>
        </li>
        <li class="nav-item">
            <button class="nav-link bg-active-white text-gray-700 px-4 py-3 text-active-success" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#analytic_performance" type="button" role="tab"><?php _e("Posts Performance")?></button>
        </li>
        <li class="nav-item">
            <button class="nav-link bg-active-white text-gray-700 px-4 py-3 text-active-success" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#analytic_history" type="button" role="tab"><?php _e("Posts History ")?></button>
        </li>
    </ul>
     
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="analytic_growth">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Followers")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Total number of new followers each day.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="followers_chart" class="h-300">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Profile Visits")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Total number of users who have viewed the Instagram User's profile.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="profile_visits_chart" class="h-300">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Reach")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of unique users who have viewed any least once of Instagram User's Media")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="reach_chart" class="h-300">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Impressions")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Total number of times the Instagram Media has been viewed.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="impressions_chart" class="h-300">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="analytic_demographics">
            <div class="row">
                <div class="col-md-8">
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Gender and Age")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of people who saw any of your posts at least once, grouped by age and gender.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                            <div class="card-toolbar d-flex">
                                <div class="me-5">
                                    <div class="fs-22 fw-9" style="color: rgba(92, 92, 176, 1);"> <?php _ec( (!empty($page_fans_gender_age) && isset($page_fans_gender_age['percent_male']))?$page_fans_gender_age['percent_male']:"0" )?>%</div>
                                    <div class="text-gray-500 text-uppercase"><?php _e("Male")?></div>
                                </div>

                                <div>
                                    <div class="fs-22 fw-9" style="color: rgba(147, 161, 252, 1);"><?php _ec( (!empty($page_fans_gender_age) && isset($page_fans_gender_age['percent_female']))?$page_fans_gender_age['percent_female']:"0" )?>%</div>
                                    <div class="text-gray-500 text-uppercase"><?php _e("Female")?></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="gender_and_age_chart" class="h-450">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Followers Location")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of people who saw any of your posts at least once, grouped by location.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="fans_location_chart" class="h-450">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Top Countries")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of people who saw any of your posts, by country")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="top_countries_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Top Cities")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of people who saw any of your posts, by cities")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="top_cities_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Top Languages")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of people who saw any of your posts, by language")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="top_languages_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="analytic_performance">
            <div class="row">
                <div class="col-md-8">

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Reach by time of day")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Average number of people who saw any of your posts at least once, grouped by time of day.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="reach_by_time_chart" class="h-450">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Impressions by time of day")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Average number of times saw any of your posts at least once, grouped by time of day.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="impressions_by_time_chart" class="h-450">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Report post by type of post")?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="reach_by_type_chart" class="h-450">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>

                            <div class="card border mt-4">
                                <div class="card-body">
                                    <table class="table table table-row-dashed">
                                        <thead>
                                            <tr class="fs-12 text-gray-600">
                                                <th scope="col" class="fw-6"><?php _e("Explain Metrics")?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Reach")?></div>
                                                    <div><?php _e("Average number of people who saw any of your posts at least once, grouped by type of post")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Impressions")?></div> 
                                                    <div><?php _e("Average number of times saw any of your posts at least once, grouped by type of post.")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Engagement")?></div> 
                                                    <div><?php _e("Average number of times people engaged with your posts through reactions, comments, shares and clicks, grouped by type of post.")?></div> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Report post by Weekdays")?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="reach_by_weekdays_chart" class="h-450">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>

                            <div class="card border mt-4">
                                <div class="card-body">
                                    <table class="table table table-row-dashed">
                                        <thead>
                                            <tr class="fs-12 text-gray-600">
                                                <th scope="col" class="fw-6"><?php _e("Explain Metrics")?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Reach")?></div>
                                                    <div><?php _e("Average number of people who saw any of your posts at least once, grouped by weekdays.")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Impressions")?></div> 
                                                    <div><?php _e("Average number of times saw any of your posts at least once, grouped by time of day.")?></div> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Top Posts")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Posts that got more engagement")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($top_posts)): ?>
                                
                                <?php foreach ($top_posts as $key => $value): ?>
                                    <div class="d-flex flex-stack">
                                        <div class="symbol symbol-55px me-3 border">
                                            <img src="<?php _ec( isset($value['thumbnail_url'])?$value['thumbnail_url']:$value['media_url'] )?>" class="align-self-center" alt="">
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2 text-over-all">
                                                <a href="<?php _ec( $value['permalink'] )?>" target="_blank" class="text-gray-800 text-hover-primary fs-14 fw-bold"><?php _ec( isset($value['caption'])?$value['caption']:"" )?></a>
                                                <span class="text-muted fw-semibold d-block fs-12"><?php _ec( sprintf(__("%s engagements"), $value['engagement_count']) )?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ( count($top_posts) != $key + 1 ): ?>
                                        <div class="separator separator-dashed my-4"></div>
                                    <?php endif ?>
                                <?php endforeach ?>

                            <?php else: ?>

                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>

                            <?php endif ?>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Recent posts")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The most recently published posts on your Page.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_posts)): ?>
                                
                                <?php foreach ($recent_posts as $key => $value): ?>
                                    <div class="d-flex flex-stack">
                                        <div class="symbol symbol-55px me-3 border">
                                            <img src="<?php _ec( isset($value['thumbnail_url'])?$value['thumbnail_url']:$value['media_url'] )?>" class="align-self-center" alt="">
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2 text-over-all">
                                                <a href="<?php _ec( $value['permalink'] )?>" target="_blank" class="text-gray-800 text-hover-primary fs-14 fw-bold"><?php _ec( isset($value['caption'])?$value['caption']:"" )?></a>
                                                <span class="text-muted fw-semibold d-block fs-12"><?php _ec( sprintf(__("%s engagements"), $value['engagement_count']) )?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ( count($recent_posts) != $key + 1 ): ?>
                                        <div class="separator separator-dashed my-4"></div>
                                    <?php endif ?>
                                <?php endforeach ?>

                            <?php else: ?>

                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>

                            <?php endif ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="analytic_history">
        
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Posts History ")?></span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="border-bottom">
                                <tr>
                                    <th scope="col" class="text-gray-500 p-10 p-l-20 text-uppercase"><?php _e("Post")?></td>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Impressions")?>" data-toggle="tooltip" data-placement="top"><i class="fad fa-eye"></i></a>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Reach")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-user-friends"></i></a>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Likes")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-thumbs-up"></i></a>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Shares")?>" data-toggle="tooltip" data-placement="top"><i class="fa-thin fa-bookmark"></i></a>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Comments")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-comment"></i></a>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Clicks")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-play"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <?php if (!empty($posts)): ?>
                                
                                    <?php foreach ($posts as $key => $value): ?>
                                        <tr>
                                            <td class="aj p-10 p-l-20">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="symbol symbol-55px me-3 border">
                                                        <img src="<?php _ec( isset($value['thumbnail_url'])?$value['thumbnail_url']:$value['media_url'] )?>" class="align-self-center" alt="">
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-over miw-150 mw-280"><?php _ec( isset($value['caption'])?$value['caption']:"" )?></span>
                                                        <span class="text-over miw-150 mw-280 fs-10 text-gray-400"><?php _ec( datetime_show( $value['timestamp'] ) )?> <a href="<?php _ec($value['permalink'])?>" target="_blank" class="ps-2"><i class="fad fa-eye"></i> <?php _e("View")?></a></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="aj p-10 text-center"><?php _ec($value['impressions_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['reach_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['like_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['saved_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['comments_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['plays_count'])?></td>
                                        </tr>
                                    <?php endforeach ?>

                                <?php else: ?>
                                    <tr>
                                        <td class="aj p-40" colspan="8">
                                            <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                                        </td>
                                    </tr>

                                <?php endif ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>


<script type="text/javascript">
    $(function(){
        <?php if (!empty($page_followers)): ?>
        Core.chart({
            id: 'followers_chart',
            categories: <?php _ec( $page_followers['columns'] )?>,
            data: [{
                name: '<?php _e("Followers")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $page_followers['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($page_profile_visits)): ?>
        Core.chart({
            id: 'profile_visits_chart',
            categories: <?php _ec( $page_profile_visits['columns'] )?>,
            data: [{
                name: '<?php _e("Profile views")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $page_profile_visits['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($page_reach)): ?>
        Core.chart({
            id: 'reach_chart',
            categories: <?php _ec( $page_reach['columns'] )?>,
            data: [{
                name: '<?php _e("Reach")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $page_reach['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($page_impressions)): ?>
        Core.chart({
            id: 'impressions_chart',
            categories: <?php _ec( $page_impressions['columns'] )?>,
            data: [{
                name: '<?php _e("Reach")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $page_impressions['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($page_fans_gender_age) && isset($page_fans_gender_age['columns'])): ?>
        Core.column_chart({
            id: 'gender_and_age_chart',
            categories: <?php _ec( $page_fans_gender_age['columns'] )?>,
            legend: true,
            xvisible: true,
            yvisible: true,
            ylineColor: 'rgba(239, 242, 245, 1)',
            data: [{
                name: '<?php _e("Male")?>',
                data: <?php _ec( $page_fans_gender_age['male'] )?>,
                color: 'rgba(92, 92, 176, 1)',
            }, {
                name: '<?php _e("Female")?>',
                data: <?php _ec( $page_fans_gender_age['female'] )?>,
                color: 'rgba(147, 161, 252, 1)'
            }],
        });
        <?php endif ?>

        <?php if (!empty($top_countries)): ?>
        Core.column_chart({
            id: 'top_countries_chart',
            categories: <?php _ec( $top_countries['columns'] )?>,
            stacking: "bar",
            xvisible: true,
            yvisible: false,
            gridLineColor: 'rgba(255, 255, 255, 0)',
            data: [{
                type: 'bar',
                borderRadius: 5,
                name: '<?php _e("Country")?>',
                data: <?php _ec( $top_countries['stats'] )?>,
                color: 'rgba(241, 104, 29, 1)',
            }],
            plotSeries: {
                dataLabels: {
                    enabled: true,
                    outside: true
                }
            }
        });
        <?php endif ?>

        <?php if (!empty($top_cities)): ?>
        Core.column_chart({
            id: 'top_cities_chart',
            categories: <?php _ec( $top_cities['columns'] )?>,
            stacking: "bar",
            xvisible: true,
            yvisible: false,
            gridLineColor: 'rgba(255, 255, 255, 0)',
            data: [{
                type: 'bar',
                borderRadius: 5,
                name: '<?php _e("Language")?>',
                data: <?php _ec( $top_cities['stats'] )?>,
                color: 'rgba(241, 104, 29, 1)',
            }],
            plotSeries: {
                dataLabels: {
                    enabled: true,
                    outside: true
                }
            }
        });
        <?php endif ?>

        <?php if (!empty($top_languages)): ?>
        Core.column_chart({
            id: 'top_languages_chart',
            categories: <?php _ec( $top_languages['columns'] )?>,
            stacking: "bar",
            xvisible: true,
            yvisible: false,
            gridLineColor: 'rgba(255, 255, 255, 0)',
            data: [{
                type: 'bar',
                borderRadius: 5,
                name: '<?php _e("Language")?>',
                data: <?php _ec( $top_languages['stats'] )?>,
                color: 'rgba(241, 104, 29, 1)',
            }],
            plotSeries: {
                dataLabels: {
                    enabled: true,
                    outside: true
                }
            }
        });
        <?php endif ?>

        <?php if (!empty($fans_countries_map)): ?>
        Core.map_chart({
            id: 'fans_location_chart',
            name: '<?php _e("Country")?>',
            minColor: 'rgba(214, 219, 254, 1)',
            maxColor: 'rgba(92, 92, 176, 1)',
            data: <?php _ec( $fans_countries_map )?>,
            
        });
        <?php endif ?>

        <?php if (!empty($reach_by_time) && isset($reach_by_time['columns'])): ?>
        Core.column_chart({
            id: 'reach_by_time_chart',
            categories: <?php _ec( $reach_by_time['columns'] )?>,
            legend: true,
            xvisible: true,
            yvisible: true,
            ylineColor: 'rgba(239, 242, 245, 1)',
            data: [{
                name: '<?php _e("Reach")?>',
                data: <?php _ec( $reach_by_time['stats'] )?>,
                color: 'rgba(92, 92, 176, 1)',
            }]
        });
        <?php endif ?>

        <?php if (!empty($reach_by_time) && isset($reach_by_time['columns'])): ?>
        Core.column_chart({
            id: 'impressions_by_time_chart',
            categories: <?php _ec( $reach_by_time['columns'] )?>,
            legend: true,
            xvisible: true,
            yvisible: true,
            ylineColor: 'rgba(239, 242, 245, 1)',
            data: [{
                name: '<?php _e("Impressions")?>',
                data: <?php _ec( $impressions_by_time['stats'] )?>,
                color: 'rgba(92, 92, 176, 1)',
            }]
        });
        <?php endif ?>

        <?php if (!empty($reach_by_type) && isset($reach_by_type['columns'])): ?>
        Core.column_chart({
            id: 'reach_by_type_chart',
            categories: <?php _ec( $reach_by_type['columns'] )?>,
            legend: true,
            xvisible: true,
            yvisible: true,
            ylineColor: 'rgba(239, 242, 245, 1)',
            data: [{
                name: '<?php _e("Reach")?>',
                data: <?php _ec( $reach_by_type['stats'] )?>,
                color: 'rgba(51, 154, 240, 1)',
            }, {
                name: '<?php _e("Impressions")?>',
                data: <?php _ec( $impressions_by_type['stats'] )?>,
                color: 'rgba(76, 110, 245, 1)',
            }, {
                name: '<?php _e('Engagement ')?> <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Average number of times saw any of your posts at least once, grouped by time of day.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>',
                data: <?php _ec( $engagement_by_type['stats'] )?>,
                color: 'rgba(34, 184, 207, 1)',
            }]
        });
        <?php endif ?>

        <?php if (!empty($reach_by_weekdays) && isset($reach_by_weekdays['columns'])): ?>
        Core.column_chart({
            id: 'reach_by_weekdays_chart',
            categories: <?php _ec( $reach_by_weekdays['columns'] )?>,
            legend: true,
            xvisible: true,
            yvisible: true,
            ylineColor: 'rgba(239, 242, 245, 1)',
            data: [{
                name: '<?php _e("Reach")?>',
                data: <?php _ec( $reach_by_weekdays['stats'] )?>,
                color: 'rgba(92, 92, 176, 1)',
            },{
                name: '<?php _e("Impressions")?>',
                data: <?php _ec( $impressions_by_weekdays['stats'] )?>,
                color: 'rgba(172, 186, 255, 1)',
            }]
        });
        <?php endif ?>

 
    });
</script>
<?php else: ?>
    <div class="container my-5">
        <div class="d-flex flex-column justify-content-center align-items-center h-300 text-gray-500">
            <i class="fad fa-puzzle-piece fs-70"></i>
            <div class="text-gray-500 fs-20 mt-3"><?php _e($message)?></div>
        </div>
    </div>
<?php endif ?>