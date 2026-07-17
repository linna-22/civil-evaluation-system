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