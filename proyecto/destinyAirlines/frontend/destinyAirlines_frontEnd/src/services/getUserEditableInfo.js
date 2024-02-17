import { toast } from "react-toastify";
import { destinyAirlinesFetch } from "./fetchUtils";

export async function getUserEditableInfo({emailAddress, accessToken}) {
    const response = await destinyAirlinesFetch(
      { emailAddress, command: "getUserEditableInfo", accessToken  }
    );

    if (!response || !response.status || !response.response) {
        toast.error(`Error en la petición a servidor: ${response?.error || "Error desconocido"}`);
        return { status: false, response: false, error: response?.error || "Error desconocido"  };
    }

    return { status: true, response: response.response, error: null };
}
