export const customFetch = async (url, JsonData) => {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(JsonData),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.text();
        return JSON.parse(data);
    } catch (error) {
        if (error.name === 'TypeError') {
            return { error: 'There was a problem fetching the data.' };
        } else if (error.message.startsWith('HTTP error')) {
            return { error: 'There was a problem with the server.' };
        }
        return { error: 'An unknown error occurred.' };
    }
};

export const destinyAirlinesFetch = async (JsonData) => {
    const url = import.meta.env.VITE_API_URL;
    return await customFetch(url, JsonData);
};