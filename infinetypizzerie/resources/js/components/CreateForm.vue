<template>
    <b-card>
        <h1>{{ formTitle }}</h1>
        <hr>
        <b-form
            @submit.prevent="alertCountdown = 5; save();"
        >
            <b-alert
                class="my-2"
                dismissible
                fade
                v-if="result"
                :show="alertCountdown"
                :variant="result.status"
                @dismissed="alertCountdown = 0"
                @dismiss-count-down="countdown"
            >
                {{ result.message }}
            </b-alert>
            <slot name="inputs"></slot>
            <hr>
            <b-row no-gutters>
                <b-button
                    class="ml-0 mr-auto"
                    variant="outline-warning"
                    @click="reset()"
                >
                    Cancelar
                </b-button>
                <b-button
                    class="ml-auto mr-0"
                    type="submit"
                    variant="primary"
                    :disabled="saveBtnDisabled"
                    :title="saveBtnTitle"
                >
                    Guardar
                </b-button>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
export default {
    data() {
        return {
            alertCountdown: 5, /* Improves the UX for the alert feedback on form saves */
        }
    },
    methods: {
        /**
         * Manage the alert auto hide behavior
         */
        countdown(count) {
            this.alertCounddown = count;
        },
    },
    props: {
        formTitle: String, /* The title for the form */
        reset: Function, /* The function to reset the form inputs */
        result: Object, /* Contains values to show the feedback of a form save */
        save: Function, /* Form save function */
        saveBtnDisabled: Boolean, /* Determines if the save button has to be disabled */
        saveBtnTitle: String, /* Determines the title text of the save button */
    }
}
</script>
