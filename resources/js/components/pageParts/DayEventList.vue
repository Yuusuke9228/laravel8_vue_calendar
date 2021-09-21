<template>
    <v-card class="pb-8">
        <v-card-actions class="d-flex justify-end">
            <v-btn icon @click="closeDialog">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-card-actions>
        <v-card-title class="d-flex justify-center">
            {{ formatDateToJa(clickedDate) }}
        </v-card-title>
        <v-card-text>
            <div v-for="event in dayEvents" :key="event.id">
                <v-timeline>
                    <v-timeline-item :color="event.color" small>
                        <v-row justify="space-between">
                            <v-col cols="7" v-text="event.name"></v-col>
                            <v-col
                                class="text-right"
                                cols="5"
                                v-text="event.startTime"
                            ></v-col>
                        </v-row>
                    </v-timeline-item>
                </v-timeline>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { formatDateToJa } from "../../functions/datetime";

export default {
    name: "DayEventList",
    computed: {
        ...mapGetters("events", ["dayEvents", "clickedDate"]),
    },
    methods: {
        ...mapActions("events", ["setClickedDate"]),
        formatDateToJa,
        closeDialog() {
            this.setClickedDate(null);
        },
    },
};
</script>
