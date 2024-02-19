import { destinyAirlinesFetch } from "./fetchUtils";

export async function getUpdateTime() {
    const response = await destinyAirlinesFetch(
        { command: "getUpdateTime" }
    );

    if (response.error) {
        return { status: false, response: false, message: "Error en la petición a servidor" };
    }

    if (response && response.status && response.response) {
        return { status: true, response: response.response, message: "Tiempo retornado con éxito" };
    }

    return { status: false, response: false, message: "Error en la petición, es posible que la contraseña no sea la correcta" };
}
