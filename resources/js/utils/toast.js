import Swal from "sweetalert2";

const Toast = Swal.mixin({

    toast: true,

    position: "top-end",

    showConfirmButton: false,

    timer: 3000,

    timerProgressBar: true,

});

export function successToast(message) {

    Toast.fire({

        icon: "success",

        title: message,

    });

}

export function errorToast(message) {

    Toast.fire({

        icon: "error",

        title: message,

    });

}

export function warningToast(message) {

    Toast.fire({

        icon: "warning",

        title: message,

    });

}