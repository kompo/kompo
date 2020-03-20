<template>
    <section 
        v-show="shown"
        :aria-hidden="!shown"
        class="vlTab"
        :id="computedId"
        role="tabpanel">
        <slot />
    </section>
</template>

<script>
    export default {
        props: {
            id: { default: null },
            name: { required: true },
            prefix: { default: '' },
            suffix: { default: '' },
            isDisabled:{ default: false },
            active: { default: false}
        },
        data: () => ({
            isActive: false
        }),
        computed: {
            shown() {
                return this.active || this.isActive
            },
            header() {
                return this.prefix + this.name + this.suffix;
            },
            computedId() {
                return this.id ? this.id : this.name.toLowerCase().replace(/ /g, '-');
            }
        },
    };
</script>