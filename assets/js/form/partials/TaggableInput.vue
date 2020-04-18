<template>
    <div class="vlTaggableInput" ref="content">

        <div v-if="$slots.prepend" class="vlInputPrepend" @click.stop="$emit('click')">
            <slot name="prepend" />
        </div>

        <div class="vlTaggableContent" :style="{width: contentWidth}">
            <div v-if="selections.length"
                :class="{vlTags: multiple, vlSingle: !multiple}" 
                style="width: 100%"
                @click.stop="$emit('click')">
                <div v-for="(selection, index) in selections" 
                    :key="index"
                    class="vlCustomLabel">
                    <i v-if="!readonly"
                        class="icon-times" 
                        @click.stop="$emit('remove', index)" />
                    <vlCustomLabel 
                        :vkompo="selection[labelKey]" 
                        :kompoid="kompoid"
                        @click.stop="$emit('click', selection)"/>
                </div>
            </div>

            <slot />
        </div>

        <div v-if="$slots.append" class="vlInputAppend" @click.stop="$emit('click')">
            <slot name="append" />
        </div>

    </div>
</template>

<script>
export default {
    props: {
        selections: {type: Array, required: true},
        kompoid: { type: String, required: true },
        multiple: {type: Boolean, default: false },
        labelKey: {type: String, default: 'label'},
        readonly: {type: Boolean, default: false } 
    },
    data(){
        return {
            contentWidth: 0
        }
    },
    mounted(){
        this.setContentWidth()
        $(window).resize(this.setContentWidth)
    },
    beforeDestroy() {
        $(window).unbind('resize', this.setContentWidth)
        this.$kompo.events.$off('vlTabChange')
    },
    created() {
        this.$kompo.events.$on('vlTabChange', () => {
            this.$nextTick(()=> { this.setContentWidth() })
        })
    },
    methods: {
        setContentWidth(){
            if(!this.$refs.content)
                return
            this.contentWidth = 'auto' //necessary cuz content depends in the width of it's contents...
            this.$nextTick(()=> {this.contentWidth = parseInt(this.$refs.content.clientWidth - 32) + 'px'})
        }
    }
}
</script>