<template>
    <div>
        <v-sheet height="6vh" class="d-flex align-center" color="lighten-3">
            <v-btn outlined small class="ma-4" @click="setToday">TODAY</v-btn>
            <v-btn icon @click="$refs.calendar.prev()">
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <v-btn icon @click="$refs.calendar.next()">
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-sheet class="d-flex">
                <v-select
                    dense
                    v-model="viewSelect"
                    :items="items"
                    item-text="text"
                    item-value="value"
                    label="Select"
                    persistent-hint
                    return-object
                    single-line
                    prepend-icon="mdi-calendar"
                    @change="changeView(viewSelect.value, $event)"
                ></v-select>
            </v-sheet>
        </v-sheet>
        <v-sheet height="94vh" class="d-flex">
            <v-sheet width="200px">
                <CalendarList />
            </v-sheet>
            <v-sheet class="flex">
                <v-calendar
                    ref="calendar"
                    :type="type || 'month'"
                    v-model="value"
                    :events="events"
                    @change="fetchEvents"
                    locale="ja-jp"
                    :day-format="
                        (timestamp) => new Date(timestamp.date).getDate()
                    "
                    :month-format="
                        (timestamp) =>
                            new Date(timestamp.date).getMonth() + 1 + ' /'
                    "
                    @click:event="showEvent"
                    @click:day="initEvent"
                    @click:date="showDayEvents"
                    @click:more="showDayEvents"
                ></v-calendar>
            </v-sheet>
        </v-sheet>

        <v-dialog
            :value="event !== null"
            @click:outside="closeDialog"
            width="600"
        >
            <EventDetailDialog v-if="event !== null && !isEditMode" />
            <EventFormDialog v-if="event !== null && isEditMode" />
        </v-dialog>

        <v-dialog
            :value="clickedDate !== null"
            @click:outside="closeDialog"
            width="600"
        >
            <DayEventList />
        </v-dialog>
    </div>
</template>

<script>
import { format } from "date-fns";
import { mapActions, mapGetters } from "vuex";
import EventDetailDialog from "../events/EventDetailDialog";
import EventFormDialog from "../events/EventFormDialog";
import CalendarList from "../calendars/CalendarList";
import DayEventList from "../pageParts/DayEventList";

export default {
    name: "Calendar",
    data: () => ({
        value: format(new Date(), "yyyy/MM/dd"), // 初期値を今日の月にする
        user_id: document.getElementById("user_id").value,
        type: "month",
        viewSelect: { value: "month", text: "月表示" },
        items: [
            { value: "month", text: "月表示" },
            { value: "week", text: "週表示" },
            { value: "day", text: "日表示" },
        ],
    }),
    components: {
        EventDetailDialog,
        EventFormDialog,
        CalendarList,
        DayEventList,
    },
    computed: {
        ...mapGetters("events", [
            "events",
            "event",
            "isEditMode",
            "clickedDate",
        ]),
        title() {
            switch (this.type) {
                case "month":
                    return format(new Date(this.value), "yyyy年 M月");
                case "week":
                    return this.formedDateOfThisWeek(new Date(this.value));
                case "day":
                    return format(new Date(this.value), "yyyy年 M月 d日");
            }
        },
    },
    methods: {
        ...mapActions("events", [
            "fetchEvents",
            "setEvent",
            "setEditMode",
            "setClickedDate",
        ]),
        setToday() {
            this.value = format(new Date(), "yyyy/MM/dd");
        },
        showEvent({ nativeEvent, event }) {
            this.setEvent(event);
            nativeEvent.stopPropagation();
        },
        closeDialog() {
            this.setEvent(null);
            this.setEditMode(false);
            this.setClickedDate(null);
        },
        initEvent({ date }) {
            if (this.clickedDate !== null) {
                return;
            }
            date = date.replace(/-/g, "/");
            const start = format(new Date(date), "yyyy/MM/dd 00:00:00");
            const end = format(new Date(date), "yyyy/MM/dd 01:00:00");
            this.setEvent({ name: "", start, end, timed: true });
            this.setEditMode(true);
        },
        showDayEvents({ date }) {
            date = date.replace(/-/g, "/");
            this.setClickedDate(date);
        },
        changeView(text, event) {
            this.type = event.value;
        },
        formedDateOfThisWeek(today) {
            const this_year = today.getFullYear();
            const this_month = today.getMonth();
            const date = today.getDate();
            const day_num = today.getDay();
            const this_sunday = date - day_num;
            const this_saturday = this_sunday + 6;
            const day = String("日月火水木金土");
            let start_date = new Date(this_year, this_month, this_sunday);
            start_date = start_date.getFullYear() + "年" + (start_date.getMonth() + 1) + "月" + start_date.getDate() + "日" + " (" + day.charAt(start_date.getDay()) + ")";
            let end_date = new Date(this_year, this_month, this_saturday);
            end_date = end_date.getFullYear() + "年" + (end_date.getMonth() + 1) + "月" + end_date.getDate() + "日" + " (" + day.charAt(end_date.getDay()) + ")";

            return start_date + " ～ " + end_date;
        },
    },
};
</script>
