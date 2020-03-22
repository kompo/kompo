<template>
    <button 
    	type="button"
    	:class="btnClass"
    	@click.stop="click">
        <slot />
        <vl-spinner-icon :loading="$_loading" />
    </button>
</template>


<script>
import VueClickable from '../mixins/VueClickable'
export default {
    mixins: [VueClickable],
    computed: {
    	btnClass(){
    		return 'vlBtn' + 
    			(this.$_data('btnOutlined') ? ' vlBtnOutlined' : '') +
    			(this.$_data('btnPlain') ? ' vlBtnPlain' : '') +
    			(this.$_data('btnInline') ? ' vlBtnInline' : '') +
                (this.$_data('btnBlock') ? ' vlBtnBlock' : '') +
                (this.$_data('secondary') ? ' vlSecondary' : '')
    	}
    }

}
</script>
