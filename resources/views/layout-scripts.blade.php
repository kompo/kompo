<script class="reloadable-script">

    var vlMobileIndicator = $('#vl-mobile-indicator')
    	
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
		$(window).resize(function () { vlOnLoadScrollResize() })
      	
    })

    function vlSetMobile()
    {
    	window.vlMobile = vlMobileIndicator.css('display') === 'block'
    }

    function vlOnLoadScrollResize()
    {
    	vlSetMobile()
    	@if($_kompo->hasAnySidebar())
	    	copySidebarsToNav()
    		highlightHashLinkOnScroll()
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

	    function highlightHashLinkOnScroll(){
	    	var scrollTop = $(window).scrollTop(),
	    		activeLink,
	    		activeHash

	    	$($('.vl-nav-item.vlActive .vl-has-hash').get().reverse()).each(function(){
	    		var linkHref = $(this).attr('href')
	    		var hashLink = linkHref.substring(linkHref.indexOf('#'))
	    		if($(hashLink).length && $(hashLink).offset().top - 100 < $(window).scrollTop()){
	    			$('.vl-nav-item.vlActive .vl-has-hash').css('font-weight', 'normal')
	    			$(this).css('font-weight', 'bold')
	    			return false //breaking out
	    		}
	    	})

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
    		
    		/*
    		if(fixHeight){
    			target.css('max-height', 'calc(100vh - '+height(vlNav)+'px)')
    			$('body').css('overflow', 'hidden') //no double scrolling, better navigation
    		}*/

    		$(toggler).removeClass('vl-toggler-closed').attr("aria-expanded","true")
    		target.slideDown().css('display','flex').removeClass('vl-menu-closed')
    	}else{

    		/*
    		if(fixHeight)
    			$('body').css('overflow', 'visible')
    		*/


    		$(toggler).addClass('vl-toggler-closed').attr("aria-expanded","false")
    		target.slideUp().css('display','none').addClass('vl-menu-closed')
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