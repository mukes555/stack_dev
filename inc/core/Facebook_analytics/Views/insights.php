<?php if ($status == "success"): ?>
<div class="container my-5">
    <div class="d-flex align-items-center mb-5">
        <img src="<?php _ec( $page_info['picture']['data']['url'] )?>" class="img-thumbnail me-3 border rounded-circle w-70">
        <div>
            <h2 class="mb-0"><?php _ec( $page_info['name'] )?></h2>
            <div class="text-gray-600"><?php _ec( $page_info['category'] )?></div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-6 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-primary w-52 h-52 text-primary m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-heart"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $total_fans )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Total Likes")?></div>
            </div>
        </div>

        <div class="col-6 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-info w-52 h-52 text-info m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-users"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $total_followers )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Total Follows")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-success w-52 h-52 text-success m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-megaphone"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $total_page_reach )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Reach")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-dark w-52 h-52 text-dark m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-eye"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $total_page_impressions )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Impressions")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-danger w-52 h-52 text-danger m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-handshake"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $total_all_engagements )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Engagements")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-warning w-52 h-52 text-warning m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-users"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $total_page_views )?></div>
                <div class="fs-20 fw-5 text-gray-500"><?php _e("Page Views")?></div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="border w-52 h-52 text-secondary m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-file-alt"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $total_posts )?></div>
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
            
            <div class="row">
                
                <div class="col-md-8">
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Fans History")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The total of likes on your Page by day")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="fans_history_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Page Views")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of times a Facebook pages has viewed by logged and logged out people")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="page_views_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">

                    <?php 
                    $net_fans_color = "text-gray-500";
                    if ($net_fans_count > 0) {
                        $net_fans_color = "text-success";
                    } else if( $net_fans_count < 0 ) {
                        $net_fans_color = "text-danger";
                    }
                    ?>

                    <ul class="list-group mb-4">
                        <li class="list-group-item px-4 py-4 d-flex justify-content-between">
                            <span><?php _e("New Fans")?></span>
                            <span class="fs-20 text-gray-500 fw-6 text-danger"><?php _ec( $new_fans_count )?></span>
                        </li>
                        <li class="list-group-item px-4 py-4 d-flex justify-content-between">
                            <span><?php _e("Lost Fans")?></span>
                            <span class="fs-20 text-gray-500 fw-6 text-danger"><?php _ec( $lost_fans_count )?></span>
                        </li>
                        <li class="list-group-item px-4 py-4 d-flex justify-content-between">
                            <span><?php _e("Net Fans")?></span>
                            <span class="fs-20 fw-6 <?php _ec( $net_fans_color )?> "><?php _ec( add_prefix_numer($net_fans_count) )?></span>
                        </li>
                    </ul>

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Fans Online")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of people who liked your Page sorted by the time when they are online.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="fans_online_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-12">

                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="me-2"><?php _e("Gained & Lost Fans")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of likes that you have gained and lost each day")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="gained_and_lost_fans">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>
                        </div>
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
                                <span class="me-2"><?php _e("Fans Location")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of people who saw any of your posts at least once, grouped by location.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-body">
                                <div id="fans_location_chart" class="h-450">
                                    <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                                </div>
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
                                <span class="me-2"><?php _e("Reach")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of unique users had any content with your page, per day and per page.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="reach_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>

                            <div class="card border mt-4">
                                <div class="card-body">
                                    <table class="table table table-row-dashed">
                                        <thead>
                                            <tr class="fs-12 text-gray-600">
                                                <th scope="col" class="fw-6"><?php _e("Reach Metrics")?></td>
                                                <th scope="col" class="text-end"><?php _e("Total")?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-6"><?php _e("Total Reach")?></td>
                                                <td class="text-end fw-6">
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Total rech may not be exactly equal to the sum of organic and paid reach.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                    <?php _ec( $total_page_reach )?>
                                                </td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td><?php _e("Organic Reach")?></td>
                                                <td class="text-end fw-6"><?php _ec( $total_page_reach_organic )?></td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td><?php _e("Paid Reach")?></td>
                                                <td class="text-end fw-6"><?php _ec( $total_page_reach_paid )?></td>
                                            </tr>
                                            <tr class="text-gray-800 fw-6 fs-14">
                                                <td>
                                                    <?php _e("Average Daily Reach per Page")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The average number of unique users who were shown any content associated with your page, per day and per page.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                </td>
                                                <td class="text-end fw-6"><?php _ec( $average_page_reach )?></td>
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
                                <span class="me-2"><?php _e("Impressions")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Review how your content was seen by the Facebook community during the reporting period")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="impressions_chart" class="h-300"></div>

                            <div class="card border mt-4">
                                <div class="card-body">
                                    <table class="table table table-row-dashed">
                                        <thead>
                                            <tr class="fs-12 text-gray-600">
                                                <th scope="col" class="fw-6"><?php _e("Impression Metrics")?></td>
                                                <th scope="col" class="text-end"><?php _e("Total")?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-6"><?php _e("Total Impressions")?></td>
                                                <td class="text-end fw-6">
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Total impressions may not be exactly equal to the sum of organic and paid impressions.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                    <?php _ec( $total_page_impressions )?>
                                                </td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td><?php _e("Organic Impressions")?></td>
                                                <td class="text-end fw-6"><?php _ec( $total_page_impressions_organic )?></td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td><?php _e("Paid Impressions")?></td>
                                                <td class="text-end fw-6"><?php _ec( $total_page_impressions_paid )?></td>
                                            </tr>
                                            <tr class="text-gray-800 fw-6 fs-14">
                                                <td>
                                                    <?php _e("Average Daily Impressions per Page")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The average number of times content associated with your page was displayed to users, per day and per page.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                </td>
                                                <td class="text-end fw-6"><?php _ec( $average_page_impressions )?></td>
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
                                <span class="me-2"><?php _e("Engagement")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("See how people are engaging with your posts during the reporting period.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="engagement_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>

                            <div class="card border mt-4">
                                <div class="card-body">
                                    <table class="table table-row-dashed">
                                        <thead>
                                            <tr class="fs-12 text-gray-600">
                                                <th scope="col" class="fw-6"><?php _e("Engagement Metrics")?></td>
                                                <th scope="col" class="text-end"><?php _e("Total")?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-6"><?php _e("Total Engagements")?></td>
                                                <td class="text-end fw-6">
                                                    <?php _ec( $total_all_engagements )?>
                                                </td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td>
                                                    <?php _e("Reactions")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of times users reacted to posts with like, care, love, haha, wow, sad, or angry reactions.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>   
                                                </td>
                                                <td class="text-end fw-6"><?php _ec( $total_engagement_reactions )?></td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td>
                                                    <?php _e("Comments")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of times users commented or replied to posts.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                </td>
                                                <td class="text-end fw-6"><?php _ec( $total_engagement_comments )?></td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td>
                                                    <?php _e("Shares")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of times users shared posts. This includes internal shares using the share action on the post, as well as external ones shared by copying the post share link.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                </td>
                                                <td class="text-end fw-6"><?php _ec( $total_engagement_shares )?></td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td>
                                                    <?php _e("Post link clicks")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of times users clicked on the links within your posts.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                </td>
                                                <td class="text-end fw-6"><?php _ec( $total_engagement_link_clicks )?></td>
                                            </tr>
                                            <tr class="text-gray-500 fs-12">
                                                <td>
                                                    <?php _e("Post other clicks")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of times users clicked anywhere in your posts without opening a web link, reacting to, commenting on, or sharing your post. This includes clicks to play a video and view a photo.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a> 
                                                </td>
                                                <td class="text-end fw-6"><?php _ec( $total_engagement_other_clicks )?></td>
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
                                <span class="me-2"><?php _e("Engagement Rate")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("See how engaged people are with your posts during the reporting period.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="engagement_rate_chart" class="h-300">
                                <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                            </div>

                            <div class="card border mt-4">
                                <div class="card-body">
                                    <table class="table table-row-dashed">
                                        <thead>
                                            <tr class="fs-12 text-gray-600">
                                                <th scope="col" class="fw-6"><?php _e("Engagement Rate Metrics")?></td>
                                                <th scope="col" class="text-end"><?php _e("Rate")?></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fs-12 text-gray-600">
                                                    <?php _e("Engagement Rate (per Impression)")?>
                                                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The number of times users engaged with your content as a percentage of impressions. This indicates how engaged people are with your brand.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>  
                                                </td>
                                                <td class="text-end fs-12 text-gray-600">
                                                    <?php  _ec( (!empty($engagement_rate))?$engagement_rate['rate']:0 )?><?php _ec("%")?>
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
                                <span class="me-2"><?php _e("Video Performance")?></span>
                                <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("View your aggregate video performance during the reporting period.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div id="video_view_metrics_chart" class="h-250"></div>
                                </div>
                                <div class="col">
                                    <div id="video_view_breakdown_chart" class="h-250"></div>
                                </div>
                                <div class="col">
                                    <div id="video_click_breakdown_chart" class="h-250"></div>
                                </div>
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
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Organic Full")?></div>
                                                    <div><?php _e("The number of times users organically viewed your page’s videos for at least 30 seconds or nearly to the end if the video is shorter than 30 seconds.")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Organic Partial")?></div> 
                                                    <div><?php _e("The number of times users organically viewed your page’s videos for at least 3 seconds, but no more than 30 seconds and not to the end of the video.")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Organic Views")?></div> 
                                                    <div><?php _e("The number of times users organically viewed your page’s videos for at least 3 seconds.")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Paid Views")?></div> 
                                                    <div><?php _e("The number of times users viewed your page’s videos for at least 3 seconds after a paid promotion.")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Click Plays")?></div> 
                                                    <div><?php _e("The number of times users viewed your page’s videos for at least 3 seconds after the user clicked to play the video.")?></div> 
                                                </td>
                                            </tr>
                                            <tr class="text-gray-800 fs-12">
                                                <td class="py-4">
                                                    <div class="fw-6 text-dark mb-2"><?php _e("Auto Plays")?></div> 
                                                    <div><?php _e("The number of times users viewed your page’s videos for at least 3 seconds after being played automatically.")?></div> 
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

                                    <?php 
                                    $message = isset($value['message'])?$value['message']:"";
                                    $img = "";
                                    if(!empty($value['attachments'])){
                                        if( isset( $value['attachments']['data'][0]['description'] ) ){
                                            $message = $value['attachments']['data'][0]['description'];
                                        }
                                    }

                                    if( isset($value['full_picture']) ){
                                        $img = $value['full_picture'];
                                    }
                                    ?>

                                    <div class="d-flex flex-stack">
                                        <div class="symbol symbol-55px me-3 border">
                                            <?php if ($img != ""): ?>
                                                <img src="<?php _ec( $img )?>" class="align-self-center" alt="">
                                            <?php else: ?>
                                                <div class="border h-55 w-55 b-r-6 text-primary d-flex justify-content-center align-items-center fs-20">
                                                    <i class="fad fa-align-center"></i>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2 text-over-all">
                                                <a href="<?php echo $value['permalink_url'] ?? '#' ?>" target="_blank" class="text-gray-800 text-hover-primary fs-14 fw-bold"><?php _ec($message)?></a>
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

                                    <?php 
                                    $message = isset($value['message'])?$value['message']:"";
                                    $img = "";
                                    if(!empty($value['attachments'])){
                                        if( isset( $value['attachments']['data'][0]['description'] ) ){
                                            $message = $value['attachments']['data'][0]['description'];
                                        }
                                    }

                                    if( isset($value['full_picture']) ){
                                        $img = $value['full_picture'];
                                    }
                                    ?>

                                    <div class="d-flex flex-stack">
                                        <div class="symbol symbol-55px me-3 border">
                                            <?php if ($img != ""): ?>
                                                <img src="<?php _ec( $img )?>" class="align-self-center" alt="">
                                            <?php else: ?>
                                                <div class="border h-55 w-55 b-r-6 text-primary d-flex justify-content-center align-items-center fs-20">
                                                    <i class="fad fa-align-center"></i>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                        <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                            <div class="flex-grow-1 me-2 text-over-all">
                                            <a href="<?php echo $value['permalink_url'] ?? '#' ?>" target="_blank" class="text-gray-800 text-hover-primary fs-14 fw-bold"><?php _ec($message)?></a>
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
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Shares")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-share-alt"></i></a>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Comments")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-comment"></i></a>
                                    </th>
                                    <th scope="col" class="text-center">
                                        <a href="javascript:void(0);" class="text-gray-500 p-10" title="<?php _e("Clicks")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-mouse-pointer"></i></a>
                                    </th>
                                    <th scope="col" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <?php if (!empty($posts)): ?>
                                
                                    <?php foreach ($posts as $key => $value): ?>

                                        <?php 
                                        $message = isset($value['message'])?$value['message']:"";
                                        $img = "";
                                        if(!empty($value['attachments'])){
                                            if( isset( $value['attachments']['data'][0]['description'] ) ){
                                                $message = $value['attachments']['data'][0]['description'];
                                            }
                                        }

                                        if( isset($value['full_picture']) ){
                                            $img = $value['full_picture'];
                                        }
                                        ?>
                                        <tr>
                                            <td class="aj p-10 p-l-20">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="symbol symbol-55px me-3 border">
                                                        <?php if ($img != ""): ?>
                                                            <img src="<?php _ec( $img )?>" class="align-self-center" alt="">
                                                        <?php else: ?>
                                                            <div class="border h-55 w-55 b-r-6 text-primary d-flex justify-content-center align-items-center fs-20">
                                                                <i class="fad fa-align-center"></i>
                                                            </div>
                                                        <?php endif ?>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-over w-150"><?php _ec($message)?></span>
                                                        <span class="text-over w-150 fs-10 text-gray-400"><?php _ec( datetime_show( $value['created_time'] ) )?> <a href="<?php _ec($value['permalink_url'])?>" target="_blank" class="ps-2"><i class="fad fa-eye"></i> <?php _e("View")?></a></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="aj p-10 text-center"><?php _ec($value['impressions'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['reach'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['reaction_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['share_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['comment_count'])?></td>
                                            <td class="aj p-10 text-center"><?php _ec($value['click_count'])?></td>
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
        <?php if (!empty($fans_history)): ?>
        Core.chart({
            id: 'fans_history_chart',
            categories: <?php _ec( $fans_history['columns'] )?>,
            data: [{
                name: '<?php _e("Fans")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $fans_history['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($page_views)): ?>
        Core.chart({
            id: 'page_views_chart',
            categories: <?php _ec( $page_views['columns'] )?>,
            data: [{
                name: '<?php _e("Views")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $page_views['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($fans_online)): ?>
        Core.chart({
            id: 'fans_online_chart',
            categories: <?php _ec( $fans_online['columns'] )?>,
            data: [{
                name: '<?php _e("Fans online")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $fans_online['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($gained_and_lost_fans)): ?>
        Core.column_chart({
            id: 'gained_and_lost_fans',
            categories: <?php _ec( $gained_and_lost_fans['columns'] )?>,
            stacking: "normal",
            xvisible: true,
            yvisible: true,
            ylineColor: 'rgba(239, 242, 245, 1)',
            data: [{
                type: 'spline',
                color: 'rgba(78, 78, 78, .9)',
                name: '<?php _e("Net")?>',
                dashStyle: 'ShortDot',
                data: <?php _ec( $gained_and_lost_fans['net_fans'] )?>,
            }, {
                name: '<?php _e("Gained")?>',
                data: <?php _ec( $gained_and_lost_fans['gained_fans'] )?>,
                color: 'rgba(80, 205, 137, 1)',
            }, {
                name: '<?php _e("Lost")?>',
                data: <?php _ec( $gained_and_lost_fans['lost_fans'] )?>,
                color: 'rgba(255, 106, 136, 1)'
            }],
        });
        <?php endif ?>

        <?php if (!empty($fans_gender_age) && isset($fans_gender_age['columns'])): ?>
        Core.column_chart({
            id: 'gender_and_age_chart',
            categories: <?php _ec( $fans_gender_age['columns'] )?>,
            legend: true,
            xvisible: true,
            yvisible: true,
            ylineColor: 'rgba(239, 242, 245, 1)',
            data: [{
                name: '<?php _e("Male")?>',
                data: <?php _ec( $fans_gender_age['male'] )?>,
                color: 'rgba(92, 92, 176, 1)',
            }, {
                name: '<?php _e("Female")?>',
                data: <?php _ec( $fans_gender_age['female'] )?>,
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

        <?php if (!empty($page_reach_organic) && !empty($page_reach_paid)): ?>
        Core.chart({
            id: 'reach_chart',
            categories: <?php _ec( $page_reach_organic['columns'] )?>,
            legend: true,
            data: [{
                name: '<?php _e("Organic Reach")?>',
                lineColor: 'rgba(80, 205, 103, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(80, 205, 103, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(80, 205, 103, 1)',
                data: <?php _ec( $page_reach_organic['stats'] )?>,
            },{
                name: '<?php _e("Paid Reach")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $page_reach_paid['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (!empty($page_impressions_organic) && !empty($page_impressions_paid)): ?>
        Core.chart({
            id: 'impressions_chart',
            categories: <?php _ec( $page_impressions_organic['columns'] )?>,
            legend: true,
            data: [{
                name: '<?php _e("Organic Impressions")?>',
                lineColor: 'rgba(80, 205, 103, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(80, 205, 103, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(80, 205, 103, 1)',
                data: <?php _ec( $page_impressions_organic['stats'] )?>,
            },{
                name: '<?php _e("Paid Impressions")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $page_impressions_paid['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if (
            !empty($engagement_reactions) && 
            !empty($engagement_comments) && 
            !empty($engagement_shares) && 
            !empty($engagement_link_clicks) && 
            !empty($engagement_other_clicks)
        ): ?>
        Core.chart({
            id: 'engagement_chart',
            categories: <?php _ec( $engagement_reactions['columns'] )?>,
            legend: true,
            data: [{
                type: 'spline',
                name: '<?php _e("Reactions")?>',
                lineColor: 'rgba(0, 158, 247, 1)',
                color: 'rgba(0, 158, 247, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $engagement_reactions['stats'] )?>,
            },{
                type: 'spline',
                name: '<?php _e("Comments")?>',
                lineColor: 'rgba(80, 205, 137, 1)',
                color: 'rgba(80, 205, 137, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $engagement_comments['stats'] )?>,
            },{
                type: 'spline',
                name: '<?php _e("Shares")?>',
                lineColor: 'rgba(241, 65, 108, 1)',
                color: 'rgba(241, 65, 108, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $engagement_shares['stats'] )?>,
            },{
                type: 'spline',
                name: '<?php _e("Post link clicks")?>',
                lineColor: 'rgba(255, 199, 0, 1)',
                color: 'rgba(255, 199, 0, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $engagement_link_clicks['stats'] )?>,
            },{
                type: 'spline',
                name: '<?php _e("Post other clicks")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                color: 'rgba(60, 88, 208, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $engagement_other_clicks['stats'] )?>,
            }]
        });
        <?php endif ?>

        <?php if ( !empty($engagement_rate) ): ?>
        Core.chart({
            id: 'engagement_rate_chart',
            categories: <?php _ec( $engagement_rate['columns'] )?>,
            legend: true,
            data: [{
                type: 'spline',
                name: '<?php _e("Engagement Rate")?>',
                lineColor: 'rgba(80, 205, 103, 1)',
                color: 'rgba(80, 205, 103, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $engagement_rate['stats'] )?>,
            }]
        });
        <?php endif ?>

        Core.chart({
            id: 'video_view_metrics_chart',
            categories: '',
            legend: true,
            data: [{
                type: 'pie',
                name: '<?php _e("Total")?>',
                data: [{
                    name: '<?php _e("Organic Full")?>',
                    y: <?php _ec( $video_organic_full )?>,
                    color: 'rgba(60, 88, 208, 1)',
                }, {
                    name: '<?php _e("Organic Partial")?>',
                    y: <?php _ec( $video_organic_partial )?>,
                    color: 'rgba(80, 205, 103, 1)',
                }],
                size: 150,
                innerSize: '60%',
                showInLegend: true,
                dataLabels: {
                    enabled: false
                }
            }]
        });

        Core.chart({
            id: 'video_view_breakdown_chart',
            categories: '',
            legend: true,
            data: [{
                type: 'pie',
                name: '<?php _e("Percent")?>',
                data: [{
                    name: '<?php _e("Organic Views")?>',
                    y: <?php _ec( $video_view_organic )?>,
                    color: 'rgba(60, 88, 208, 1)',
                }, {
                    name: '<?php _e("Paid Views")?>',
                    y: <?php _ec( $video_view_paid )?>,
                    color: 'rgba(80, 205, 103, 1)',
                }],
                size: 150,
                innerSize: '60%',
                showInLegend: true,
                dataLabels: {
                    enabled: false
                }
            }]
        });

        Core.chart({
            id: 'video_click_breakdown_chart',
            categories: '',
            legend: true,
            data: [{
                type: 'pie',
                name: '<?php _e("Percent")?>',
                data: [{
                    name: '<?php _e("Click Plays")?>',
                    y: <?php _ec( $video_click_to_play )?>,
                    color: 'rgba(60, 88, 208, 1)',
                }, {
                    name: '<?php _e("Auto Plays")?>',
                    y: <?php _ec( $video_autoplayed )?>,
                    color: 'rgba(80, 205, 103, 1)',
                }],
                size: 150,
                innerSize: '60%',
                showInLegend: true,
                dataLabels: {
                    enabled: false
                }
            }]
        });
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

