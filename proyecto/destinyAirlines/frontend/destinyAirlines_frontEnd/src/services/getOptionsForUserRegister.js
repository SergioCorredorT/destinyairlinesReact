import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function getOptionsForUserRegister() {
    const response = await destinyAirlinesFetch(
        { command: "getOptionsForUserRegister" }
    );

    if (response.error) {
        toast.error(`Error en la petición a servidor: ${response.error}`);
        return { status: false, response: false };
    }

    if (response && response.status && response.response) {
        return { status: true, response: response.response };
    }

    toast.error(
        "Error en la petición a servidor"
    );
    return { status: false, response: false };
}