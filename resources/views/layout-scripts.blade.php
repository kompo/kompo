<script class="reloadable-script">
    	
    	@if($_kompo->has('lsidebar'))
    		var vlSidebarL = $('.vl-sidebar-l').eq(0)
			var vlSidebarLMobile = $('#vl-sidebar-l-mobile')
    	@endif

    	@if($_kompo->has('rsidebar'))
			var vlSidebarR = $('.vl-sidebar-r').eq(0)
			var vlSidebarRMobile = $('#vl-sidebar-r-mobile')
		@endif


    vlSetMobile()

	$(document).ready(function(){

		vlOnLoadScrollResize()
        window.addEventListener('resize', vlOnLoadScrollResize)
      	
    })

    function vlSetMobile()
    {
    	window.vlMobile = getComputedStyle(document.getElementById('vl-md')).display === 'block'
    }

    function vlOnLoadScrollResize()
    {
    	vlSetMobile()
    	@if($_kompo->hasAnySidebar())
	    	copySidebarsToNav()
    	@endif
    }

    @if($_kompo->hasAnySidebar())
	    function copySidebarsToNav()
	    {
	    	@if($_kompo->has('lsidebar') && $_kompo->collapse('lsidebar'))
	    		vlMobile ? vlSwitchSidebars(vlSidebarL, vlSidebarLMobile) : 
	    				   vlSwitchSidebars(vlSidebarLMobile, vlSidebarL)
    		@endif
    		@if($_kompo->has('rsidebar') && $_kompo->collapse('rsidebar'))
    			vlMobile ? vlSwitchSidebars(vlSidebarR, vlSidebarRMobile) : 
    					   vlSwitchSidebars(vlSidebarRMobile, vlSidebarR)
    		@endif
	    }

	@endif

    function height(element)
    {
    	return element.outerHeight() || 0
    }

    function width(element)
    {
    	return element.outerWidth() || 0
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

	function vlSwitchSidebars(oldSb, newSb)
	{
		if(oldSb.html()){
			newSb.html(oldSb.html())
			oldSb.html('')
		}
	}
</script>