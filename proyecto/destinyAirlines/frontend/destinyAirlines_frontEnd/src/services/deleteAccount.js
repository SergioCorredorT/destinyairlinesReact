import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function deleteAccount({ emailAddress, refreshToken, password }) {
    const response = await destinyAirlinesFetch(
        { command: "removeUser", emailAddress, password, refreshToken }
    );

    if (response.error) {
        toast.error(`Error en la petición a servidor: ${response.error}`);
        return { status: false, response: false, message: "Error en la petición a servidor" };
    }

    if (response && response.status && response.response) {
        toast.success(
            "Cuenta eliminada con éxito"
        );
        return { status: true, response: response.response, message: "Cuenta eliminada con éxito" };
    }

    toast.error(
        "Error en la petición, es posible que la contraseña no sea la correcta"
    );
    return { status: false, response: false, message: "Error en la petición, es posible que la contraseña no sea la correcta" };
}
