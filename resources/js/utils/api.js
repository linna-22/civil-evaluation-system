export async function get(url) {

    const response = await fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
        }
    });

    if (!response.ok) {
        throw new Error("Failed to load data.");
    }

    return await response.json();
}