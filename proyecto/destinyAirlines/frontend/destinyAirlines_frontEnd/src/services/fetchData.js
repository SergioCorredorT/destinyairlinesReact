function getSuspender(promise) {
    let status = "pending";
    let response;

    const suspender = promise.then(
        (res) => {
            status = "success";
            response = res;
        },
        (err) => {
            status = "error";
            response = err;
        }
    )

    const read = () => {
        const statusHandlers = {
            pending: () => { throw suspender },
            error: () => { throw response },
            success: () => { return response },
            default: () => { throw response }
        };

        // En la siguiente línea se ejecuta la función contenida en la posición de statusHandlers en vez de devolver la función en sí
        return (statusHandlers[status])() || statusHandlers.default();
    }

    return { read }
}

export function fetchData(url, options) {
    const promise = fetch(url, options)
        .then((response) => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json();
        })
        .catch((error) => {
            throw new Error(error.message);
        });

    return getSuspender(promise);
}
