<template>
    <div v-bind="catalogAttributes" class="vlCatalog">

        <div class="vlFlex">
            <vl-filters v-bind="filtersAttributes('Left')" />

            <div class="vlCatalogWrapper">
                <vl-filters v-bind="filtersAttributes('Top')" />
                <div class="vlCatalogInner">
                    <component v-if="topPagination" @browse="browseCatalog" 
                        v-bind="paginationAttributes" />

                    <table v-if="isTableLayout" class="w-full table vlTable">
                        <vl-table-headers :columns="columns" :kompoid="$_kompoId" />
                        <component v-bind="layoutAttributes" />
                    </table>

                    <component v-else v-bind="layoutAttributes" />

                    <component v-if="bottomPagination" @browse="browseCatalog" 
                        v-bind="paginationAttributes" />
                </div>
                <vl-filters v-bind="filtersAttributes('Bottom')" />
            </div>

            <vl-filters v-bind="filtersAttributes('Right')" />
        </div>

        <vl-support-modal 
            :kompoid="$_kompoId" 
            @refresh="refreshItem"
            @previous="previewPrevious"
            @next="previewNext" />

    </div>
</template>

<script>
import Element from '../element/mixins/Element'

export default {
    mixins: [Element],
    props: {
        kompoid: { type: String, required: false }
    },
    data: () => ({
        currentPage: 1,
        currentSort: '',
        filters: [],
        cards: [],
        pagination: null,
        columns: [],
        cardsKey: '',
        previewIndex: null
    }),
    created() {
        this.cardsKey = 'cards' + this.vcomponent.id
        this.filters = this.vcomponent.filters
        this.cards = this.getCards(this.component)
        this.pagination = this.getPagination(this.component)
        this.columns = this.component.columns
        this.destroyEvents()
        this.attachEvents()
    },
    updated() {
        this.destroyEvents()
        this.attachEvents()
    },
    computed: {
        filtersPlacement(){ return [ 'top', 'left', 'bottom', 'right' ] },
        catalogAttributes(){
            return {
                ...this.$_defaultElementAttributes,
                class: this.$_phpClasses,
                style: this.$_elementStyles
            }
        },
        layoutAttributes(){
            return {
                is: this.layoutComponent,
                vcomponent: this.component,
                kompoid: this.$_kompoId,
                cards: this.cards,
                columns: this.columns,
                noItemsFound: this.noItemsFound,
                key: this.cardsKey
            }
        },
        paginationAttributes(){
            return {
                is: this.paginationStyle,
                pagination: this.pagination,
                class: this.paginationClass
            }
        },
        catalogUrl(){ return this.component.browseUrl },
        hasPagination() { return this.component.hasPagination },
        topPagination(){ return this.hasPagination && this.component.topPagination },
        bottomPagination(){ return this.hasPagination && this.component.bottomPagination },
        leftPagination(){ return this.hasPagination && this.component.leftPagination },
        paginationStyle() { return 'VlPagination' + this.component.paginationStyle },
        paginationClass(){ return this.$_classString(
            [this.leftPagination ? '' : 'vlJustifyEnd']
            .concat([this.topPagination ? 'vlPaginationT' : ''])
            .concat([this.bottomPagination ? 'vlPaginationB' : ''])
        ) },
        layoutComponent(){ return this.hasItems ? 'vl-' + this.component.layout : this.noItemsComponent },
        isTableLayout(){ return this.component.layout.indexOf('Table') > -1 },
        hasItems(){ return this.cards.length > 0 },
        noItemsFound(){ return this.component.noItemsFound },
        noItemsComponent(){ return this.isTableLayout ? 'vl-table-no-items' : 'vl-no-items' },
    },
    methods: {
        getCards(catalog){ return this.getPagination(catalog).data },
        getPagination(catalog){ return catalog.query },
        filtersAttributes(placement){
            return {
                filters: this.filters[placement.toLowerCase()],
                placement: placement,
                kompoid: this.$_kompoId
            }
        },
        getJsonFormData(jsonFormData){
            this.$_fillRecursive(jsonFormData)
            return jsonFormData
        },
        preparedFormData(){
            var formData = new FormData(), jsonFormData = this.getJsonFormData({})
            for ( var key in jsonFormData ) {
                formData.append(key, jsonFormData[key])
            }
            return formData
        },
        refreshItem(index){
            this.browseCatalog() //temporary need to make back-end
        },
        preview(index){
            this.previewIndex = index
            this.cards[this.previewIndex].$_previewModal(this.cards.length > 1)
        },
        previewPrevious(){
            this.preview(this.previewIndex == 0 ? this.cards.length - 1 : this.previewIndex - 1)
        },
        previewNext(){
            this.preview(this.previewIndex == this.cards.length - 1 ? 0 : this.previewIndex + 1)
        },
        browseCatalog(page) {
            this.currentPage = page || this.currentPage
            axios({
                url: this.catalogUrl, 
                method: 'POST',
                data: this.preparedFormData(),
                headers: {
                    'X-Kompo-Id': this.$_elKompoId,
                    'X-Kompo-Page': this.currentPage,
                    'X-Kompo-Sort': this.currentSort
                }
            }).then(r => {
                this.$_state({ loading: false })
                this.pagination = this.getPagination(r.data)
                Vue.set(this, 'cards', this.getCards(r.data))
                this.cardsKey += 1 //to re-render cards
            })
            .catch(e => {
                if (e.response.status == 422){
                    this.$_validate(e.response.data.errors)
                }else{
                    this.$modal.showFill('modal'+this.$_elKompoId, 
                        '<div>Error '+e.response.status+' | '+e.response.data.message+'</div>')
                }

                this.$_state({ loading: false })
            })
        },
        attachEvents(){
            this.$_vlOn('vlEmit'+this.$_elKompoId, (eventName, eventPayload) => {
                this.$emit(eventName, eventPayload)
                if(this.kompoid)
                    this.$_vlEmitFrom(eventName, eventPayload)
            })
            this.$_vlOn('vlRefreshCatalog'+this.$_elKompoId, (page) => {
                this.currentPage = page ? page : this.currentPage
                this.browseCatalog()
            })
            this.$_vlOn('vlSort'+this.$_elKompoId, (sortValue, emitterId) => {
                this.currentSort = sortValue == this.currentSort ? '' : sortValue
                this.currentPage = 1
                this.$_resetSort(emitterId)
                this.browseCatalog()
            })
            this.$_vlOn('vlToggle'+this.$_elKompoId, (toggleId) => {
                this.$_toggle(toggleId)
            })
            this.$_vlOn('vlPreview'+this.$_elKompoId, (index) => {
                this.preview(index)
            })
        },
        destroyEvents(){
            this.$_vlOff([
                'vlEmit'+this.$_elKompoId,
                'vlRefreshCatalog'+this.$_elKompoId,
                'vlSort'+this.$_elKompoId,
                'vlToggle'+this.$_elKompoId,
                'vlPreview'+this.$_elKompoId
            ])
        },
        $_fillRecursive(jsonFormData){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_fillRecursive(jsonFormData) )
            )
        },
        $_resetSort(emitterId){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_resetSort(emitterId) )
            )
            this.columns.forEach( item => item.$_resetSort(emitterId) )
        },
        $_state(state){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_state(state) )
            )
        },
        $_toggle(toggleId){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_toggle(toggleId) )
            )
        },
        $_validate(errors) {
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_validate(errors) )
            )
        },

    }
}

</script>
