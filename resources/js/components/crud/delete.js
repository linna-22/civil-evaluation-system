import { confirm } from "../../utils/alert";
import { successToast, errorToast } from "../../utils/toast";
import { destroy } from "../../utils/api";

export async function deleteRecord(url, reload) {

    const result = await confirm("តើអ្នកពិតជាចង់លុបទិន្នន័យនេះមែនទេ?");

    if (!result.isConfirmed) {
        return;
    }

    try {

        const response = await destroy(url);

        if (response.success) {

            successToast(response.message);

            reload();

        } else {

            console.log(message);
            errorToast(response.message);

        }

    } catch (error) {
        console.error(error);
        errorToast("មានបញ្ហាក្នុងការលុបទិន្នន័យ");

    }

}