import Swal from "sweetalert2";

const defaultOptions = {

    confirmButtonColor: "#2563eb",
    cancelButtonColor: "#dc2626",

};

export function alert(options = {}) {

    return Swal.fire({

        ...defaultOptions,

        ...options,

    });

}

export function success(message, title = "ជោគជ័យ") {

    return alert({

        icon: "success",

        title,

        text: message,

    });

}

export function error(message, title = "បរាជ័យ") {

    return alert({

        icon: "error",

        title,

        text: message,

    });

}

export function warning(message, title = "ប្រុងប្រយ័ត្ន") {

    return alert({

        icon: "warning",

        title,

        text: message,

    });

}

export function confirm(message, title = "បញ្ជាក់") {

    return alert({

        icon: "question",

        title,

        text: message,

        showCancelButton: true,

        confirmButtonText: "យល់ព្រម",

        cancelButtonText: "បោះបង់",

    });

}