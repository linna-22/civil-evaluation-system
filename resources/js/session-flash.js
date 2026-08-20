import {
    successToast,
    errorToast
} from "./utils/toast";

const success = document.body.dataset.success;
const error = document.body.dataset.error;

if (success) {

    successToast(success);

}

if (error) {

    errorToast(error);

}