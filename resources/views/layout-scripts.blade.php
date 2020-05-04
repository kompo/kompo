<script class="reloadable-script">

    var vlMobileIndicator = $('#vl-mobile-indicator'),
    	
    	@if($Navbar)
    		vlNav = $('.vl-nav').eq(0),
    	@endif

    	vlFooter = $('.vl-footer').eq(0),
    	
    	@if($LeftSidebar)
    		vlSidebarL = $('.{{ $LeftSidebar->data('menuType') }}').eq(0),
			vlSidebarLContainer = $('#{{ $LeftSidebar->data('menuType') }}-container'),
			vlSidebarLPanel = $('#{{ $LeftSidebar->data('menuType') }}-panel'),
			vlSidebarLMobile = $('#{{ $LeftSidebar->data('menuType') }}-mobile'),
    	@endif

    	@if($RightSidebar)
			vlSidebarR = $('.{{ $RightSidebar->data('menuType') }}').eq(0),
			vlSidebarRContainer = $('#{{ $RightSidebar->data('menuType') }}-container'),
			vlSidebarRPanel = $('#{{ $RightSidebar->data('menuType') }}-panel'),
			vlSidebarRMobile = $('#{{ $RightSidebar->data('menuType') }}-mobile'),
		@endif
    	
    	vlContent = $('#vl-main'),
    	vlWrapper = $('#vl-wrapper')


    vlSetMobile()

	$(document).ready(function(){

		vlOnLoadScrollResize()
		//$(window).scroll(function () { vlOnLoadScrollResize() }) //why did we need it? to remove
		$(window).resize(function () { vlOnLoadScrollResize() })
      	
    })

    function vlSetMobile()
    {
    	window.vlMobile = vlMobileIndicator.css('display') === 'block'
    }

    function vlOnLoadScrollResize()
    {
    	vlSetMobile()
    	@if($VlHasAnySidebar)
	    	copySidebarsToNav()
	    	fixSidebars()
    		highlightHashLinkOnScroll()
    	@endif
    	@if($Navbar)
    		fixNavbar()
    	@endif
    	//updateSidebarsHeight()
    }

    @if($VlHasAnySidebar)
	    function copySidebarsToNav()
	    {
	    	@if($LeftSidebar)
	    		vlMobile ? vlSwitchSidebars(vlSidebarL, vlSidebarLMobile) : 
	    				   vlSwitchSidebars(vlSidebarLMobile, vlSidebarL)
    		@endif
    		@if($RightSidebar)
    			vlMobile ? vlSwitchSidebars(vlSidebarR, vlSidebarRMobile) : 
    					   vlSwitchSidebars(vlSidebarRMobile, vlSidebarR)
    		@endif
	    }

	    function fixSidebars()
	    {
	    	@if($LeftSidebar) 

				@if($LeftSidebar->fixed)

					var lst = {{ $LeftSidebar->top ? 'true' : 'false' }}
					vlFixedSidebar(vlSidebarLContainer, lst)
					vlFixedSidebar(vlSidebarLPanel, lst)

				@else

					@if(!$LeftSidebar->top && optional($Navbar)->fixed )
						vlSidebarLContainer.css('margin-top', height(vlNav))
						vlSidebarLPanel.css('margin-top', height(vlNav))
					@endif
				
				@endif

			@endif

	    	@if($RightSidebar) 

				@if($RightSidebar->fixed)

					var rst = {{ $RightSidebar->top ? 'true' : 'false' }}
					vlFixedSidebar(vlSidebarRContainer, rst, true)
					vlFixedSidebar(vlSidebarRPanel, rst, true)

				@else

					@if(!$RightSidebar->top && optional($Navbar)->fixed)
						vlSidebarRContainer.css('margin-top', height(vlNav))
						vlSidebarRPanel.css('margin-top', height(vlNav))
					@endif
				
				@endif

			@endif
	    }

	    function fixWrapperOnDesktop()
	    {
	    	if(!vlMobile){
	    		
	    		var wrapperWidth = 'calc(100vw'

				@if(optional($LeftSidebar)->fixed)
					vlWrapper.css('margin-left', width(vlSidebarL))
					wrapperWidth += ' - '+width(vlSidebarL)+'px'
				@endif

				@if(optional($RightSidebar)->fixed)
					vlWrapper.css('margin-right', width(vlSidebarR))
					wrapperWidth += ' - '+width(vlSidebarR)+'px'
				@endif

				wrapperWidth += ')'
				vlWrapper.css('width', wrapperWidth)

	    	}else{
	    		vlWrapper.css('margin-left', 0).css('margin-right', 0)
	    	}
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




	    //not used, would be used if footer overlaps
	    function updateSidebarsHeight() {
	    	if($('footer').length){
				var h = window.innerHeight
				var footerTop = $('footer').offset().top
				var scrollTop = $(window).scrollTop()
				if (scrollTop + 2*h > footerTop) {
					vlSidebarL.css('height', h - height(vlNav) + Math.min(footerTop - scrollTop - h,0))
					vlSidebarR.css('height', h - height(vlNav) + Math.min(footerTop - scrollTop - h,0))
				}
			}
	  	}

	@endif

    function fixNavbar()
    {
		var verticalSpace = window.innerHeight - height(vlNav) - height(vlFooter)
		if(height(vlContent) <= verticalSpace)
			vlContent.css('min-height', verticalSpace)

    	@if( optional($Navbar)->fixed)

			vlNav.addClass('vlFixed')
				 //.css('z-index', 1) //because rotate of collapse icon gives it a higher z-index :/
				 // should not add CSS like this... it overwrites user's CSS
			vlContent.css('margin-top', height(vlNav))

	    	if(!vlMobile){
				@if(optional($LeftSidebar)->top || optional($RightSidebar)->top)
					vlNav.css('width', 'auto') //to remove width: 100%
				@endif
				@if(optional($LeftSidebar)->top)
					vlNav.css('left', width(vlSidebarLContainer)).css('right', 0)
				@endif
				@if(optional($RightSidebar)->top)
					vlNav.css('right', width(vlSidebarRContainer))
				@endif
	    	}else{
	    		vlNav.css('left', 0).css('right', 0).css('width', '100%')
	    	}

		@endif
    }

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
    		
    		if(fixHeight){
    			target.css('max-height', 'calc(100vh - '+height(vlNav)+'px)')
    			$('body').css('overflow', 'hidden') //no double scrolling, better navigation
    		}

    		$(toggler).removeClass('vl-toggler-closed').attr("aria-expanded","true")
    		target.slideDown().css('display','flex').removeClass('vl-menu-closed')
    	}else{

    		if(fixHeight)
    			$('body').css('overflow', 'visible')


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

	function vlFixedSidebar(container, isOnTop, isRight)
	{
		container
			.addClass('vlFixedLg')
			.css('bottom', 0)
			.css('top', isOnTop ? 0 : height(vlNav))

		if(isRight)
			container.css('right', 0)

		fixWrapperOnDesktop()
	}
</script>