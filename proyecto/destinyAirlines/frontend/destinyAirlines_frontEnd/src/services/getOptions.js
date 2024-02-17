import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function getOptions({listOptions}) {
    const response = await destinyAirlinesFetch(
        { command: "getOptions", listOptions }
    );

    if (!response || !response.status || !response.response) {
        toast.error(`Error en la petición a servidor: ${response?.error || ""}`);
        return { status: false, response: false };
    }

    return { status: true, response: response.response };
}
