<template>
    <a  :class="linkClass"
    	:href="vcomponent.href || 'javascript:void(0)'" 
    	:target="vcomponent.target"
        v-turbo-click="vcomponent.turbo"
    	@click.stop="click" >
        <slot v-show="!$_loading" />
        <vl-spinner-icon :loading="$_loading" />
    </a>
</template>

<script>
import VueClickable from '../mixins/VueClickable'
export default {
    mixins: [VueClickable],
    computed: {
    	linkClass(){
    		return (this.$_data('btnStyle') ? 'vlBtn' : 'vlLink') + 
    			(this.$_data('btnOutlined') ? ' vlBtnOutlined' : '') +
    			(this.$_data('btnPlain') ? ' vlBtnPlain' : '') +
                (this.$_data('secondary') ? ' vlSecondary' : '')+
                (this.$_data('btnInline') ? ' vlBtnInline' : '') +
    			(this.$_data('btnBlock') ? ' vlBtnBlock' : '') //inline by default
    			
    	}
    }
}
</script>
