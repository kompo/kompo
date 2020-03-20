<template>
    <div class="vlTabs">
        <ul role="tablist" class="vlFlex">
            <li
                v-for="(tab, i) in tabs"
                :key="i"
                :class="{ 'vlActive': tab.isActive, 'vlDisabled': tab.isDisabled }"
                role="presentation"
            >
                <a v-html="tab.header"
                   :aria-selected="tab.isActive"
                   @click.stop="selectTab(i)"
                   href="javascript:void(0)"
                   class="vlTabLink"
                   role="tab"
                ></a>
            </li>
        </ul>
        <div class="vlTabContent">
            <slot/>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            activeTab: { type: Number, default: 0}
        },
        watch: { 
            activeTab: function(newVal, oldVal) { 
               this.selectTab(newVal)
            }
        },
        data: () => ({
            tabs: []
        }),
        created() {
            this.tabs = this.$children
        },
        mounted() {
            if (this.tabs.length)
                this.selectTab(0)
        },
        methods: {
            selectTab(index) {
                const selectedTab = this.tabs[index] || this.tabs[this.activeTab]
                if (!selectedTab  || selectedTab.isDisabled)
                    return

                this.tabs.forEach( (tab) => {
                    tab.isActive = (selectedTab == tab)
                })

                this.$vuravel.events.$emit('vlTabChange', index) //mainly for select resizing
                this.$emit('vlTabChange', index)
            }
        },
    };
</script>