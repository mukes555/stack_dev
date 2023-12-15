"use strict";
function Rss_post(){
    var self = this;
    var RSS_SCHEDULE_SIDEBAR = $(".sub-sidebar");
    var RSS_SCHEDULE_MAIN = $(".rss-schedules-main");
    var RSS_SCHEDULE_LIST = $(".schedule-list");
    var RSS_SCHEDULE_CALENDAR = $("#schedule-calendar");
    var RSS_SCHEDULE_PATH = $("[name='schedule_action']").val();
    var RSS_SCHEDULE_GET = $("[name='schedule_get']").val();

    this.init = function(){
        self.action();
        self.schedules();
    };

    this.action = function(){
        $(document).on("click", ".btnCloseCaption", function(){
            var that = $(this).parents(".popup-caption").remove();
            return false;
        });

        $(document).on("change", ".btnActiveRss", function(){
            var that = $(this);
            var id = that.parents(".rss-item").data("id"); 
            Core.overplay();
            $.post( PATH+"rss_post/do_active_rss/"+id ,{ csrf  : csrf, id: id }, function(result){
                Core.notify(result.message, result.status);
                Core.overplay("hide");
            });
            return false;
        });

    };

    this.schedules = function(){

        if( RSS_SCHEDULE_MAIN.length > 0 ){
            var type = RSS_SCHEDULE_SIDEBAR.find('[name="schedule_type"]:checked').val();
            var category = RSS_SCHEDULE_SIDEBAR.find("input[name='schedule_of']:checked").val();
            var time = RSS_SCHEDULE_SIDEBAR.find('[name="schedule_time"]').val();
            var d =new Date(time);

            RSS_SCHEDULE_CALENDAR.monthly({
                mode: 'event',
                dataType: 'json',
                jsonUrl: RSS_SCHEDULE_GET+'/'+type+'/'+category,
                eventList: false,
                setDate: d.getTime()/1000
            });
            
            RSS_SCHEDULE_MAIN.find(".monthly-day[data-time='"+time+"']").addClass("active");

            RSS_SCHEDULE_MAIN.on("click", ".monthly-day", function(){
                var that = RSS_SCHEDULE_CALENDAR;
                var time = $(this).data('time');
                var type = RSS_SCHEDULE_SIDEBAR.find('[name="schedule_type"]:checked').val();
                var category = RSS_SCHEDULE_SIDEBAR.find("input[name='schedule_of']:checked").val();
                var params = { token: csrf };
                var action = RSS_SCHEDULE_PATH + "/" + type + "/" + category + "/" + time;

                RSS_SCHEDULE_MAIN.find(".monthly-day").removeClass("active");
                $(this).addClass("active");
                Core.ajax_post( that, action, params, function(result){
                    RSS_SCHEDULE_LIST.html(result);
                    Core.overplay("hide");
                    history.pushState(null, '', action);
                    RSS_SCHEDULE_SIDEBAR.find('[name="schedule_time"]').val(time);
                    Layout.carousel();
                });
            });

           RSS_SCHEDULE_SIDEBAR.find(".schedule-type").on("click", function(){
                var time = RSS_SCHEDULE_SIDEBAR.find('[name="schedule_time"]').val();
                var url = $(this).attr("href") + "/" + time;
                location.assign( url );
                return false;
            });

            RSS_SCHEDULE_SIDEBAR.find("input[name='schedule_of']").on("change", function(){
                var type = RSS_SCHEDULE_SIDEBAR.find('[name="schedule_type"]:checked').val();
                var time = RSS_SCHEDULE_SIDEBAR.find('[name="schedule_time"]').val();
                var category = $(this).val();
                var url = RSS_SCHEDULE_PATH + "/" + type + "/" + category + "/" + time;
                location.assign( url );
                Core.overplay();
            });
        }
    }

    this.closeRssModal = function(){
        $('#addRssModal').modal('hide');
    };
}

var Rss_post = new Rss_post();
$(function(){
    Rss_post.init();
});