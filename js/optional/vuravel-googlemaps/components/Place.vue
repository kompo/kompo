<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <vlTaggableInput 
            v-bind="$_taggableInputAttributes" 
            v-on="$_taggableInputEvents"
            :labelKey="labelKey">
            <GmapAutocomplete
                class="vlFormControl"
                v-bind="$_attributes"
                v-on="$_events"
                placeholder="" 
                ref="input" />
            <template v-slot:append>
                <i class="icon-location"/>
            </template>
        </vlTaggableInput>

        <GmapMap
            :center="center"
            :zoom="zoom"
            map-type-id="terrain"
            style="height: 200px"
            @zoom_changed="setZoom"
        >
            <GmapMarker
                v-for="(marker, index) in markers"
                :key="index"
                :position="marker"
                :clickable="true"
                :draggable="true"
                @click.stop=" center = marker"
            />
        </GmapMap>
    </vl-form-field>

</template>

<script>
import Field from 'vuravel-form/js/mixins/Field'
import HasTaggableInput from 'vuravel-form/js/mixins/HasTaggableInput'
import SetInitialValueAsArray from 'vuravel-form/js/mixins/SetInitialValueAsArray'
import draggable from 'vuedraggable'

import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.MIX_GOOGLE_MAPS_API_KEY,
    libraries: 'places'
  }
})

export default {
    mixins: [Field, VueGoogleMaps, HasTaggableInput, SetInitialValueAsArray],
    components: { draggable },

    data: () => ({
        labelKey: 'address',
        center: {lat:10, lng:10},
        zoom: 4,
        markers: []
    }),
    mounted(){
        if(this.$_value.length){
            this.$_value.forEach( (place) => {
                this.setLocation({ lat: parseFloat(place.lat), lng: parseFloat(place.lng) })
            })
            this.centerAndZoom()
        }
    },

    computed: {
        $_attributes() {
            return {
                ...this.$_defaultFieldAttributes,
                value: this.inputValue
            }
        },
        $_events() {
            return {
                ...this.$_defaultFieldEvents,
                blur: this.blur,
                'place_changed': this.addPlaceToValue
            }
        },
        $_pristine() {
            return this.$_value.length == 0
        },
    },

    methods: {
        blur(){
            this.$_emptyInput()
            this.$_blurAction()
        },

        addRow(index) {
            this.component.value.push(_.cloneDeep(this.emptyRow))
        },

        deleteRow(index){
            if(this.$_value.length > 1){
                this.component.value.splice( index, 1)
            }else{
                this.component.value = [_.cloneDeep(this.emptyRow)]
            }
        },
        addPlaceToValue(place){
            place.address = place.formatted_address
            if(this.$_multiple){
                this.component.value.push(place)
            }else{
                this.component.value = [place]
            }
            this.setLocation({
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            })
            this.$_blurAction()
        },
        
        $_fill(jsonFormData) {
            this.$_value.forEach( (place, i) => {
                jsonFormData[this.$_name + '[' + i + ']']= JSON.stringify(place)          
            })
        },
        $_remove(index) {
            HasTaggableInput.methods.$_remove.call(this, index)
            this.markers.splice( index, 1)
        },
        setLocation(location){
            if(this.$_multiple){
                this.markers.push(location)
            }else{
                this.markers = [location]
            }
            this.centerAndZoom()
        },
        centerAndZoom(){

            var avgLat = 0, avgLng = 0, minLat = 1000, minLng = 1000, maxLat = -1000, maxLng = -1000
            this.markers.forEach( (marker) => {
                avgLat += parseFloat(marker.lat)
                avgLng += parseFloat(marker.lng)
                minLat = Math.min(minLat, parseFloat(marker.lat))
                minLng = Math.min(minLng, parseFloat(marker.lng))
                maxLat = Math.max(maxLat, parseFloat(marker.lat))
                maxLng = Math.max(maxLng, parseFloat(marker.lng))
            })

            this.center = {lat: avgLat/this.markers.length, lng: avgLng/this.markers.length }
            if(this.markers.length < 2){
                this.zoom = 14
            }else{
                var maxDist = Math.abs(Math.max(maxLat - minLat, maxLng - minLng))
                this.zoom = maxDist < 0.3 ? 12 : (maxDist < 1 ? 8 : (maxDist < 10 ? 4 : 1))
            }
        },
        setZoom(zoom){
            this.zoom = zoom
        }
    }
}
</script>

