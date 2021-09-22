<template>

    <v-card class="py-12">
        <v-card-text>
            <DialogSection icon="mdi-square" :color="color">
                <v-text-field
                    v-model="name"
                    label="カレンダー名"
                ></v-text-field>
            </DialogSection>
            <DialogSection icon="mdi-palette">
                <ColorForm v-model="color" />
            </DialogSection>
        </v-card-text>
        <v-card-actions class="d-flex justify-end">
            <v-btn @click="close">キャンセル</v-btn>
            <v-btn @click="submit" :disabled="$v.$invalid">保存</v-btn> <!--:disabled="$v.$invalid"を追加-->
        </v-card-actions>
    </v-card>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { validationMixin } from 'vuelidate'; // 追加
import { required } from 'vuelidate/lib/validators'; // 追加
import DialogSection from "../pageParts/DialogSection";
import ColorForm from "../form/ColorForm";

export default {
    name: "CalendarFormDialog",
    mixins: [validationMixin], // 追加
    components: { DialogSection, ColorForm },
    data: () => ({
        name: "",
        color: null,
    }),
    // 追加
    validations: {
        name: {required },
    },
    // ここまで
    computed: {
        ...mapGetters("calendars", ["calendar"]),
    },
    created() {
        this.name = this.calendar.name;
        this.color = this.calendar.color;
    },
    methods: {
        ...mapActions("calendars", [
            "createCalendar",
            "updateCalendar",
            "setCalendar",
        ]),
        close() {
            this.setCalendar(null);
        },
        submit() {
            // 追加
            if (this.$v.$invalid) {
                return;
            }
            // ここまで
            const user_id = document.getElementById("user_id").value;

            const params = {
                ...this.calendar,
                name: this.name,
                color: this.color,
                user_id: user_id,
            };

            if (params.id) {
                this.updateCalendar(params);
            } else {
                this.createCalendar(params);
            }
            this.close();
        },
    },
};
</script>
