import axios from 'axios';
import { serializeEvent } from '../../functions/serializers';

const state = {
    events: [],
    event: null,
    isEditMode: false,
};

const getters = {
    events: state => state.events.map(event => serializeEvent(event)),
    event: state => serializeEvent(state.event),
    isEditMode: state => state.isEditMode,
};

const mutations = {
    setEvents: (state, events) => (state.events = events),
    appendEvent: (state, event) => (state.events = [...state.events, event]),
    setEvent: (state, event) => (state.event = event),
    removeEvent: (state, event) => (state.events = state.events.filter(e => e.id !== event.id)),
    resetEvent: state => (state.event = null),
    // 追加
    updateEvent: (state, event) => (state.events = state.events.map(e => (e.id === event.id ? event : e))),
    // ここまで
    setEditMode: (state, bool) => (state.isEditMode = bool),
}

const actions = {
    async fetchEvents({ commit }) {
        const response = await axios.get('/api/events');
        commit('setEvents', response.data);
    },
    async createEvent({ commit }, event) {
        const response = await axios.post('/api/events', event);
        commit('appendEvent', response.data);
    },
    async deleteEvent({ commit }, id) {
        const response = await axios.delete(`/api/events/${id}`);
        commit('removeEvent', response.data);
        commit('resetEvent');
    },
    // メソッドを追加
    async updateEvent({ commit }, event) {
        const response = await axios.put(`/api/events/${event.id}`, event);
        commit('updateEvent', response.data);
    },
    // ここまで
    setEvent({ commit }, event) {
        commit('setEvent', event);
    },
    setEditMode({ commit }, bool) {
        commit('setEditMode', bool)
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
