<script class="reloadable-script">

    vlSetMobile()

	$(document).ready(function(){

        window.addEventListener('resize', vlSetMobile)
      	
    })

    function vlSetMobile()
    {
    	window.vlMobile = getComputedStyle(document.getElementById('vl-md')).display === 'block'
    }

	function toggleMenu(toggler, fixHeight) {
		var target = $(toggler).next()
    	if(target.hasClass('vl-menu-closed')){

    		$(toggler).removeClass('vl-toggler-closed').attr("aria-expanded","true")
    		target.slideDown().removeClass('vl-menu-closed')
    	}else{

    		$(toggler).addClass('vl-toggler-closed').attr("aria-expanded","false")
    		target.slideUp().addClass('vl-menu-closed')
    	}
	}
</script>