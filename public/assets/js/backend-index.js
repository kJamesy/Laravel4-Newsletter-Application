jQuery(document).ready(function($) {
	$('.percentage').easyPieChart({
  		animate: 1000,
  		lineWidth: 4,
      // barColor: '#5CB85C',
  		onStep: function(value) {
    		this.$el.find('span').text(Math.round(value));
		},
  		onStop: function(value, to) {
    		this.$el.find('span').text(Math.round(to));
  		}
	});
});