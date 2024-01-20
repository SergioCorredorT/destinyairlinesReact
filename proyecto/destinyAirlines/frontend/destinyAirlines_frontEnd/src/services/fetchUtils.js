export const customFetch = async (url, formData) => {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        });
        const data = await response.text();
        return JSON.parse(data);
    } catch (error) {
        return { error };
    }
};