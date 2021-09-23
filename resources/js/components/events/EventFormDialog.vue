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
                <!--:isError="isInvalidDatetime"を追加-->
                <DateForm v-model="endDate" :isError="isInvalidDatetime" />
                <div v-show="!allDay">
                    <!--:isError="isInvalidDatetime"を追加-->
                    <TimeForm v-model="endTime" :isError="isInvalidDatetime" />
                </div>
                <CheckBox v-model="allDay" label="終日" />
            </DialogSection>
            <!--追加-->
            <v-alert
                dense
                outlined
                type="error"
                v-show="isInvalidDatetime"
            >終了日時は開始日時より後に設定してください。</v-alert>
            <!--ここまで-->

            <DialogSection icon="mdi-card-text-outline">
                <TextForm v-model="description" />
            </DialogSection>
            <DialogSection icon="mdi-calendar">
                <CalendarSelectForm
                    :value="calendar"
                    @input="changeCalendar($event)"
                />
            </DialogSection>
            <DialogSection icon="mdi-palette">
                <ColorForm v-model="color" />
            </DialogSection>
        </v-card-text>
        <v-card-actions class="d-flex justify-end">
            <v-btn @click="cancel">キャンセル</v-btn>
            <v-btn :disabled="isInvalid" @click="submit">保存</v-btn> <!--:disabled="isInvalid"を追加-->
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { validationMixin } from 'vuelidate'; // 追加
import { required } from 'vuelidate/lib/validators'; // 追加
import DialogSection from "../pageParts/DialogSection";
import DateForm from "../form/DateForm";
import TimeForm from "../form/TimeForm";
import TextForm from "../form/TextForm";
import ColorForm from "../form/ColorForm";
import CheckBox from "../form/CheckBox";
import CalendarSelectForm from "../form/CalendarSelectForm";
import { isGreaterEndThanStart } from "../../functions/datetime"; // 追加

export default {
    name: "EventFormDialog",
    mixins: [validationMixin], // 追加
    components: {
        DialogSection,
        DateForm,
        TimeForm,
        TextForm,
        ColorForm,
        CheckBox,
        CalendarSelectForm,
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
        calendar: null,
    }),
    // 追加
    validations: {
        name: { required },
        startDate: { required },
        endDate: { required },
        calendar: {required },
    },
    // ここまで
    computed: {
        ...mapGetters("events", ["event"]),
        // 追加
        isInvalidDatetime() {
            return !isGreaterEndThanStart(this.startDate, this.startTime, this.endDate, this.endTime, this.allDay);
        },
        isInvalid() {
            return this.$v.$invalid || this.isInvalidDatetime;
        },
        // ここまで
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
        this.calendar = this.event.calendar;
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
            // 追加
            if (this.isInvalid) {
                return;
            }
            // ここまで
            const params = {
                ...this.event,
                name: this.name,
                start: `${this.startDate} ${this.startTime || ""}`,
                end: `${this.endDate} ${this.endTime || ""}`,
                description: this.description,
                color: this.color,
                timed: !this.allDay,
                calendar_id: this.calendar.id,
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
        changeCalendar(calendar) {
            this.color = calendar.color;
            this.calendar = calendar;
        },
    },
};
</script>
