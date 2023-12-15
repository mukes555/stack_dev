<div class="container my-5">
    <div class="alert alert-warning d-flex align-items-center">
        <div class="fs-40 me-3"><i class="fad fa-exclamation-circle"></i></div>
        <div>
            <div class="fw-bold"><?php _e("Notification")?></div>
            <?php _e("Twitter Analytics is only available via Twitter Cookie Login")?>
        </div>
    </div>
</div>

<?php if ($status == "success"): ?>

<?php
$account_data = $stats->data;
$top_hashtags = $account_data->top_hashtags;
$top_mentions = $account_data->top_mentions;
$profile_info = $account_data->profile_info;
$feeds = $account_data->feeds;
$follower_count = $profile_info->followers_count;
$media_count = $profile_info->statuses_count;
$total_days = $stats->total_days;
?>



<div class="container my-5">
    <div class="d-flex align-items-center mb-5">
        <img src="<?php _ec( $profile_info->profile_image_url_https )?>" class="img-thumbnail me-3 border rounded-circle w-70">
        <h2>
            <div><?php _ec( $profile_info->name )?></div>
            <div class="fs-14 fw-6 text-gray-500">@<?php _ec( $profile_info->screen_name )?></div>
        </h2>
    </div>

    <div class="row">
        
        <div class="col-4 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-danger w-52 h-52 text-danger m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-handshake"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $account_data->engagement )?>%</div>
                <div class="fs-20 fw-5 text-gray-500">
                    <?php _e("Engagement")?> 
                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("The engagement rate is the number of active favorites / retweets on each post")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                </div>
            </div>
        </div>

        <div class="col-4 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-primary w-52 h-52 text-primary m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-heart"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $account_data->average_likes )?></div>
                <div class="fs-20 fw-5 text-gray-500">
                    <?php _e("Average Favorites")?>
                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Average favorites based on the last 10 posts")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                </div>
            </div>
        </div>

        <div class="col-4 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-success w-52 h-52 text-success m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-retweet"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( $account_data->average_comments )?></div>
                <div class="fs-20 fw-5 text-gray-500">
                    <?php _e("Average Retweets")?>
                    <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Average retweets based on the last 10 posts")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a>
                </div>
            </div>
        </div>

        <div class="col-4 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-warning w-52 h-52 text-warning m-auto d-flex align-items-center justify-content-center fs-25 rounded-circle">
                    <i class="fad fa-user-plus"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( number_format($profile_info->followers_count) )?></div>
                <div class="fs-20 fw-5 text-gray-500">
                    <?php _e("Followers")?> 
                </div>
            </div>
        </div>

        <div class="col-4 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-info w-52 h-52 text-info m-auto d-flex align-items-center justify-content-center fs-25 rounded-circle">
                    <i class="fad fa-users"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( number_format($profile_info->friends_count) )?></div>
                <div class="fs-20 fw-5 text-gray-500">
                    <?php _e("Following")?>
                </div>
            </div>
        </div>

        <div class="col-4 mb-4">
            <div class="border border-light rounded p-20 bg-white b-r-8 text-center">
                <div class="bg-light-dark w-52 h-52 text-dark m-auto d-flex align-items-center justify-content-center fs-30 rounded-circle">
                    <i class="fad fa-file-alt"></i>
                </div>
                <div class="fs-38 fw-9 text-gray-700"><?php _ec( number_format($profile_info->statuses_count) )?></div>
                <div class="fs-20 fw-5 text-gray-500">
                    <?php _e("Posts")?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Followers History")?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="followers_history_chart" class="h-300">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Following History")?></span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="following_history_chart" class="h-300">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Account Stats Summary")?>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Showing last 15 entries.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table_sumary">
                        <?php
                        $total_followers_summany = 0;
                        $total_following_summany = 0;
                        $total_posts_summany = 0;
                        $compare_new_followers = "";
                        $compare_new_following = "";
                        $compare_total_followers = "";
                        $compare_total_following = "";
                        ?>

                        <table class="table my-0">
                            <thead>
                                <tr>
                                    <td><?php _e("Date")?></td>
                                    <td colspan="2"><?php _e("Followers")?></td>
                                    <td colspan="2"><?php _e("Following")?></td>
                                    <td colspan="2"><?php _e("Posts")?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats->list_summary as $key => $row){
                                    $followers_status = "text-default";
                                    $followers_sumary = "-";
                                    $total_followers_summany += (int)$row->followers_sumary;
                                    if($row->followers_sumary > 0){
                                        $followers_sumary = "+".$row->followers_sumary;
                                        $followers_status = "text-success";
                                    }else if($row->followers_sumary < 0){
                                        $followers_sumary = $row->followers_sumary;
                                        $followers_status = "text-danger";
                                    }

                                    $following_status = "text-default";
                                    $following_sumary = "-";
                                    $total_following_summany += (int)$row->following_sumary;
                                    if($row->following_sumary > 0){
                                        $following_sumary = "+".$row->following_sumary;
                                        $following_status = "text-success";
                                    }else if($row->following_sumary < 0){
                                        $following_sumary = $row->following_sumary;
                                        $following_status = "text-danger";
                                    }

                                    $posts_status = "text-default";
                                    $posts_sumary = "-";
                                    $total_posts_summany += (int)$row->posts_sumary;
                                    if($row->posts_sumary > 0){
                                        $posts_sumary = "+".$row->posts_sumary;
                                        $posts_status = "text-success";
                                    }else if($row->posts_sumary < 0){
                                        $posts_sumary = $row->posts_sumary;
                                        $posts_status = "text-danger";
                                    }

                                    $compare_new_followers .= (int)$followers_sumary.",";
                                    $compare_new_following .= (int)$following_sumary.",";
                                    $compare_total_followers .= (int)$row->followers.",";
                                    $compare_total_following .= (int)$row->following.",";
                                ?>
                                <tr>
                                    <td><?php _e(date("D, d M, Y", strtotime( date_sql($row->date) )))?></td>
                                    <td><?php _e($row->followers)?></td>
                                    <td><span class="<?php _e($followers_status)?>"><?php _e($followers_sumary)?></span></td>
                                    <td><?php _e($row->following)?></td>
                                    <td><span class="<?php _e($following_status)?>"><?php _e($following_sumary)?></span></td>
                                    <td><?php _e($row->posts)?></td>
                                    <td><span class="<?php _e($posts_sumary)?>"><?php _e($posts_sumary)?></span></td>
                                </tr>
                                <?php }?>
                            </tbody>
                            <tfoot>
                                <?php 
                                $total_followers_status = "text-default";
                                if($total_followers_summany > 0){
                                    $total_followers_summany = "+".$total_followers_summany;
                                    $total_followers_status = "text-success";
                                }else if($total_followers_summany < 0){
                                    $total_followers_status = "text-danger";
                                }

                                $total_following_status = "text-default";
                                if($total_following_summany > 0){
                                    $total_following_summany = "+".$total_following_summany;
                                    $total_following_status = "text-success";
                                }else if($total_following_summany < 0){
                                    $total_following_status = "text-danger";
                                }

                                $total_posts_status = "text-default";
                                if($total_posts_summany > 0){
                                    $total_posts_summany = "+".$total_posts_summany;
                                    $total_posts_status = "text-success";
                                }else if($total_posts_summany < 0){
                                    $total_posts_status = "text-danger";
                                }
                                ?>

                                <tr>
                                    <td><i class="ft-crosshair"></i> <?php _e("Total Summary")?></td>
                                    <td colspan="2"><span class="<?php _e($total_followers_status)?>"><?php _e(($total_followers_summany!=0)?$total_followers_summany:"-")?></span></td>
                                    <td colspan="2"><span class="<?php _e($total_following_status)?>"><?php _e(($total_following_summany!=0)?$total_following_summany:"-")?></span></td>
                                    <td colspan="2"><span class="<?php _e($total_posts_status)?>"><?php _e(($total_posts_summany!=0)?$total_posts_summany:"-")?></span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Compare new Followers and Following")?></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                        $compare_new_followers = "[".substr($compare_new_followers, 0, -1)."]";
                        $compare_new_following = "[".substr($compare_new_following, 0, -1)."]";
                    ?>
                    <div id="compare_new_followers_and_following_chart" class="h-350">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Compare total Followers and Following")?></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                        $compare_total_followers = "[".substr($compare_total_followers, 0, -1)."]";
                        $compare_total_following = "[".substr($compare_total_following, 0, -1)."]";
                    ?>
                    <div id="compare_total_followers_and_following_chart" class="h-350">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Average Engagement Rate")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Each value in this chart is equal to the Average Engagement Rate of the account in that specific day.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a></span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="average_engagement_rate_chart" class="h-350">
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Future Projections")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Here you can see the approximated future projections based on your previous days averages")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                    $average_followers = $total_days>0?(int)ceil($total_followers_summany/$total_days):0;
                    $average_posts = $total_days>0?(int)ceil($total_posts_summany/$total_days):0;
                    ?>
                    <div class="table_sumary">
                        <table class="table my-0">
                            <thead>
                                <tr>
                                    <td><?php _e("Time Until")?></td>
                                    <td><?php _e("Date")?></td>
                                    <td><?php _e("Followers")?></td>
                                    <td><?php _e("Posts")?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php _e("Current Stats")?></td>
                                    <td><?php _e(date("d M, Y", strtotime(reset($stats->list_summary)->date)))?></td>
                                    <td><?php _e(number_format(reset($stats->list_summary)->followers))?></td>
                                    <td><?php _e(number_format(reset($stats->list_summary)->posts))?></td>
                                </tr>
                                <?php if($total_days > 0){ ?>
                                <tr>
                                    <td>30 <?php _e("days")?></td>
                                    <td><?php _e((new \DateTime())->modify('+30 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 30))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 30))) ?></td>
                                </tr>
                                <tr>
                                    <td>60 <?php _e("days")?></td>
                                    <td><?php _e((new \DateTime())->modify('+60 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 60))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 60))) ?></td>
                                </tr>
                                <tr>
                                    <td>3 <?php _e("months")?></td>
                                    <td><?php _e((new \DateTime())->modify('+90 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 90))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 90))) ?></td>
                                </tr>
                                <tr>
                                    <td>6 <?php _e("months")?></td>
                                    <td><?php _e((new \DateTime())->modify('+180 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 180))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 180))) ?></td>
                                </tr>
                                <tr>
                                    <td>9 <?php _e("months")?></td>
                                    <td><?php _e((new \DateTime())->modify('+279 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 279))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 279))) ?></td>
                                </tr>
                                <tr>
                                    <td>1 <?php _e("year")?></td>
                                    <td><?php _e((new \DateTime())->modify('+365 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 365))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 365))) ?></td>
                                </tr>
                                <tr>
                                    <td>1 <?php _e("year and half")?></td>
                                    <td><?php _e((new \DateTime())->modify('+547 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 547))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 547))) ?></td>
                                </tr>
                                <tr>
                                    <td>2 <?php _e("years")?></td>
                                    <td><?php _e((new \DateTime())->modify('+730 day')->format('Y-m-d')) ?></td>
                                    <td><?php _e(number_format($follower_count + ($average_followers * 730))) ?></td>
                                    <td><?php _e(number_format($media_count + ($average_posts * 730))) ?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                            <tfoot>
                                <?php if($total_days > 0){ ?>
                                <tr>

                                    <?php 
                                    $average_followers = "-";
                                    if($average_followers > 0){
                                        $average_followers = "<span class='text-success'>+".number_format($average_followers)."<span>";
                                    }else if($average_followers < 0){
                                        $average_followers = "<span class='text-danger'>".number_format($average_followers)."<span>";
                                    }

                                    $average_posts = "-";
                                    if($average_posts > 0){
                                        $average_posts = "<span class='text-success'>+".number_format($average_posts)."<span>";
                                    }else if($average_posts < 0){
                                        $average_posts = "<span class='text-danger'>".number_format($average_posts)."<span>";
                                    }
                                    ?>

                                    <td colspan="2"><i class="ft-crosshair"></i> <?php _e("Based on an average of")?></td>
                                    <td><?php _e(sprintf(__("%s followers/day"), $average_followers)) ?></td>
                                    <td><?php _e(sprintf(__("%s posts/day"), $average_posts)) ?></td>
                                </tr>
                                <?php }else{?>
                                <tr>
                                    <td colspan="4" style="font-weight: 400"><?php _e("There is not enough data to generate future projections, please come back tomorrow.")?></td>
                                </tr>
                                <?php }?>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Top Posts")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Top posts from the last 10 posts.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a></span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="owl-carousel owl-theme">
                        <?php if(!empty($feeds)){
                        foreach ($feeds as $key => $row) {
                            $row = (object)$row;
                        ?>
                        <div class="item px-2">
                            <blockquote class="twitter-tweet"><p lang="en" dir="ltr">
                                <a href="https://twitter.com/posts/status/<?php _e( $row->media_id )?>?ref_src=twsrc%5Etfw"></a>
                            </blockquote>
                        </div>
                        <?php }}?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Top mentions")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Top mentions from the last 10 posts.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_hashtags)): ?>
                        <?php 
                        $count = 1;
                        foreach ($top_mentions as $key => $value) {
                        ?>
                        <<div class="d-flex justify-content-start align-items-center mb-3">
                            <div class="border border-primary w-40 h-40 d-flex align-items-center justify-content-center b-r-100 me-3 text-primary fs-18">
                                <?php _e($count)?>
                            </div>
                            <div class="fs-18">
                                <a href="https://www.instagram.com/explore/tags/<?php _e($key)?>" target="_blank">#<?php _e($key)?></a> (<span class="text-dark"><?php _e($value)?></span>)
                            </div>
                        </div>
                        <?php $count++; }?>
                    <?php else: ?>
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title">
                        <span class="me-2"><?php _e("Top hashtags")?></span>
                        <a href="javascript:void(0);" class="text-gray-400" title="<?php _e("Top hashtags from the last 10 posts.")?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-question-circle"></i></a></span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_hashtags)): ?>

                        <?php 
                        $count = 1;
                        foreach ($top_hashtags as $key => $value) {
                        ?>
                        <div class="d-flex justify-content-start align-items-center mb-3">
                            <div class="border border-primary w-40 h-40 d-flex align-items-center justify-content-center b-r-100 me-3 text-primary fs-18">
                                <?php _e($count)?>
                            </div>
                            <div class="fs-18">
                                <a href="https://www.instagram.com/explore/tags/<?php _e($key)?>" target="_blank">#<?php _e($key)?></a> (<span class="text-dark"><?php _e($value)?></span>)
                            </div>
                        </div>
                        <?php $count++; }?>

                    <?php else: ?>
                        <?php _ec( $this->include('Core\Analytics\Views\empty'), false);?>
                    <?php endif ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

        Core.chart({
            id: 'followers_history_chart',
            categories: <?php _ec( $stats->date_chart )?>,
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
                data: <?php _ec( $stats->followers_chart )?>,
            }]
        });

        Core.chart({
            id: 'following_history_chart',
            categories: <?php _ec( $stats->date_chart )?>,
            data: [{
                name: '<?php _e("Following")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $stats->following_chart )?>,
            }]
        });

        Core.chart({
            id: 'average_engagement_rate_chart',
            categories: <?php _ec( $stats->date_chart )?>,
            data: [{
                name: '<?php _e("Average Engagement Rate")?>',
                lineColor: 'rgba(60, 88, 208, 1)',
                fillColor: {
                    linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                    stops: [
                        [0, 'rgba(60, 88, 208, 1)'],
                        [1, 'rgba(255,255,255,.5)']
                    ]
                },
                color: 'rgba(60, 88, 208, 1)',
                data: <?php _ec( $stats->engagement_chart )?>,
            }]
        });

        Core.chart({
            id: 'compare_new_followers_and_following_chart',
            categories: <?php _ec( $stats->date_chart )?>,
            legend: true,
            data: [{
                type: 'spline',
                name: '<?php _e("Followers")?>',
                lineColor: 'rgba(0, 158, 247, 1)',
                color: 'rgba(0, 158, 247, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $compare_new_followers )?>,
            },{
                type: 'spline',
                name: '<?php _e("Following")?>',
                lineColor: 'rgba(80, 205, 137, 1)',
                color: 'rgba(80, 205, 137, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $compare_new_following )?>,
            }]
        });

        Core.chart({
            id: 'compare_total_followers_and_following_chart',
            categories: <?php _ec( $stats->date_chart )?>,
            legend: true,
            data: [{
                type: 'spline',
                name: '<?php _e("Followers")?>',
                lineColor: 'rgba(0, 158, 247, 1)',
                color: 'rgba(0, 158, 247, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $compare_total_followers )?>,
            },{
                type: 'spline',
                name: '<?php _e("Following")?>',
                lineColor: 'rgba(80, 205, 137, 1)',
                color: 'rgba(80, 205, 137, 1)',
                marker: {
                    enabled: false
                },
                data: <?php _ec( $compare_total_following )?>,
            }]
        });

        $(".owl-carousel").owlCarousel({
            nav: true,
            responsiveClass:true,
            responsive:{
                0:{
                    items:1
                },

                600:{
                    items:1
                },

                1024:{
                    items:3
                }
            }       
        });
        
        if ( typeof window.instgrm !== 'undefined' ) {
            window.instgrm.Embeds.process();
        }
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

