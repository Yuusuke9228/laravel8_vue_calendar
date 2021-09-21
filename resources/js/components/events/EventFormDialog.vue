<template>
    <v-card class="pb-12">
        <v-card-actions class="d-flex justify-end pa-2">
            <v-btn icon @click="closeDialog">
                <v-icon size="20px">mdi-close</v-icon>
            </v-btn>
        </v-card-actions>
        <v-card-text>
            <DialogSection icon="mdi-square" :color="color">
                <v-text-field v-model="name" label="タイトル"></v-text-field>
            </DialogSection>
            <DialogSection icon="mdi-clock-outline">
                <DateForm v-model="startDate" />
                <div v-show="!allDay">
                    <TimeForm v-model="startTime" />
                </div>
                <DateForm v-model="endDate" />
                <div v-show="!allDay">
                    <TimeForm v-model="endTime" />
                </div>
                <CheckBox v-model="allDay" label="終日" />
            </DialogSection>
            <DialogSection icon="mdi-card-text-outline">
                <TextForm v-model="description" />
            </DialogSection>
            <!--追加-->
            <DialogSection icon="mdi-calendar">
                <CalendarSelectForm
                    :value="calendar"
                    @input="changeCalendar($event)"
                />
            </DialogSection>
            <!--ここまで-->
            <DialogSection icon="mdi-palette">
                <ColorForm v-model="color" />
            </DialogSection>
        </v-card-text>
        <v-card-actions class="d-flex justify-end">
            <v-btn @click="cancel">キャンセル</v-btn>
            <v-btn @click="submit">保存</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import DialogSection from "../pageParts/DialogSection";
import DateForm from "../form/DateForm";
import TimeForm from "../form/TimeForm";
import TextForm from "../form/TextForm";
import ColorForm from "../form/ColorForm";
import CheckBox from "../form/CheckBox";
import CalendarSelectForm from "../form/CalendarSelectForm"; // 追加

export default {
    name: "EventFormDialog",
    components: {
        DialogSection,
        DateForm,
        TimeForm,
        TextForm,
        ColorForm,
        CheckBox,
        CalendarSelectForm, // 追加
    },
    data: () => ({
        name: "",
        startDate: null,
        startTime: null,
        endDate: null,
        endTime: null,
        description: "",
        color: "",
        allDay: false,
        calendar: null, // 追加
    }),
    computed: {
        ...mapGetters("events", ["event"]),
    },
    created() {
        this.name = this.event.name;
        this.startDate = this.event.startDate;
        this.startTime = this.event.startTime;
        this.endDate = this.event.endDate;
        this.endTime = this.event.endTime;
        this.description = this.event.description;
        this.color = this.event.color;
        this.allDay = !this.event.timed;
        this.calendar = this.event.calendar; // 追加
    },
    methods: {
        ...mapActions("events", [
            "setEvent",
            "setEditMode",
            "createEvent",
            "updateEvent",
        ]),
        closeDialog() {
            this.setEditMode(false);
            this.setEvent(null);
        },
        submit() {
            const params = {
                ...this.event,
                name: this.name,
                start: `${this.startDate} ${this.startTime || ""}`,
                end: `${this.endDate} ${this.endTime || ""}`,
                description: this.description,
                color: this.color,
                timed: !this.allDay,
                calendar_id: this.calendar.id, // 追加
            };
            if (params.id) {
                this.updateEvent(params);
            } else {
                this.createEvent(params);
            }
            this.closeDialog();
        },
        cancel() {
            this.setEditMode(false);
            if (!this.event.id) {
                this.setEvent(null);
            }
        },
        // 追加
        changeCalendar(calendar) {
            this.color = calendar.color;
            this.calendar = calendar;
        },
        // ここまで
    },
};
</script>
