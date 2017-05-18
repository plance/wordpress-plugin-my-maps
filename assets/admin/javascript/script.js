jQuery(document).ready(function () {
	jQuery('#form-msm').keydown(function(event)
	{
		if(event.keyCode === 13)
		{
			event.preventDefault();
			return false;
		}
	});
	
	new google.maps.places.Autocomplete(document.getElementById('msm_address'));
	
	var myMap;
	ymaps.ready(function(){
		myMap = new ymaps.Map('msm', {
			center: [55.76, 37.64],
			zoom: 5,
			controls: ['zoomControl', 'geolocationControl'],
			type: 'yandex#map'
		});
		
		buildRoute();
	});

	jQuery('#msm_address').blur(function() {
		if(jQuery('#msm_address').val() !== '')
		{
			buildRoute();
		}
	});

	function buildRoute() {
		myMap.geoObjects.removeAll();
		ymaps.geocode(jQuery('#msm_address').val()).then(function(res)
		{
			if(res.geoObjects.getLength())
			{
				myMap.setZoom(10);
				myMap.panTo(res.geoObjects.get(0).geometry.getCoordinates());

				var myPlacemark = new  ymaps.Placemark(res.geoObjects.get(0).geometry.getCoordinates());
				myMap.geoObjects.add(myPlacemark);
			}
		});
	}
});