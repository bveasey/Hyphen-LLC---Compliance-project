import { ref } from "vue";

export default function formErrors()
{
    const formErrors = ref(null);

    function formErrorBindings(field) {
        if (!formErrors.value) return null;
        const errorMessages = formErrors.value?.[field] ?? [];
        if (errorMessages.length) return { errorMessages };
        return null;
    }

    function clearFormErrors() {
        setFormErrors();
    }

    function setFormErrors(errors = null) {
        if (errors instanceof Error) return;
        formErrors.value = errors;
    }

    return {
        setFormErrors,
        clearFormErrors,
        formErrorBindings
    }
};