<template>
    <vl-modal 
        :name="modalName" 
        v-bind="modalProps"
        @previous="$emit('previous')" 
        @next="$emit('next')">

        <component 
            v-if="modalComponentProps"
            v-bind="modalComponentProps"
            @closeModal="closeModal"
            @openModal="openModal"
            @refresh="refresh"
            @previous="$emit('previous')" 
            @next="$emit('next')" />

    </vl-modal>
</template>

<script>

export default {
    props: {
        kompoid: { type: String, required: true }
    },
    data: () => ({
        modalComponentProps: null,
        modalProps: {}
    }),
    created() {
        this.destroyEvents()
        this.attachEvents()
    },
    updated() {
        this.destroyEvents()
        this.attachEvents()
    },
    computed: {
        modalName(){ return 'modal'+this.kompoid}
    },
    methods: {
        openModal(){ this.$modal.show(this.modalName) },
        closeModal(){ this.$modal.close(this.modalName) },
        refresh(index){this.$emit('refresh', index)},
        attachEvents(){
            this.$modal.events.$on('insertModal'+this.kompoid, (componentProps, modalProps) => {
                this.modalComponentProps = componentProps
                this.modalProps = modalProps
                this.$modal.show(this.modalName)
            })
        },
        destroyEvents(){
            this.$modal.events.$off(['insertModal'+this.kompoid])
        }

    }
}

</script>
