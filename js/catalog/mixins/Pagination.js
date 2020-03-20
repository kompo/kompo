export default {
	props: {
        pagination: {type: Object, required: true}
    },
    data(){ 
        return {
            loadingPage: null
        }
    },
    methods:{
        loadPage(page){
            this.loadingPage = page
            this.$emit('browse', page)
        },
        loadPrevPage(){
            this.loadPage(this.currentPage - 1)
        },
        loadNextPage(){
            this.loadPage(this.currentPage + 1)
        },
        visiblePagination(page){
            return this.lastPage < 11 ? true : 
                (Math.abs(page - this.currentPage) < 5 ? true : 
                    (Math.abs(this.lastPage - page) < 3 ? true : 
                        (Math.abs(page - 1) < 3 ? true : false) ) )
        },
        pageLabel(page){
            return this.loadingPage == page ? '<i class="icon-spinner spin"></i>' : page
        }

    },
    computed:{
        from(){ return this.pagination.from },
        to(){ return this.pagination.to },
        total(){ return this.pagination.total },
        pages(){
            this.loadingPage = null //removes loading when pages is recalculated
            return Array.from(Array(this.pagination.last_page).keys()).map(x => ++x)
        },
        currentPage(){
            return this.pagination.current_page
        },
        isFirstPage(){
            return this.currentPage == 1
        },
        lastPage(){
            return this.pagination.last_page
        },
        isLastPage(){
            return this.currentPage == this.lastPage
        },
        needsPaginationLinks(){
            return this.total > this.pagination.per_page
        },
        dotsPage(){
            return _.find(this.pages, (page) => {
                return !this.visiblePagination(page)
            })
        }
    }


}