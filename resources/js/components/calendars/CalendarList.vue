<template>
    <v-list dense>
        <v-list-item>
            <v-list-item-content>
                <v-subheader>マイカレンダー</v-subheader>
            </v-list-item-content>
            <v-list-item-action>
                <v-btn icon @click="initCalendar">
                    <v-icon size="16px">mdi-plus</v-icon>
                </v-btn>
            </v-list-item-action>
        </v-list-item>
        <v-list-item-group :value="selectedItem">
            <v-list-item v-for="calendar in calendars" :key="calendar.id">
                <v-list-item-content class="pa-1">
                    <v-checkbox
                        dense
                        v-model="calendar.visibility"
                        :color="calendar.color"
                        :label="calendar.name"
                        class="pb-2"
                        hide-details="true"
                    ></v-checkbox>
                </v-list-item-content>
                <v-list-item-action class="ma-0">
                    <v-menu transition="scale-transition" offset-y min-width="100px">
                        <template v-slot:activator="{ on }">
                            <v-btn icon v-on="on">
                                <v-icon size="12px">mdi-dots-vertical</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <!--追加-->
                            <v-list-item @click="editCalendar(calendar)">編集</v-list-item>
                            <v-list-item @click="delCalendar(calendar)">削除</v-list-item>
                            <!--ここまで-->
                        </v-list>
                    </v-menu>
                </v-list-item-action>
            </v-list-item>
        </v-list-item-group>
        <v-dialog :value="calendar !== null" @click:outside="closeDialog" width="600">
            <CalendarFormDialog v-if="calendar !== null" />
        </v-dialog>
    </v-list>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import CalendarFormDialog from './CalendarFormDialog'; // 追加

export default {
    name: 'CalendarList',
    data: () => ({
        selectedItem: null,
    }),
    components: { CalendarFormDialog },
    computed: {
        ...mapGetters('calendars', ['calendars', 'calendar']), // 'calendar'追加
    },
    created() {
        this.fetchCalendars();
    },
    methods: {
        ...mapActions('calendars', ['fetchCalendars', 'deleteCalendar', 'setCalendar']), // 'deleteCalendar'追加
        initCalendar() {
            this.setCalendar({
                name: '',
                visibility: true,
            });
        },
        closeDialog() {
            this.setCalendar(null);
        },
        editCalendar(calendar) {
            this.setCalendar(calendar);
        },
        // 追加
        delCalendar(calendar) {
            const res = confirm(`「${calendar.name}」を削除してもよろしいですか？`);
            if(res) {
                this.deleteCalendar(calendar.id);
            }
        },
        // ここまで
    },
};
</script>
