<template>
    <v-checkbox
        v-model="value"
        @change="$emit('input', $event)"
        :label="label"
    ></v-checkbox>
</template>

<script>
export default {
    name: 'CheckBox',
    props: ['value', 'label'],
}
</script>
