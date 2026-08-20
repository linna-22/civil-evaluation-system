export async function get(url) {

    const response = await fetch(url, {

        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
        }

    });

    if (!response.ok) {
        throw new Error("Failed to load data.");
    }

    return await response.json();

}


export async function patch(url, data = {}) {

    const response = await fetch(url, {

        method: "PATCH",

        headers: {

            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .content,

            "X-Requested-With": "XMLHttpRequest",

            "Accept": "application/json",

            "Content-Type": "application/json",

        },

        body: JSON.stringify(data),

    });


    if (!response.ok) {

        const error = await response.json()
            .catch(() => null);

        throw new Error(
            error?.message || "Update failed."
        );

    }


    return await response.json();

}


export async function destroy(url) {

    const response = await fetch(url, {

        method: "DELETE",

        headers: {

            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .content,

            "X-Requested-With": "XMLHttpRequest",

            "Accept": "application/json",

        },

    });


    if (!response.ok) {
        throw new Error("Delete failed.");
    }


    return await response.json();

}