<template>
    <ul v-if="needsPaginationLinks" class="pagination" role="navigation">
        <li v-if="isFirstPage" class="page-item disabled" aria-disabled="true" aria-label="previous">
            <span class="page-link" aria-hidden="true">&lsaquo;</span>
        </li>
        <li v-else class="page-item">
            <a class="page-link" href="javascript:void(0)" @click.stop="loadPrevPage" rel="prev" aria-label="previous">&lsaquo;</a>
        </li>

        <template v-for="page in pages">
            <li v-if="currentPage == page && visiblePagination(page) " 
                class="page-item active" aria-current="page">
                <span class="page-link" v-html="pageLabel(page)" />
            </li>
            <li v-if="currentPage != page && visiblePagination(page) " 
                class="page-item">
                <a class="page-link" href="javascript:void(0)" 
                    @click.stop="loadPage(page)" v-html="pageLabel(page)" />
            </li>
            <li v-if="!visiblePagination(page) && (page == dotsPage)" 
                class="page-item">
                <a class="page-link" href="javascript:void(0)" @click.stop="loadPage(page)">...</a>
            </li>
        </template>

        <li v-if="isLastPage" class="page-item disabled" aria-disabled="true" aria-label="next">
            <span class="page-link" aria-hidden="true">&rsaquo;</span>
        </li>
        <li v-else class="page-item">
            <a class="page-link" href="javascript:void(0)" @click.stop="loadNextPage" rel="next" aria-label="next">&rsaquo;</a>
        </li>
    </ul>
</template>

<script>
import Pagination from './mixins/Pagination'

export default {
    mixins: [Pagination]
}
</script>
