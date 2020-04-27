<template>
    <div class="vlFlex vlAlignCenter">
        <div>
            <span v-html="pagination.total + ' results'" />
            <span v-html="'(showing '+from+' to '+to+') &nbsp;'" />
        </div>
        <ul v-if="needsPaginationLinks" class="pagination" role="navigation">
            <li v-if="isFirstPage" class="page-item disabled" aria-disabled="true" aria-label="previous">
                <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
            <li v-else class="page-item">
                <a class="page-link" href="javascript:void(0)" @click.stop="loadPrevPage" rel="prev" aria-label="previous">&lsaquo;</a>
            </li>

            <li v-if="isLastPage" class="page-item disabled" aria-disabled="true" aria-label="next">
                <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
            <li v-else class="page-item">
                <a class="page-link" href="javascript:void(0)" @click.stop="loadNextPage" rel="next" aria-label="next">&rsaquo;</a>
            </li>
        </ul>
    </div>
</template>

<script>
import Pagination from './mixins/Pagination'

export default {
    mixins: [Pagination]
}
</script>
