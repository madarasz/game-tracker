<template>
    <button type="button" :class="buttonClass" data-toggle="modal" data-target="#modal-confirm" @click="$emit('click');">
        <i :class="buttonIcon" aria-hidden="true" v-if="buttonIcon"></i>
        {{ buttonText }}
    </button>
</template>

<script>
    export default {
        props: ['buttonText', 'buttonClass', 'buttonIcon']
    }
</script>
