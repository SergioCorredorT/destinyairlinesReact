import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function updateUser({ newUserInfo, accessToken, emailAddressAuth }) {

    const response = await destinyAirlinesFetch(
        { command: "updateUser", ...newUserInfo, accessToken, emailAddressAuth }
    );

    if (response.error) {
        toast.error(`Error en la petición a servidor: ${response.error}`);
        return { status: false, response: false, message: "Error en la petición a servidor" };
    }

    if (response && response.status && response.response) {
        toast.success(
            "Cuenta actualizada con éxito"
        );
        return { status: true, response: response.response, message: "Cuenta eliminada con éxito" };
    }

    toast.error(
        "Error en la petición"
    );
    return { status: false, response: false, message: "Error en la petición" };
}
