import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function forgotPassword({emailAddress}) {
    const response = await destinyAirlinesFetch(
        { command: "forgotPassword", emailAddress }
    );

    if (response.error) {
        toast.error(`Error en la petición a servidor: ${response.error}`);
        return { status: false, response: false, message: "Error en la petición a servidor" };
    }

    if (response && response.status && response.response) {
        toast.success(
            "Email enviado con éxito para cambio de contraseña. Recuerda revisar que esté bien escrito el email"
        );
        return { status: true, response: response.response, message: "Email enviado con éxito para cambio de contraseña" };
    }

    toast.error(
        "Error en la petición a servidor"
    );
    return { status: false, response: false, message: "Error en la petición a servidor" };
}
